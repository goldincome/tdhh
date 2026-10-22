<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SeoBackfillMediaDimensions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:backfill-media-dimensions
                            {--dry-run : Report what would change without writing to the database}
                            {--only-missing : Only process media rows whose width or height is NULL}
                            {--limit=0 : Process at most this many rows (0 = no limit)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the pixel dimensions of existing media files and store them in media.width / media.height';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (! Schema::hasTable('media')) {
            $this->error('The media table does not exist.');

            return self::FAILURE;
        }

        if (! Schema::hasColumn('media', 'width') || ! Schema::hasColumn('media', 'height')) {
            $this->error('The media table has no width/height columns. Run "php artisan migrate --force" first.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $onlyMissing = (bool) $this->option('only-missing');
        $limit = (int) $this->option('limit');

        $query = Media::query()->orderBy('id');

        if ($onlyMissing) {
            $query->where(function ($q) {
                $q->whereNull('width')->orWhereNull('height');
            });
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->info('Nothing to backfill. No media rows match the filter.');

            return self::SUCCESS;
        }

        $this->info(($dryRun ? 'Dry run. ' : '').'Scanning '.$total.' media row(s).');

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($query->with('model')->cursor() as $media) {
            $dimensions = $this->dimensionsFor($media);

            if ($dimensions === null) {
                $skipped++;
                $bar->advance();

                continue;
            }

            [$width, $height] = $dimensions;

            if ($media->width === $width && $media->height === $height) {
                $skipped++;
                $bar->advance();

                continue;
            }

            if (! $dryRun) {
                $media->newQuery()->whereKey($media->getKey())->update([
                    'width' => $width,
                    'height' => $height,
                ]);
            }

            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Media rows scanned', $total],
                ['Dimensions resolved', $updated],
                ['Unchanged or not an image', $skipped],
                ['Unreadable file', $failed],
                ['Mode', $dryRun ? 'DRY RUN (no writes)' : 'WRITTEN'],
            ]
        );

        if (! $dryRun && $updated > 0) {
            $this->info('Re-run "php artisan seo:diagnose" to see the media coverage in the report.');
        }

        return self::SUCCESS;
    }

    /**
     * Resolve the pixel dimensions for a media row.
     *
     * @param  \App\Models\Media  $media
     * @return array{0: int, 1: int}|null
     */
    protected function dimensionsFor(Media $media): ?array
    {
        if (! $media->isImage()) {
            return null;
        }

        $relativePath = $media->getPathRelativeToRoot();

        if ($relativePath === '') {
            return null;
        }

        try {
            $disk = Storage::disk($media->disk);

            if (! $disk->exists($relativePath)) {
                $this->line('  <comment>missing file:</comment> '.$media->id.' '.$relativePath);

                return null;
            }

            $size = $this->readImageSize($disk, $relativePath);
        } catch (\Throwable $e) {
            $this->line('  <comment>unreadable:</comment> '.$media->id.' '.$e->getMessage());

            return null;
        }

        if (! is_array($size) || empty($size[0]) || empty($size[1])) {
            return null;
        }

        return [(int) $size[0], (int) $size[1]];
    }

    /**
     * Resolve the image size from a local path or a remote disk.
     *
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $disk
     * @param  string  $relativePath
     * @return array<int, int>|false
     */
    protected function readImageSize($disk, string $relativePath)
    {
        $localPath = null;

        if (method_exists($disk, 'path')) {
            $localPath = $disk->path($relativePath);
        }

        if ($localPath !== null && is_file($localPath)) {
            $size = @getimagesize($localPath);

            if ($size !== false) {
                return $size;
            }
        }

        return @getimagesizefromstring($disk->get($relativePath));
    }
}

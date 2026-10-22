<?php

namespace App\Console\Commands;

use App\Models\Property\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BackfillPropertySlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:backfill-slugs
                            {--dry-run : Report what would change without writing to the database}
                            {--force : Overwrite an existing slug even when it is already populated}
                            {--limit=0 : Process at most this many rows (0 = no limit)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate properties.slug and properties.area_slug with SEO-safe, unique URL segments';

    /**
     * The maximum length of a slug. The column is a varchar(191) so the
     * unique index fits inside the InnoDB key limit on every server.
     */
    protected const MAX_LENGTH = 191;

    /**
     * The number of slug collisions that were resolved.
     *
     * @var int
     */
    protected $collisions = 0;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->collisions = 0;

        if (! Schema::hasTable('properties')) {
            $this->error('The properties table does not exist.');

            return self::FAILURE;
        }

        if (! Schema::hasColumn('properties', 'slug') || ! Schema::hasColumn('properties', 'area_slug')) {
            $this->error('The properties table has no slug/area_slug columns. Run "php artisan migrate --force" first.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $limit = (int) $this->option('limit');

        $query = Property::query()->orderBy('reference');

        if (! $force) {
            $query->where(function ($q) {
                $q->whereNull('slug')->orWhereNull('area_slug');
            });
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->info('Nothing to backfill. Every property already has a slug and an area_slug (or --force was not used).');

            return self::SUCCESS;
        }

        $this->info(($dryRun ? 'Dry run. ' : '').'Scanning '.$total.' property row(s).');

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        // Slugs that are already taken, mapped to the reference of the
        // property that owns them. The reference is the identity used to
        // recognise a property's own slug, so the mapping must be keyed by
        // slug and hold the reference as the value.
        $taken = DB::table('properties')
            ->whereNotNull('slug')
            ->pluck('reference', 'slug')
            ->all();

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $noArea = 0;

        foreach ($query->cursor() as $property) {
            $slug = $this->uniqueSlug($property, $taken);

            if ($slug === null) {
                $skipped++;
                $bar->advance();

                continue;
            }

            if ($slug !== $property->slug) {
                $taken[$slug] = $property->reference;
                $created++;
            } else {
                $updated++;
            }

            $areaSlug = $this->areaSlugFor($property);

            if ($areaSlug === null) {
                $noArea++;
            }

            if (! $dryRun) {
                Property::query()
                    ->whereKey($property->getKey())
                    ->update([
                        'slug' => $slug,
                        'area_slug' => $areaSlug,
                    ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Properties scanned', $total],
                ['Slug created', $created],
                ['Slug unchanged', $updated],
                ['Skipped (no usable name)', $skipped],
                ['Slug collisions disambiguated', $this->collisions],
                ['No area resolved (fell back to "london")', $noArea],
                ['Mode', $dryRun ? 'DRY RUN (no writes)' : 'WRITTEN'],
            ]
        );

        if (! $dryRun) {
            $this->info('Re-run "php artisan seo:diagnose" to see the slug coverage in the report.');
        }

        return self::SUCCESS;
    }

    /**
     * Build a unique slug for a property.
     *
     * The base is the slugified property name. On a collision the property
     * reference number is appended, because the reference is already unique.
     *
     * @param  \App\Models\Property\Property  $property
     * @param  array<string, mixed>  $taken
     * @return string|null
     */
    protected function uniqueSlug(Property $property, array &$taken): ?string
    {
        $name = trim((string) $property->name);

        if ($name === '') {
            $name = 'stay-'.$property->reference;
        }

        $base = $this->slugify($name);

        if ($base === '') {
            $base = 'stay-'.$property->reference;
        }

        // The property keeps its own slug when it is already unique.
        if (! isset($taken[$base]) || $taken[$base] === $property->reference) {
            return $base;
        }

        $candidate = $base.'-'.$property->reference;
        $suffix = 2;

        while (isset($taken[$candidate]) && $taken[$candidate] !== $property->reference) {
            $candidate = $base.'-'.$property->reference.'-'.$suffix;
            $suffix++;
        }

        $this->collisions++;

        return $candidate;
    }

    /**
     * Build the area segment for a property.
     *
     * The area is derived from the free text city_name, then state_name, then
     * the outward code of the postcode. The city_id and state_id columns
     * point at generic country and region tables that are not meaningful for
     * area hubs, so they are deliberately not used here.
     *
     * @param  \App\Models\Property\Property  $property
     * @return string|null
     */
    protected function areaSlugFor(Property $property): ?string
    {
        foreach ([$property->city_name, $property->state_name] as $candidate) {
            $slug = $this->slugify((string) $candidate);

            if ($slug !== '') {
                return $slug;
            }
        }

        $outward = $this->outwardCode((string) $property->postal_code);

        if ($outward !== '') {
            return $outward;
        }

        return 'london';
    }

    /**
     * Extract the outward code from a UK postcode.
     *
     * @param  string  $postcode
     * @return string
     */
    protected function outwardCode(string $postcode): string
    {
        $postcode = strtoupper(trim($postcode));

        if ($postcode === '') {
            return '';
        }

        if (preg_match('/^([A-Z]{1,2}\d[A-Z\d]?)\s*\d[A-Z]{2}$/', $postcode, $m)) {
            return $this->slugify($m[1]);
        }

        return '';
    }

    /**
     * Convert a string into a URL safe slug.
     *
     * @param  string  $value
     * @return string
     */
    protected function slugify(string $value): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            return '';
        }

        if (Str::length($slug) > self::MAX_LENGTH) {
            $slug = Str::limit($slug, self::MAX_LENGTH, '');
        }

        return trim($slug, '-');
    }
}

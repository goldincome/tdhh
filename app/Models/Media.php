<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * Captures the pixel dimensions of every newly registered image so the
     * Open Graph and JSON-LD tags can declare og:image:width, og:image:height
     * and image:width / image:height. Those values are required for a valid
     * image object and let search engines pick the correct crop.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function (self $media) {
            if ($media->width !== null && $media->height !== null) {
                return;
            }

            if (! $media->isImage()) {
                return;
            }

            $dimensions = $media->detectDimensions();

            if ($dimensions === null) {
                return;
            }

            // Write directly so the model is not re-saved recursively and
            // the updated_at timestamp is not needlessly touched.
            $media->newQuery()
                ->whereKey($media->getKey())
                ->update([
                    'width' => $dimensions[0],
                    'height' => $dimensions[1],
                ]);

            $media->setAttribute('width', $dimensions[0]);
            $media->setAttribute('height', $dimensions[1]);
        });
    }

    /**
     * Determine whether the media item is an image.
     *
     * @return bool
     */
    public function isImage(): bool
    {
        if ($this->mime_type !== null && str_starts_with($this->mime_type, 'image/')) {
            return true;
        }

        $extension = strtolower(pathinfo((string) $this->file_name, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'avif', 'tif', 'tiff'], true);
    }

    /**
     * Read the pixel dimensions of the stored file.
     *
     * @return array{0: int, 1: int}|null
     */
    public function detectDimensions(): ?array
    {
        $relativePath = $this->getPathRelativeToRoot();

        if ($relativePath === '') {
            return null;
        }

        try {
            $disk = Storage::disk($this->disk);

            if (! $disk->exists($relativePath)) {
                return null;
            }

            $size = $this->readImageSize($disk, $relativePath);
        } catch (\Throwable $e) {
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

    /**
     * The aspect ratio of the image, or null when it is not known.
     *
     * @return float|null
     */
    public function getAspectRatioAttribute(): ?float
    {
        if (! $this->width || ! $this->height) {
            return null;
        }

        return round($this->width / $this->height, 4);
    }

    /**
     * Whether the image is landscape, portrait or square.
     *
     * @return string|null
     */
    public function getOrientationAttribute(): ?string
    {
        if (! $this->width || ! $this->height) {
            return null;
        }

        if ($this->width === $this->height) {
            return 'square';
        }

        return $this->width > $this->height ? 'landscape' : 'portrait';
    }
}

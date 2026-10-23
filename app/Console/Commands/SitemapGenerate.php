<?php

namespace App\Console\Commands;

use App\Models\Property\Property;
use App\Models\FrontPageCity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SitemapGenerate extends Command
{
    protected $signature = 'seo:sitemap-generate';
    protected $description = 'Generate XML sitemap for all public-facing pages';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Static pages (high priority)
        // Note: /search is noindex, follow with canonical to /property — excluded from sitemap
        // Trust pages (/about, /how-booking-works, etc.) are Phase 3 (unapproved) — include only if routes exist
        $staticPages = [
            ['url' => '/',                    'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/property',            'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/contact',             'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/partners/property',   'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/show-now',            'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $sitemap .= $this->buildSitemapEntry(
                config('app.url') . $page['url'],
                $page['priority'],
                $page['changefreq']
            );
        }

        // Paginated property listing pages (index, follow with self-canonical per strategy)
        try {
            $perPage = 12; // Must match FrontPropertyController@propertyList pagination
            $totalProperties = Property::where('status_id', 2)->count();
            $lastPage = (int) ceil($totalProperties / $perPage);

            for ($page = 2; $page <= $lastPage; $page++) {
                $url = url('/property', ['page' => $page]);
                $sitemap .= $this->buildSitemapEntry($url, '0.7', 'daily');
            }

            if ($lastPage > 1) {
                $added = $lastPage - 1;
                $this->info("Added {$added} paginated listing pages to sitemap");
            }
        } catch (\Exception $e) {
            $this->warn("Could not add paginated listing pages: {$e->getMessage()}");
        }

        // Location-filtered listing pages (only if ≥3 available properties — strategy guardrail)
        try {
            $locations = FrontPageCity::where('status', 1)
                ->with('state') // Load state relation to get the name
                ->get();

            foreach ($locations as $location) {
                // Use state name as the location slug (lowercase, spaces to hyphens)
                $state = $location->state;
                if (! $state || ! $state->name) {
                    continue;
                }
                $locationSlug = strtolower(trim(str_replace(' ', '-', $state->name)));
                if ($locationSlug === '') {
                    continue;
                }

                $count = Property::where('status_id', 2)
                    ->where(function ($q) use ($locationSlug) {
                        $q->where('city_name', 'LIKE', "%{$locationSlug}%")
                          ->orWhere('state_name', 'LIKE', "%{$locationSlug}%");
                    })
                    ->count();

                if ($count >= 3) {
                    $url = url("/property/location/{$locationSlug}");
                    $sitemap .= $this->buildSitemapEntry($url, '0.6', 'weekly');
                }
            }

            $this->info("Added location-filtered pages to sitemap (where ≥3 properties)");
        } catch (\Exception $e) {
            $this->warn("Could not add location pages: {$e->getMessage()}");
        }

        // Property detail pages (only indexable ones: status_id=2 + thin-content guard)
        // Strategy Task 3.3: noindex if description < 150 chars, < 2 images, or missing city_name
        // Order by `reference` (indexed PK), not `id` (unindexed UUID)
        try {
            $properties = Property::where('status_id', 2)
                ->whereNotNull('slug')
                ->whereNotNull('area_slug')
                ->where('slug', '<>', '')
                ->where('area_slug', '<>', '')
                // Thin-content guard
                ->whereRaw('CHAR_LENGTH(COALESCE(description, "")) >= 150')
                ->whereNotNull('city_name')
                ->where('city_name', '<>', '')
                ->whereHas('media', function ($q) {
                    $q->where('collection_name', 'property_images');
                }, '>=', 2)
                ->select('reference', 'slug', 'area_slug', 'updated_at')
                ->orderBy('reference') // indexed auto-increment PK — strategy requirement
                ->get();

            foreach ($properties as $property) {
                $url = route('front.stays.show', [
                    'area' => $property->area_slug,
                    'slug' => $property->slug,
                ]);

                $sitemap .= $this->buildSitemapEntry(
                    $url,
                    '0.8',
                    'weekly',
                    $property->updated_at->toAtomString()
                );
            }

            $this->info("Added {$properties->count()} property detail pages to sitemap");
        } catch (\Exception $e) {
            $this->warn("Could not add property detail pages: {$e->getMessage()}");
        }

        $sitemap .= '</urlset>';

        // Write to public/sitemap.xml
        $path = public_path('sitemap.xml');
        file_put_contents($path, $sitemap);

        $this->info("✓ Sitemap generated: {$path}");
        $this->info("Total size: " . number_format(strlen($sitemap)) . " bytes");

        return self::SUCCESS;
    }

    /**
     * Build a single sitemap entry.
     */
    private function buildSitemapEntry($url, $priority = '0.5', $changefreq = 'monthly', $lastmod = null)
    {
        $entry = '  <url>' . PHP_EOL;
        $entry .= '    <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;

        if ($lastmod) {
            $entry .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        }

        $entry .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $entry .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $entry .= '  </url>' . PHP_EOL;

        return $entry;
    }
}

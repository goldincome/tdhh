<?php

namespace App\Console\Commands;

use App\Models\Property\Property;
use Illuminate\Console\Command;

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
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/property', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/search', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/partners/property', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $sitemap .= $this->buildSitemapEntry(
                config('app.url') . $page['url'],
                $page['priority'],
                $page['changefreq']
            );
        }

        // Property detail pages (indexed by slug)
        try {
            $properties = Property::where('status_id', 2) // Only published properties
                ->whereNotNull('slug')
                ->whereNotNull('area_slug')
                ->select('id', 'slug', 'area_slug', 'updated_at')
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

            $this->info("Added {$properties->count()} property pages to sitemap");
        } catch (\Exception $e) {
            $this->warn("Could not add property pages: {$e->getMessage()}");
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

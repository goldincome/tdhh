<?php

namespace App\Console\Commands;

use App\Models\Property\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeoDiagnose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:diagnose
                            {--json : Emit the report as JSON instead of a formatted summary}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read-only diagnostic report for the tdhh.co.uk SEO remediation (safe to run on production)';

    /**
     * Minimum characters of description for a property page to be considered indexable.
     */
    private const THIN_DESCRIPTION_LENGTH = 150;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $report = $this->buildReport();

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            return self::SUCCESS;
        }

        $this->render($report);

        return self::SUCCESS;
    }

    /**
     * Build the full diagnostic report.
     *
     * @return array
     */
    private function buildReport(): array
    {
        return [
            'environment' => $this->environmentReport(),
            'inventory' => $this->inventoryReport(),
            'inventory_by_status' => $this->inventoryByStatus(),
            'media' => $this->mediaReport(),
            'areas' => $this->areasReport(),
            'slugs' => $this->slugReport(),
            'content' => $this->contentReport(),
            'assets' => $this->assetReport(),
            'nap' => $this->napReport(),
            'static_checks' => $this->staticChecks(),
            'flags' => $this->buildFlags(),
        ];
    }

    /**
     * Environment and configuration state.
     *
     * @return array
     */
    private function environmentReport(): array
    {
        return [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'app_env' => config('app.env'),
            'app_debug' => $this->bool(config('app.debug')),
            'app_url' => config('app.url'),
            'app_name' => config('app.name'),
            'config_cached' => $this->bool(app()->configurationIsCached()),
            'app_is_local' => $this->bool(app()->isLocal()),
            'debugbar_enabled' => $this->describeBool(config('debugbar.enabled'), 'null (no app config/debugbar.php - package default env("DEBUGBAR_ENABLED", null) applies)'),
            'debugbar_env' => $this->describeEnv('DEBUGBAR_ENABLED'),
            'debugbar_provider_registration' => $this->debugbarRegistration(),
            'telescope_enabled' => $this->describeBool(config('telescope.enabled'), 'NOT CONFIGURED'),
            'telescope_env' => $this->describeEnv('TELESCOPE_ENABLED'),
            'database_connection' => config('database.default'),
            'database_name' => config('database.connections.'.config('database.default').'.database'),
            'database_host' => config('database.connections.'.config('database.default').'.host'),
        ];
    }

    /**
     * Property inventory.
     *
     * @return array
     */
    private function inventoryReport(): array
    {
        if (! Schema::hasTable('properties')) {
            return ['error' => 'properties table not found'];
        }

        $total = DB::table('properties')->count();
        $available = DB::table('properties')->where('status_id', 2)->count();
        $pending = DB::table('properties')->where('status_id', 1)->count();
        $missingStatus = Schema::hasColumn('properties', 'status_id')
            ? DB::table('properties')->whereNull('status_id')->count()
            : 'n/a';

        $lastUpdated = DB::table('properties')->max('updated_at');

        return [
            'total_properties' => $total,
            'available_status_id_2' => $available,
            'pending_status_id_1' => $pending,
            'null_status_id' => $missingStatus,
            'last_property_updated_at' => $lastUpdated ?: 'never',
            'public_listing_would_render' => $available > 0 ? 'YES' : 'NO - zero property cards will render',
        ];
    }

    /**
     * Property counts grouped by status, with the status name resolved.
     *
     * @return array
     */
    private function inventoryByStatus(): array
    {
        if (! Schema::hasTable('properties')) {
            return [];
        }

        $statusNames = Schema::hasTable('property_statuses')
            ? DB::table('property_statuses')->pluck('name', 'id')
            : collect();

        $counts = DB::table('properties')
            ->select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->orderBy('status_id')
            ->get();

        $rows = [];
        foreach ($counts as $row) {
            $id = is_null($row->status_id) ? 'NULL' : (string) $row->status_id;
            $rows[] = [
                'status_id' => $id,
                'status_name' => $statusNames[$row->status_id] ?? 'UNKNOWN STATUS (not in property_statuses)',
                'count' => (int) $row->total,
            ];
        }

        if ($rows === []) {
            $rows[] = [
                'status_id' => '-',
                'status_name' => 'no properties exist',
                'count' => 0,
            ];
        }

        return $rows;
    }

    /**
     * Media library state.
     *
     * @return array
     */
    private function mediaReport(): array
    {
        if (! Schema::hasTable('media')) {
            return ['error' => 'media table not found'];
        }

        $modelType = $this->propertyMediaType();
        $base = DB::table('media');

        $total = (clone $base)->count();
        $propertyRows = $modelType ? (clone $base)->where('model_type', $modelType)->count() : 0;

        $collections = $modelType
            ? (clone $base)->where('model_type', $modelType)
                ->select('collection_name', DB::raw('count(*) as total'))
                ->groupBy('collection_name')->pluck('total', 'collection_name')->toArray()
            : [];

        $withoutImages = $this->countPropertiesWithoutMedia('property_images');
        $withoutCover = $this->countPropertiesWithoutMedia('cover_image');

        $hasWidth = Schema::hasColumn('media', 'width');
        $hasHeight = Schema::hasColumn('media', 'height');

        $missingDimensions = 0;
        if ($hasWidth && $hasHeight && $total > 0) {
            $missingDimensions = DB::table('media')
                ->where(function ($q) {
                    $q->whereNull('width')->orWhereNull('height');
                })
                ->count();
        }

        return [
            'total_media_rows' => $total,
            'property_media_rows' => $propertyRows,
            'media_model_type' => $modelType ?: 'none detected',
            'property_media_collections' => $collections === [] ? 'none' : $collections,
            'properties_without_property_images' => $withoutImages,
            'properties_without_cover_image' => $withoutCover,
            'media_has_width_column' => $this->bool($hasWidth),
            'media_has_height_column' => $this->bool($hasHeight),
            'media_rows_missing_dimensions' => $missingDimensions,
            'note' => (! $hasWidth || ! $hasHeight)
                ? 'media has no width/height columns; a migration is required before <img width height> can be emitted from data'
                : ($missingDimensions > 0
                    ? $missingDimensions.' media row(s) have no dimensions. Run "php artisan seo:backfill-media-dimensions" to populate them.'
                    : 'media has width/height columns and every row is populated'),
        ];
    }

    /**
     * Area, city and neighbourhood state.
     *
     * @return array
     */
    private function areasReport(): array
    {
        $report = [];

        if (Schema::hasTable('front_page_cities')) {
            $report['front_page_cities_total'] = DB::table('front_page_cities')->count();
            $report['front_page_cities_active'] = DB::table('front_page_cities')->where('status', 1)->count();
            $report['front_page_cities_has_priority_order'] = $this->bool(Schema::hasColumn('front_page_cities', 'priority_order'));

            if (Schema::hasTable('states')) {
                $orphans = DB::table('front_page_cities')
                    ->whereNotIn('state_id', DB::table('states')->select('id'))
                    ->pluck('state_id')->unique()->values()->all();
                $report['front_page_cities_orphan_state_ids'] = $orphans === [] ? 'none' : $orphans;
            }
        } else {
            $report['front_page_cities'] = 'table not found';
        }

        if (Schema::hasTable('properties')) {
            $report['properties_missing_city_name'] = Schema::hasColumn('properties', 'city_name')
                ? DB::table('properties')->whereNull('city_name')->orWhere('city_name', '')->count()
                : 'n/a';
            $report['properties_missing_state_name'] = Schema::hasColumn('properties', 'state_name')
                ? DB::table('properties')->whereNull('state_name')->orWhere('state_name', '')->count()
                : 'n/a';
            $report['properties_missing_postal_code'] = Schema::hasColumn('properties', 'postal_code')
                ? DB::table('properties')->whereNull('postal_code')->orWhere('postal_code', '')->count()
                : 'n/a';

            if (Schema::hasColumn('properties', 'city_name')) {
                $cities = DB::table('properties')
                    ->whereNotNull('city_name')->where('city_name', '<>', '')
                    ->select('city_name', DB::raw('count(*) as total'))
                    ->groupBy('city_name')->orderByDesc('total')->limit(25)
                    ->get()->map(fn ($r) => $r->city_name.' ('.$r->total.')')->all();
                $report['top_city_names'] = $cities === [] ? 'none' : $cities;
            }
        }

        return $report;
    }

    /**
     * Slug column state.
     *
     * @return array
     */
    private function slugReport(): array
    {
        if (! Schema::hasTable('properties')) {
            return ['error' => 'properties table not found'];
        }

        $hasSlug = Schema::hasColumn('properties', 'slug');
        $hasArea = Schema::hasColumn('properties', 'area_slug');

        $report = [
            'slug_column_exists' => $this->bool($hasSlug),
            'area_slug_column_exists' => $this->bool($hasArea),
        ];

        if ($hasSlug) {
            $report['rows_with_empty_slug'] = DB::table('properties')->whereNull('slug')->orWhere('slug', '')->count();
            $report['duplicate_slugs'] = DB::table('properties')
                ->whereNotNull('slug')->where('slug', '<>', '')
                ->select('slug', DB::raw('count(*) as total'))
                ->groupBy('slug')->havingRaw('count(*) > 1')->count();
        }

        if ($hasArea) {
            $report['rows_with_empty_area_slug'] = DB::table('properties')->whereNull('area_slug')->orWhere('area_slug', '')->count();
            $report['area_slug_distribution'] = DB::table('properties')
                ->whereNotNull('area_slug')->where('area_slug', '<>', '')
                ->select('area_slug', DB::raw('count(*) as total'))
                ->groupBy('area_slug')->orderByDesc('total')->limit(25)
                ->get()->map(fn ($r) => $r->area_slug.' ('.$r->total.')')->all() ?: 'none';
        }

        if (! $hasSlug && ! $hasArea) {
            $report['note'] = 'Phase 2 Task 2.4a has not been applied; run seo:backfill-slugs after the migration';
        }

        return $report;
    }

    /**
     * Listing content quality.
     *
     * @return array
     */
    private function contentReport(): array
    {
        if (! Schema::hasTable('properties')) {
            return ['error' => 'properties table not found'];
        }

        $report = [
            'missing_name' => Schema::hasColumn('properties', 'name')
                ? DB::table('properties')->whereNull('name')->orWhere('name', '')->count() : 'n/a',
            'missing_price' => Schema::hasColumn('properties', 'price')
                ? DB::table('properties')->whereNull('price')->count() : 'n/a',
            'missing_bedroom_qty' => Schema::hasColumn('properties', 'bedroom_qty')
                ? DB::table('properties')->whereNull('bedroom_qty')->count() : 'n/a',
            'missing_max_guest' => Schema::hasColumn('properties', 'max_guest')
                ? DB::table('properties')->whereNull('max_guest')->count() : 'n/a',
        ];

        if (Schema::hasColumn('properties', 'description')) {
            $thin = DB::table('properties')
                ->whereRaw('CHAR_LENGTH(COALESCE(description, "")) < ?', [self::THIN_DESCRIPTION_LENGTH])
                ->count();
            $report['thin_description_under_'.self::THIN_DESCRIPTION_LENGTH.'_chars'] = $thin;
            $report['description_min_length'] = DB::table('properties')->min(DB::raw('CHAR_LENGTH(COALESCE(description, ""))')) ?? 0;
            $report['description_max_length'] = DB::table('properties')->max(DB::raw('CHAR_LENGTH(COALESCE(description, ""))')) ?? 0;
        }

        if (Schema::hasTable('reviews')) {
            $report['total_reviews'] = DB::table('reviews')->count();
            $report['reviews_by_status'] = DB::table('reviews')
                ->select('status', DB::raw('count(*) as total'))->groupBy('status')
                ->get()->map(fn ($r) => ($r->status ?? 'NULL').' ('.$r->total.')')->all() ?: 'none';
        }

        return $report;
    }

    /**
     * Public asset state.
     *
     * @return array
     */
    private function assetReport(): array
    {
        $report = [];

        $ogCandidates = [
            'public/img/bgs/home-bg.jpg',
            'public/img/bgs/contact-us.jpeg',
        ];

        foreach ($ogCandidates as $candidate) {
            $path = public_path(str_replace('public/', '', $candidate));
            if (file_exists($path)) {
                $size = @getimagesize($path);
                $report[$candidate] = $size
                    ? $size[0].'x'.$size[1].' ('.($size[0] < 1200 || $size[1] < 630 ? 'BELOW 1200x630 OG MINIMUM' : 'meets OG minimum').')'
                    : 'present (dimensions unreadable)';
            } else {
                $report[$candidate] = 'missing';
            }
        }

        $report['public_img_dir_exists'] = $this->bool(is_dir(public_path('img')));
        $report['public_images_dir_exists'] = $this->bool(is_dir(public_path('images')));
        $report['og_asset_1200x630'] = 'MANUAL DELIVERABLE - no compliant Open Graph image exists';

        foreach ($this->scanLocalAssetReferences() as $path => $note) {
            $report[$path] = $note;
        }

        $report['vite_manifest_present'] = $this->bool(file_exists(public_path('build/manifest.json')));
        $report['build_css_present'] = $this->bool(count(glob(public_path('build/assets/*.css')) ?: []) > 0);
        $report['build_js_present'] = $this->bool(count(glob(public_path('build/assets/*.js')) ?: []) > 0);

        $zeroByte = [];
        foreach ($this->nestedPublicJsFiles() as $file) {
            $zeroByte[] = $file;
        }
        $report['public_js_files'] = $zeroByte === [] ? 'none' : $zeroByte;

        return $report;
    }

    /**
     * Scan Blade views for local asset references and report whether each target exists.
     *
     * Only static-file references are considered; routes, remote URLs and Vite
     * bundle output are skipped.
     *
     * @return array
     */
    private function scanLocalAssetReferences(): array
    {
        $viewsPath = resource_path('views');

        if (! is_dir($viewsPath)) {
            return [];
        }

        $extensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'avif', 'ico', 'bmp', 'woff', 'woff2', 'ttf', 'eot', 'otf', 'svgz', 'mp4', 'webm', 'pdf'];

        $references = [];

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($viewsPath, \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            $contents = (string) file_get_contents($file->getPathname());
            $contents = $this->stripBladeComments($contents);
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($viewsPath) + 1));

            preg_match_all('/(?:asset|url)\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $contents, $matches);

            foreach ($matches[1] as $match) {
                $match = trim($match);

                if ($match === '' || preg_match('#^(https?:)?//#', $match) || str_starts_with($match, 'data:')) {
                    continue;
                }

                if (str_starts_with($match, 'build/')) {
                    continue;
                }

                $path = '/'.ltrim($match, '/');
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                if (! in_array($extension, $extensions, true)) {
                    continue;
                }

                $references[$path][] = $relative;
            }
        }

        $report = [];

        foreach ($references as $path => $sources) {
            $sources = array_values(array_unique($sources));
            $note = 'referenced by '.implode(', ', array_slice($sources, 0, 3));

            if (count($sources) > 3) {
                $note .= ' (+'.(count($sources) - 3).' more)';
            }

            $report[$path] = file_exists(public_path(ltrim($path, '/')))
                ? 'present - '.$note
                : 'MISSING (404) - '.$note;
        }

        ksort($report);

        return $report;
    }

    /**
     * Remove Blade comment blocks so commented-out markup is not scanned.
     *
     * @param  string  $contents
     * @return string
     */
    private function stripBladeComments(string $contents): string
    {
        $contents = preg_replace('/\{\{--.*?--\}\}/s', '', $contents);
        $contents = preg_replace('/\{\{\/\*.*?\*\/\}\}/s', '', $contents);

        return (string) $contents;
    }

    /**
     * List nested public/js files, flagging zero-byte ones.
     *
     * @return array
     */
    private function nestedPublicJsFiles(): array
    {
        $results = [];
        $root = public_path('js');

        if (! is_dir($root)) {
            return $results;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'js') {
                continue;
            }

            $relative = str_replace(public_path(), '', $file->getPathname());
            $size = $file->getSize();
            $results[] = $relative.($size === 0 ? ' (0 bytes - EMPTY)' : ' ('.$size.' bytes)');
        }

        return $results;
    }

    /**
     * NAP (name, address, phone) configuration state.
     *
     * @return array
     */
    private function napReport(): array
    {
        $report = [
            'config_app_name' => config('app.name'),
            'config_admin_email' => config('app.admin_email'),
        ];

        if (Schema::hasTable('general_settings')) {
            $settings = DB::table('general_settings')->orderBy('id')->get()
                ->map(fn ($r) => $r->name.' = '.$r->value)->all();
            $report['general_settings_rows'] = $settings === [] ? 'none' : $settings;
        } else {
            $report['general_settings_rows'] = 'table not found';
        }

        $report['expected_nap'] = [
            'legal_name' => 'T D HOLIDAY HOMES LTD',
            'company_number' => '11247086',
            'registered_office' => '53 Tideslea Path, London, England, SE28 0LY',
            'operating_address' => 'Unit 6, Block 3 Woolwich Dockyard Industrial Estate, Woolwich Church Street, Charlton, London, SE18 5PQ',
            'phone' => '+44 (0) 203 247 4747',
            'email' => 'info@tdhh.co.uk',
        ];
        $report['note'] = 'NAP values are not yet stored in general_settings; the footer still hardcodes them';

        return $report;
    }

    /**
     * Static scans of the Blade/PHP source for the known-bad strings.
     *
     * @return array
     */
    private function staticChecks(): array
    {
        $checks = [
            'resources/views/property-list.blade.php' => [
                'Showing 1 - 12 items out of 116' => 'hardcoded listing count',
                'price_range_style' => 'reference to a file that does not exist',
            ],
            'resources/views/common/footer.blade.php' => [
                '+00000000' => 'placeholder phone number',
                'support@tdholidayhomes.com' => 'wrong email domain',
                'registered trademarks' => 'unverified trademark claim',
            ],
            'resources/views/home.blade.php' => [
                'Have an vacant' => 'grammar error',
                'spend time along' => 'copy error',
            ],
            'resources/views/contact/form.blade.php' => [
                'alt="vho"' => 'stray old-brand alt text that Google indexes as body text',
            ],
            'resources/views/common/admin-header.blade.php' => [
                'alt="VHO logo"' => 'old brand alt text',
            ],
            'resources/views/common/partner-header.blade.php' => [
                'alt="VHO logo"' => 'old brand alt text',
            ],
            'app/Http/Controllers/Front/FrontPropertyController.php' => [
                'inRandomOrder' => 'randomised internal linking',
            ],
            'resources/views/layouts/app.blade.php' => [
                "asset('css/app.css')" => 'dead stylesheet reference',
                "'/css/toastr.css'" => 'dead stylesheet reference',
                "'/js/toastr.js'" => 'dead script reference',
            ],
            'app/Providers/AppServiceProvider.php' => [
                'isLocal()' => 'DebugBar registration is still gated on app()->isLocal(). It must be gated on config(\'debugbar.enabled\') so that a production server running APP_ENV=local cannot activate it.',
            ],
            'app/Providers/TelescopeServiceProvider.php' => [
            ],
            'config/telescope.php' => [
                "TELESCOPE_ENABLED', true" => 'fails open - Telescope defaults to enabled. It must default to false.',
            ],
        ];

        $results = [];
        foreach ($checks as $file => $needles) {
            $path = base_path($file);
            if (! file_exists($path)) {
                $results[$file] = 'file not found';
                continue;
            }

            $contents = file_get_contents($path);
            $found = [];
            foreach ($needles as $needle => $label) {
                if (str_contains($contents, $needle)) {
                    $found[] = $label.' ('.$needle.')';
                }
            }
            $results[$file] = $found === [] ? 'clean' : $found;
        }

        return array_merge($results, $this->staticGuards());
    }

    /**
     * Static checks for the hardening rules that must be PRESENT.
     *
     * These are the failure modes discovered while remediating the P0
     * telemetry exposure. Each one is a silent re-introduction of a public
     * debug surface, so they are asserted rather than merely reported.
     *
     * @return array
     */
    private function staticGuards(): array
    {
        $guards = [];

        $appConfig = base_path('config/app.php');
        $providersActive = false;

        if (file_exists($appConfig)) {
            foreach (preg_split('/\R/', (string) file_get_contents($appConfig)) as $line) {
                if (preg_match('/^\s*Barryvdh\\\\Debugbar\\\\ServiceProvider::class/', $line)) {
                    $providersActive = true;
                    break;
                }
            }
        }

        $guards['config/app.php (DebugBar provider)'] = $providersActive
            ? 'FAIL: Barryvdh\Debugbar\ServiceProvider is listed in the providers array. That bypasses the config(\'debugbar.enabled\') gate in AppServiceProvider and activates DebugBar on every request. Comment it out.'
            : 'clean';

        $telescopeProvider = base_path('app/Providers/TelescopeServiceProvider.php');
        if (! file_exists($telescopeProvider)) {
            $guards['app/Providers/TelescopeServiceProvider.php (auth override)'] = 'file not found';

            return $guards;
        }

        $contents = (string) file_get_contents($telescopeProvider);
        $hasOverride = str_contains($contents, 'protected function authorization()');
        $hasAllowList = str_contains($contents, 'TELESCOPE_ALLOWED_EMAILS');

        $problems = [];
        if (! $hasOverride) {
            $problems[] = 'FAIL: authorization() is not overridden. The parent class grants Telescope access to anyone when app()->environment(\'local\'), which is how the dashboard was publicly readable.';
        }
        if (! $hasAllowList) {
            $problems[] = 'FAIL: no TELESCOPE_ALLOWED_EMAILS allow-list. Telescope must require an explicitly authorised account.';
        }

        // A real statement, not a comment. The parent implementation reads
        // "return app()->environment('local') || Gate::check(...)".
        if (preg_match('/^\s*return\s+app\(\)->environment\(\'local\'\)/m', $contents)) {
            $problems[] = 'FAIL: the permissive Telescope authorisation callback is still present. Telescope::auth() must never grant access based on the environment.';
        }

        $guards['app/Providers/TelescopeServiceProvider.php (auth override)'] = $problems === [] ? 'clean' : $problems;

        return $guards;
    }

    /**
     * Actionable warnings derived from the report.
     *
     * @return array
     */
    private function buildFlags(): array
    {
        $flags = [];

        if (config('app.debug')) {
            $flags[] = 'CRITICAL: APP_DEBUG is true. Disable it in production.';
        }

        if (app()->isLocal()) {
            $flags[] = 'CRITICAL: APP_ENV is "local". The production server must not run as "local": it changes framework behaviour (Telescope authorisation, debug helpers, error rendering) beyond the DebugBar gate that is now in place.';
        }

        if (config('debugbar.enabled') === true) {
            $flags[] = 'CRITICAL: DebugBar is enabled. Disable it in production.';
        }

        if (config('telescope.enabled') === true) {
            $flags[] = 'CRITICAL: Telescope is enabled. Disable it in production.';
        }

        if (file_exists(base_path('bootstrap/cache/routes-v7.php'))
            || file_exists(base_path('bootstrap/cache/routes.php'))) {
            $flags[] = 'CRITICAL: a cached route file exists in bootstrap/cache. Cached routes are served instead of the live registrations, so the /telescope and /_debugbar routes stay live even when those packages are disabled. Run "php artisan route:clear" then "php artisan route:cache" after deploying.';
        }

        if (Schema::hasTable('properties') && DB::table('properties')->where('status_id', 2)->count() === 0) {
            $flags[] = 'CRITICAL: zero properties have status_id = 2 (Available). The public listing renders no cards. Do NOT publish status_id = 1 (Pending) without owner approval.';
        }

        if (Schema::hasTable('properties')) {
            $total = DB::table('properties')->count();
            $pending = DB::table('properties')->where('status_id', 1)->count();

            if ($total > 0 && $pending > 0) {
                $flags[] = 'NOTICE: '.$pending.' of '.$total.' properties are Pending. This is the likely source of the "116 listings" claim.';
            }

            if ($total === 0) {
                $flags[] = 'NOTICE: the properties table is completely empty.';
            }
        }

        if (! Schema::hasColumn('properties', 'slug')) {
            $flags[] = 'NOTICE: the slug column does not exist yet. Apply Phase 2 Task 2.4a before generating a sitemap or publishing /stays URLs.';
        }

        if (Schema::hasTable('media') && ! Schema::hasColumn('media', 'width')) {
            $flags[] = 'NOTICE: the media table has no width/height columns. Image dimensions in the markup are currently hard-coded constants.';
        }

        if (config('app.name') === 'Laravel') {
            $flags[] = 'NOTICE: config(app.name) is still "Laravel". Set APP_NAME="TD Holiday Homes".';
        }

        $flags[] = 'REMINDER: the production database is not reachable from the development environment. Run this command on the production host and paste the output back.';

        return $flags;
    }

    /**
     * Render the report as a readable summary.
     *
     * @param  array  $report
     * @return void
     */
    private function render(array $report): void
    {
        $this->line('');
        $this->line('<options=bold;fg=cyan>TD Holiday Homes - SEO Diagnostic</>');
        $this->line('<fg=gray>Read-only. Generated '.now()->toDateTimeString().'</>');

        foreach ($report as $section => $data) {
            if ($data === [] || $data === null) {
                continue;
            }

            $this->line('');
            $this->line('<options=bold>'.$this->sectionTitle($section).'</>');

            if ($section === 'inventory_by_status') {
                $this->table(['status_id', 'status_name', 'count'], $data);
                continue;
            }

            if ($section === 'flags') {
                foreach ($data as $flag) {
                    $this->line('  <fg=yellow>-</> '.$flag);
                }
                continue;
            }

            if ($section === 'static_checks') {
                foreach ($data as $file => $result) {
                    if (is_string($result)) {
                        $this->line('  <fg=gray>'.$file.':</> '.$result);
                        continue;
                    }
                    if ($result === ['clean']) {
                        $this->line('  <fg=green>OK</> '.$file);
                        continue;
                    }
                    $this->line('  <fg=red>FAIL</> '.$file);
                    foreach ($result as $issue) {
                        $this->line('       <fg=red>-</> '.$issue);
                    }
                }
                continue;
            }

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }
                $this->line('  <fg=gray>'.str_pad($key, 38).'</> '.$value);
            }
        }

        $this->line('');
        $this->line('<fg=gray>No data was modified. This command is safe to run on production.</>');
    }

    /**
     * Human friendly section heading.
     *
     * @param  string  $section
     * @return string
     */
    private function sectionTitle(string $section): string
    {
        return strtoupper(str_replace('_', ' ', $section));
    }

    /**
     * Resolve the media model_type used for Property records.
     *
     * @return string|null
     */
    private function propertyMediaType(): ?string
    {
        $types = DB::table('media')->distinct()->pluck('model_type');

        foreach ($types as $type) {
            if ($type === Property::class) {
                return $type;
            }
        }

        foreach ($types as $type) {
            if (str_ends_with($type, '\\Property') || $type === 'Property') {
                return $type;
            }
        }

        return null;
    }

    /**
     * Count properties that have no media in the given collection.
     *
     * @param  string  $collection
     * @return int
     */
    private function countPropertiesWithoutMedia(string $collection): int
    {
        $modelType = $this->propertyMediaType();

        if (! $modelType) {
            return 0;
        }

        return DB::table('properties')
            ->whereNotExists(function ($query) use ($modelType, $collection) {
                $query->select(DB::raw(1))
                    ->from('media')
                    ->whereColumn('media.model_id', 'properties.id')
                    ->where('media.model_type', $modelType)
                    ->where('media.collection_name', $collection);
            })
            ->count();
    }

    /**
     * Describe how the DebugBar service provider is registered.
     *
     * @return string
     */
    private function debugbarRegistration(): string
    {
        $appConfig = base_path('config/app.php');

        if (file_exists($appConfig)) {
            foreach (preg_split('/\R/', (string) file_get_contents($appConfig)) as $line) {
                if (preg_match('/^\s*Barryvdh\\\\Debugbar\\\\ServiceProvider::class/', $line)) {
                    return 'listed in config/app.php providers array - BYPASSES the config(\'debugbar.enabled\') gate and is ALWAYS ACTIVE';
                }
            }
        }

        $path = base_path('app/Providers/AppServiceProvider.php');

        if (! file_exists($path)) {
            return 'unknown';
        }

        $contents = file_get_contents($path);

        if (! str_contains($contents, 'Debugbar')) {
            return 'not registered';
        }

        if (str_contains($contents, "config('debugbar.enabled')")) {
            return 'gated on config(\'debugbar.enabled\') - active only when that flag is true';
        }

        if (str_contains($contents, 'isLocal()')) {
            return 'gated on app()->isLocal() - ACTIVE when APP_ENV=local';
        }

        return 'registered unconditionally - ALWAYS ACTIVE';
    }

    /**
     * Format a boolean for display.
     *
     * @param  mixed  $value
     * @return string
     */
    private function bool($value): string
    {
        return $value ? 'YES' : 'NO';
    }

    /**
     * Format a possibly-missing boolean for display.
     *
     * @param  mixed  $value
     * @param  string  $fallback
     * @return string
     */
    private function describeBool($value, string $fallback): string
    {
        if ($value === null) {
            return $fallback;
        }

        return $this->bool($value);
    }

    /**
     * Read a raw environment flag.
     *
     * @param  string  $key
     * @return string
     */
    private function describeEnv(string $key): string
    {
        $value = env($key);

        if ($value === null) {
            return 'not set in .env';
        }

        return $this->bool((bool) $value);
    }
}

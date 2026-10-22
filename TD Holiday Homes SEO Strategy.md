# TD Holiday Homes (`tdhh.co.uk`) — Consolidated SEO Audit & Implementation Spec

**Target Application:** `https://tdhh.co.uk` (TD Holiday Homes — Short-Stay & Holiday-Let Marketplace)
**Legal Entity:** T D HOLIDAY HOMES LTD — Company No. **11247086** (incorporated 10 March 2018, Active)
**Secondary Domain to Consolidate:** `https://tdholidayhomes.com` → 301 to canonical
**Audit & Spec Date:** 20 October 2026 (revised same day after full codebase + live-production verification)
**Target Executor:** OpenCode (autonomous coding agent) & Lead Laravel Engineer
**Execution Scope (approved):** Phase 0 + Phase 1 + Phase 2 (see §12 Roadmap). Phases 3–5 remain specified but unapproved.

---

## 0\. Executive Summary & Locked Decisions

### 0.1 What is actually wrong

The site is not suffering from "weak content". It is suffering from **an instrumented production environment leaking into the DOM**, **a listing query that returns zero rows**, **hardcoded copy pretending to be data**, and **no metadata layer at all**. Content work is worthless until those are fixed.

The single most damaging issue is that the production site renders **PHP DebugBar HTML and live SQL queries** into every page, including the SQL `select count(*) as aggregate from properties where status_id = 2`. Googlebot is indexing it.

### 0.2 The "116 listings" is not a fact

The string `"Showing 1 - 12 items out of 116"` is **hardcoded in Blade**. The database query returns **0**. There is no verified evidence that 116 listings exist anywhere. Until `seo:diagnose` runs against the production database, **treat inventory as unknown, and treat 116 as fiction**. Any strategy, sitemap, area hub, or collection page built on an assumed 116 listings is built on sand.

### 0.3 Locked decisions (owner-approved — do not re-litigate)

| # | Decision | Rationale |
| :-- | :---- | :---- |
| D1 | **Scope = Phase 0 + Phase 1 + Phase 2.** Phases 3–5 specified but not built. | Fixes + SEO foundation only. Content rewrites need working inventory. |
| D2 | **Property URLs = add `slug` + `area_slug` columns, backfill, and 301 legacy URLs.** | Keyword-bearing URLs. `slug` is generated, `area_slug` is an area taxonomy. |
| D3 | **Repo-only changes + deployment runbook.** The agent does not touch production `.env`, nginx, DNS, or the production database. | Safe deployment. Operator runs documented commands. |
| D4 | **Diagnose the empty listing before exposing pending inventory.** | `status_id = 1` (Pending) must not be published. Approval required. |
| D5 | **No commission percentage published on `/partners/property`.** | `partner_commission_percent = 5` is seeder-only dead config read by zero lines of code; the direction of the percentage is ambiguous. The page sells "no upfront listing fee" instead. |
| D6 | **`/partners/property` remains the public host-landing URL.** The authenticated dashboard moves to `/partners/dashboard/property`. | Header, footer, and partner sidebar already point at `/partners/property`. |
| D7 | **Legacy `/property/{id}` becomes a 301** to `/stays/{area}/{slug}`, preserving the full query string. | 14 Blade call sites keep working through one redirect hop; no N+1 model lookups. |
| D8 | **Footer shows the operating address; the legal line carries the registered office.** | Satisfies the Companies Act website disclosure requirement *and* keeps the customer-facing NAP stable. |
| D9 | **Dead asset tags are deleted, not resurrected.** The price-range input sync is inlined. | `app.css` is already bundled by Vite. A parallel `public/css` pipeline is not worth maintaining. |
| D10 | **`status_id` for published inventory is decided by the Phase 0 diagnostic, not by assumption.** | The status taxonomy is now known, but the production distribution is not. |

### 0.4 The NAP block (single source of truth — use verbatim everywhere)

```text
Legal name:      T D HOLIDAY HOMES LTD
Company number:  11247086
Brand:           TD Holiday Homes
Registered office: 53 Tideslea Path, London, England, SE28 0LY
Operating address: Unit 6, Block 3 Woolwich Dockyard Industrial Estate,
                    Woolwich Church Street, Charlton, London, SE18 5PQ, United Kingdom
Phone:           +44 (0) 203 247 4747   (tel:+442032474747)
Email:           info@tdhh.co.uk          (mailto:info@tdhh.co.uk)
SIC codes:       55209 (other holiday and other collective accommodation), 55900 (other accommodation)
Domain:          tdhh.co.uk
```

**Legal-entity naming rule:** the Companies House legal name is **`T D HOLIDAY HOMES LTD`** (spaced initials). The brand is **`TD Holiday Homes`**. Do not write "TD Holiday Homes Ltd" in schema or copy — it does not match the register and is a trust/consistency defect.

---

## 1\. Verified Codebase Context

Everything in this section was confirmed by reading the source, querying the local database, and inspecting live production responses. **No assumption from the original spec survived unless it is restated here.**

### 1.1 Runtime

* **Framework:** Laravel Framework `v9.52.21`, PHP `v8.3.30`
* **Web server (production):** **nginx** — *not* Apache. Any `.htaccess` cache or redirect rule is inert.
* **Local environment:** `APP_URL=https://tdhh.test`, `config('app.name')` = **`Laravel`** (must become `TD Holiday Homes`)
* **Local database:** `tdhh` — **0 properties**. The local database cannot be used to validate listing behaviour; only schema and command logic can be validated locally.

### 1.2 Verified files

| Purpose | Path | Notes |
| :---- | :---- | :---- |
| Master layout | `resources/views/layouts/app.blade.php` | Owns `<head>`, title, Open Graph, JSON-LD stack |
| Error/messages layout | `resources/views/layouts/errors-and-messages.blade.php` | |
| Homepage | `resources/views/home.blade.php` | |
| Listing page | `resources/views/property-list.blade.php` | |
| Property detail | `resources/views/property-details.blade.php` | **Reused as the `stays.show` template** |
| Property reviews | `resources/views/property-reviews.blade.php` | **Static mockup — publicly routable** |
| Header / Footer | `resources/views/common/header.blade.php`, `common/footer.blade.php` | |
| Property grid card | `resources/views/common/home-grid.blade.php` | |
| Filter / Pagination | `resources/views/common/filter.blade.php`, `common/pagination.blade.php` | |
| Contact | `resources/views/contact/form.blade.php` | |
| Controllers | `app/Http/Controllers/Front/FrontPropertyController.php`, `.../ContactUsController.php` | |
| Models | `app/Models/Property/Property.php`, `Property/PropertyStatus.php`, `app/Models/FrontPageCity.php`, `app/Models/Partner.php`, `app/Models/PartnerCommission.php`, `app/Models/GeneralSetting.php` | |

**Directories that do NOT exist (must be created):**
`resources/views/components/` (for `<x-seo-meta>` / `<x-json-ld>`),
`resources/views/property/` (the original spec assumed `property/show.blade.php`).
`resources/views/partners/` **does** exist.

### 1.3 Verified `properties` schema (40 columns)

```text
id (char36, UUID, UNINDEXED)   reference (auto-increment, PRIMARY KEY)   name
description   neighborhood   price   price_meta   address   bedroom_qty
bathroom_qty  country_id   min_bookable_night   state_id   postal_code
listed_by   map_address   status_id   cleaning_fee   check_in_from
check_in_to   check_out_from   check_out_to   parking_is_available
parking_options   room_type_id   property_type   bed_types   room_quantity
max_guest   room_size   bathroom_type   living_room_qty   refund_policy
sofa_availability   sofa_qty   created_at   updated_at   country_name
state_name   city_name   city_id
```

**Corrections to the original spec — the following columns DO NOT EXIST and must not be referenced:**

| Original spec assumed | Reality | Use instead |
| :---- | :---- | :---- |
| `slug` | Does not exist | `name` + `reference` (to be added as `slug` in Phase 2) |
| `area_slug` | Does not exist | `city_name` / `state_name` (to be added as `area_slug` in Phase 2) |
| `nightly_rate` | Does not exist | `price` |
| `bedrooms` | Does not exist | `bedroom_qty` |
| `max_guests` | Does not exist | `max_guest` |
| `postcode_outward` | Does not exist | `postal_code` (outward code = `substr` before the space) |
| `title` | Does not exist | `name` |
| `area` | Does not exist | `city_name` |
| `images` relation | Does not exist | `$property->propertyImages` (Spatie `HasMedia`) |
| `cover_image` relation | Does not exist | `$property->cover_image` (Spatie `HasMedia`) |
| `verified_reviews_count` accessor | Does not exist | `reviews` relation filtered by `reviews.status`; must be added as an accessor |
| `$characteristic->name` | Does not exist | `$characteristic->characteristic->name` |

**Also note:** `id` is a `char(36)` UUID and is **unindexed**; `status_id` is **unindexed**. The only index is `PRIMARY KEY (reference)`. A `status_id` index is a prerequisite for any Phase 2 listing-performance work.

**`states` table is a generic country/region list** (`Andaman and Nicobar Islands`, `Andhra Pradesh`, …). It is *not* a list of UK areas. `front_page_cities.state_id` points into this generic list, and `properties.state_name` / `properties.city_name` are free-text denormalised strings. **Area hubs in Phase 4 must therefore be driven by real-text area slugs on properties, not by the `states` table.**

### 1.4 Verified `property_statuses` taxonomy

| id | name |
| :-- | :---- |
| 1 | Pending |
| 2 | **Available** |
| 3 | Suspended By Partner |
| 4 | Suspended By Admin |
| 6 | Disabled |

(id 5 was deleted.) `PropertyStatus::AVAILABLE = 2`, so the production filter `where('status_id', 2)` is **semantically correct** — the empty listing is a data-state problem, not a filter bug. `status_id = 1` (Pending) must never be published without owner approval (D4).

### 1.5 Other verified tables

* **`front_page_cities`:** `id, state_id, status, image, created_at, updated_at` — **no `priority_order` column**. The original spec's `orderBy('priority_order')` is invalid.
* **`media`** (Spatie): `id, model_type, uuid, model_id, collection_name, name, file_name, mime_type, conversions_disk, disk, size, manipulations, custom_properties, responsive_images, order_column, generated_conversions, timestamps` — **no `width` / `height` columns**. A migration is required before `<img width height>` can be emitted.
* **`general_settings`:** `id, name, value, description, timestamps` — currently contains exactly **one** row: `partner_commission_percent = 5` (description *"Percent Commission Percent"*). **No code reads this setting.** It is dead configuration. NAP values must be added as new rows.
* **`characteristics`** (9 rows): Swimming pool, Gas cooker, Washing Machine, Free Wifi, Refrigerator, Iron, Heater, Microwave, Free breakfast.
* **`house_rules`** (4 rows): Smoking not allowed, Children not allowed, Party not allowed, Pets not allowed.
* **`reviews`:** has a `status` column. No `verified_reviews_count` accessor exists.
* **`partner_commissions`:** `partner_id, reservation_id, commission_amt, commission_percentage, commission_status_id, ref_no, payout_amt` — **none of these are ever written by application code.**

### 1.6 Verified routing facts

* `route('front.details', …)` is referenced in **14 Blade locations** (account, checkout, admin, partner, reviews). Keeping the route name alive is materially cheaper than rewriting 14 call sites (D7).
* **Route shadowing:** `routes/web.php:192-194` registers a closure at `GET /partners/property`; `routes/partners/route.php` registers the same path later. Laravel honours the **last** registration, so the `web.php` closure is **dead code** and the authenticated partner route wins. The fix is to **rename the authenticated route**, not merely delete the closure.
* `config('app.admin_email')` = `info@tdhh.co.uk` ✓ (matches the intended NAP).

### 1.7 Verified live-production facts (checked 20 Oct 2026)

| Check | Result |
| :---- | :---- |
| DebugBar | **Leaking.** DebugBar HTML and live SQL in every page body. |
| `/property` counts | `select count(*) as aggregate from properties where status_id = 2` → **0** |
| Homepage property query | `from properties where status_id = 2` → **0** |
| `/property` headline | `Showing 1 - 12 items out of 116` (hardcoded) beside `Showing 1 - 0 items out of 0` (dynamic) |
| Homepage areas | `from front_page_cities where status = 1 order by RAND() limit 8` |
| Meta | **Zero** `<meta name="description">`, **zero** `<link rel="canonical">`, **zero** `<h1>` on `/`, `/property`, `/search`, `/contact`, `/partners/property` |
| `/partners/property` | Resolves to the partner **login** page |
| Cache headers | `cache-control: private, must-revalidate` on all public pages |
| `robots.txt` | `User-agent: *` / `Disallow:` only — **no sitemap reference** |
| `sitemap.xml` | Not generated |
| `tdholidayhomes.com` | **Does not resolve in DNS** — the "secondary domain" needs DNS created before a redirect can work |
| `property-reviews.blade.php` | Publicly routable, contains **fake** property name and **fake** reviews |

### 1.8 Verified dead asset references (all 404 on production)

| Requested | Referenced from | Reality |
| :---- | :---- | :---- |
| `/css/app.css` | `layouts/app.blade.php` | `resources/css/app.css` is **already bundled by Vite** via `resources/js/app.js:2` |
| `/css/toastr.css` | `layouts/app.blade.php` | `resources/css/toastr.css` exists in source, not in `public/` |
| `/js/toastr.js` | `layouts/app.blade.php` | `resources/js/toastr.js` exists in source, not in `public/` |
| `/css/price_range_style.css` | `property-list.blade.php` | **Does not exist anywhere in the repository** |
| `/js/price_range_style.js` | `property-list.blade.php` | **Does not exist anywhere in the repository** — the price-range min/max input sync is non-functional |

Also: `public/js/index.js` and `public/js/fullcalendar@6.1.4.js` are **0 bytes**.

Two render-blocking 404 `<link>` tags sit in `<head>` on every page, and the price filter's number inputs never sync with the jQuery UI slider (jQuery UI *is* loaded from CDN, so the slider itself renders).

---

## 2\. Findings Matrix (corrected, 100% verified)

| ID | Category | Finding & Root Cause | Target Files | Impact |
| :---- | :---- | :---- | :---- | :---- |
| **P0-1** | Security & index bloating | `APP_DEBUG=true` and DebugBar/Telescope active in production; **raw SQL, stack traces and CSRF tokens render into every page body** and are indexed by Google. | `config/debugbar.php` (new), `app/Providers/AppServiceProvider.php`, `app/Providers/TelescopeServiceProvider.php`, `config/telescope.php`, `.env.example` | Destroys keyword relevance across the DOM, exposes internal paths, leaks CSRF tokens. |
| **P0-2** | Empty listings + fabricated count | `property-list.blade.php` hardcodes `"Showing 1 - 12 items out of 116"` while the real query returns **0**. `property-list.blade.php` and `common/pagination.blade.php` both render `Showing 1 - 0 items out of 0` and produce zero property cards. The 116 is fiction. | `FrontPropertyController.php`, `property-list.blade.php`, `common/pagination.blade.php` | Soft-404. Zero crawlable property links. Google concludes the site has no inventory. |
| **P0-3** | Randomised internal linking | `FrontPropertyController.php:45` → `select * from front_page_cities where status = 1 order by RAND() limit 8`, re-randomised on every request. | `FrontPropertyController.php` | Destroys stable internal PageRank flow to destination pages; wastes crawl budget re-discovering links. |
| **P0-4** | Missing meta & duplicate OG | Zero `<meta name="description">` on any page. One hardcoded `og:description` ("…spend time along or with people.") shared by every page. | `layouts/app.blade.php`, `components/seo-meta.blade.php` (new) | Google rewrites snippets arbitrarily; pages compete against each other. |
| **P0-5** | Weak & duplicate `<title>` | `/` = "TD Holiday Homes: Welcome". `/property` and `/search` share "Browse our apartments - TD Holiday Homes". `/partners/property` = "Login". `property-details.blade.php` = `# {{$property->reference}} - {{$property->name}}` — a **database reference number** in the title. | Controllers, `property-details.blade.php` | Zero geographic targeting; cannibalisation between `/property` and `/search`; reference numbers in SERPs. |
| **P0-6** | Missing canonicals & filter sprawl | No `<link rel="canonical">` anywhere. `?sort=`, `?type=`, `?price=`, `?guests=`, `?page=` generate crawlable duplicate URLs. | `layouts/app.blade.php`, `FrontPropertyController.php` | Crawl-budget waste and duplicate-content dilution. |
| **P0-7** | Missing robots directives & schema | No `meta name="robots"`, no `X-Robots-Tag`, no JSON-LD anywhere. `/login`, `/account`, `/account/wallet-funding`, `/partners/property` (login), `/emails/*`, `/admin/*` and `/property/{id}/reviews` are all indexable. | `app/Http/Middleware/NoIndex.php` (new), `app/Http/Kernel.php`, `routes/web.php`, `routes/customers/auth.php`, `routes/admins/auth.php`, `routes/partners/route.php` | Utility/auth/mock pages pollute the index; zero Rich Results eligibility. |
| **P0-8** | Host page is a login gate | "List your property" (`/partners/property`) renders a login form. Cannot rank for host-acquisition terms. | `routes/web.php`, `app/Http/Controllers/Front/PartnerLandingController.php` (new), `resources/views/partners/landing.blade.php` (new) | No host acquisition. No internal link target for host guides. |
| **P0-9** | NAP, trademark & copy errors | Footer shows `Call Us: +00000000` and `support@tdholidayhomes.com` and **no address at all**; `/contact` shows the real details. Footer claims "TDHolidayHomes.com is a registered trademarks" [sic]. Homepage typo "Have an vacant apartment?" and "spend time along". `alt="vho"` on the contact image — **and Google already indexes it as body text** (confirmed in the `tdhh.co.uk/contact` search snippet). `alt="VHO logo"` on the admin and partner header logos (old brand). | `common/footer.blade.php`, `home.blade.php`, `contact/form.blade.php`, `common/admin-header.blade.php`, `common/partner-header.blade.php` | Severe E-E-A-T and trust failure on a platform handling bookings and customer wallets. |
| **P0-10** | Fabricated social proof | `property-reviews.blade.php` is a static mockup with a fake property name and fake reviews, publicly routable. | `property-reviews.blade.php`, routes | Fake reviews in the index = trust risk and a manual-action vector. |
| **P1-11** | Dead assets & broken filter | Five asset URLs 404 on every page (two of them render-blocking in `<head>`). The price-range min/max input sync script does not exist. | `layouts/app.blade.php`, `property-list.blade.php` | Core Web Vitals penalty; broken price filter. |
| **P1-12** | HTTP cache bottleneck | All public pages return `cache-control: private, must-revalidate`, `pragma: no-cache`, `expires: -1`. Production is **nginx**, so `.htaccess` rules are inert. | `app/Http/Middleware/PublicCacheHeaders.php` (new), `app/Http/Kernel.php` | Kills TTFB/LCP for crawlers and mobile. |
| **P1-13** | Sister-site NAP & intent overlap | The **operating** address and `020 3247 4747` are shared with three sister businesses. **Correction to the original spec:** the *registered* office is **not** shared — it is `53 Tideslea Path, London SE28 0LY` since a later AD01. | Off-page governance, footer legal disclosure | Local-pack suppression; cannibalisation with the sister travel site's "Holiday Homes" page. |
| **P1-14** | Missing entity identity | No `Organization` / `LodgingBusiness` schema. `config('app.name')` is `Laravel`. The legal name in the register is `T D HOLIDAY HOMES LTD`, not "TD Holiday Homes Ltd". | `config/app.php`, `.env.example`, `layouts/app.blade.php` | Entity disambiguation failure. |
| **P1-15** | Orphaned/broken internal links | `admin/account/finance/list.blade.php:196` links `/property/{{$partnerCommission->id}}` — a **partner-commission id in a property URL**. `/account/property/add` CTA points at a dead route. `front.details` is used in 14 places (all must survive the 301). | `admin/account/finance/list.blade.php`, `common/header.blade.php` | Crawlers hit soft-404s; admin pages leak invalid URLs into the graph. |

---

## 3\. Strategic Positioning

### 3.1 Where `tdhh.co.uk` can win

Generic head terms are dominated by Airbnb, Booking.com and Vrbo. With an unverified inventory base and a physical base in **Charlton / Woolwich (SE18)**, the defensible position is a **hyper-local South East London + intent-led marketplace**:

1. **Hyper-local SE London stays** — Woolwich, Greenwich, Charlton, Royal Arsenal Riverside, Abbey Wood, Thamesmead. SERP competition here is small operators who can be outranked.
2. **Event, commuter & contractor stays** — ExCeL London, The O2 (North Greenwich), Elizabeth Line quick-access stays, corporate relocation.
3. **Price-led & amenity collections** — turn the "Properties under £100" homepage module into a crawlable collection page.
4. **Host supply acquisition** — a dedicated `/partners/property` landing page.
5. **UK–Nigeria corridor synergy** — travellers booking flights via the sister travel site who need verified London short-stay accommodation, without duplicating stay content on another domain.

### 3.2 NAP & cannibalization governance

1. **Shared operating address & phone.** Four sister businesses share `Unit 6, Block 3 Woolwich Dockyard Industrial Estate, SE18 5PQ` and `+44 (0) 203 247 4747`. Assign a distinct direct dial or extension to TD Holiday Homes before creating a Google Business Profile, and only register a GBP if guests can physically visit.
2. **Registered office is NOT shared** (corrected): `53 Tideslea Path, London, England, SE28 0LY`. It is a genuine address-uniqueness asset. The footer legal disclosure must carry it — this satisfies the Companies Act website disclosure requirement *and* differentiates the entity from the sister brands.
3. **Single canonical domain.** 301 `https://tdholidayhomes.com` (and `www.`) → `https://tdhh.co.uk`. **Note: `tdholidayhomes.com` currently does not resolve in DNS — the domain/DNS work must be done before the redirect can exist.**
4. **Sister travel site's "Holiday Homes" page** becomes a referral bridge only (link or 301) to `tdhh.co.uk`; all indexable accommodation content lives on `tdhh.co.uk`.

---

## 4\. Phase 0 — Diagnostics (Build First)

> **Rationale:** the listing count, the `status_id` distribution, and the slug coverage are all unknowns. Every downstream decision in Phase 1 depends on them. This phase is read-only and safe to run on production.

### Task 0.1: `seo:diagnose` Artisan command

**Target file:** `app/Console/Commands/SeoDiagnose.php` (create new)

Laravel 9 style: `protected $signature` / `protected $description` / `public function handle()`. The command is auto-discovered via `app/Console/Kernel.php` → `$this->load(__DIR__.'/Commands')`.

**Required output sections:**

| Section | Content |
| :---- | :---- |
| Environment | `APP_ENV`, `APP_DEBUG`, `APP_URL`, `DEBUGBAR_ENABLED`, `TELESCOPE_ENABLED`, `config('app.name')` |
| Inventory | Total `properties` rows; count grouped by `status_id` with the **name** resolved from `property_statuses`; the count of `status_id = 2` (Available) and `status_id = 1` (Pending) |
| Media | Total `media` rows; properties with **zero** images; properties whose `cover_image` is missing |
| Areas | `front_page_cities` row count, count with `status = 1`, and `state_id` values with no matching row in `states` |
| Slugs | Whether `slug` / `area_slug` columns exist; if they do, rows with a null/empty `slug` and duplicate slugs |
| Assets | Existence of the OG image candidates; `public/build/manifest.json` presence |
| Content | Properties with a description shorter than 150 characters; properties missing `name` |
| NAP | Current `general_settings` rows |

**Constraints:** strictly read-only. No writes, no migrations, no cache clears. Must not crash on an empty database (the local DB has 0 properties) and must not assume any column exists that Phase 2 has not yet created — guard every optional column with `Schema::hasColumn()`.

**Signature:** `seo:diagnose` with an optional `--json` flag for machine-readable output (so the result can be diffed between runs and pasted into the strategy file).

### Task 0.2: Run and record

Run locally first (validates the command against a 0-property database), then run on production and **record the output verbatim in this file** under a "Production Diagnostic Result" heading. The production database is not reachable from the development environment; the operator runs the command and pastes the output back.

**Gate:** no Phase 1 listing code is written until the diagnostic confirms the real inventory state and the published `status_id`.

---

## 5\. Phase 1 — P0 Emergency Fixes

### Task 1.1: Gate DebugBar & Telescope in code (repo-only)

**Target files:** `config/debugbar.php` (create new), `config/app.php`, `app/Providers/AppServiceProvider.php`, `app/Providers/TelescopeServiceProvider.php`, `config/telescope.php`, `.env.example`

**Status: IMPLEMENTED (verified).** The work turned out to be substantially more than the original five bullets. Three separate mechanisms had to be closed, and two of them are silent re-introduction paths that a naive fix would have missed.

The fix is **code-side and environment-driven**, so a mis-set `.env` cannot leak telemetry again. The production `.env` change is an operator step in the runbook (§11), not an agent action.

#### 1.1.1 `config/debugbar.php` (new) — full published config, fail closed

The package config was **published in full** (338 lines), not stubbed. Reason: `Barryvdh\Debugbar\ServiceProvider` calls `mergeConfigFrom()`, and `mergeConfigFrom()` is a **no-op when the app config is cached** (`bootstrap/cache/config.php`). A minimal stub therefore works in dev and silently reverts to the package defaults — `env('DEBUGBAR_ENABLED', null)` — the moment `config:cache` is run on production. Publishing the whole file guarantees the app's value wins under every caching state.

```php
'enabled' => env('DEBUGBAR_ENABLED', false) && env('APP_ENV', 'production') !== 'production',
```

The second clause is a hard interlock: even a mis-copied `.env` with `DEBUGBAR_ENABLED=true` cannot activate DebugBar while `APP_ENV=production`.

#### 1.1.2 `config/app.php` — the DebugBar provider was registered *outside* the gate

`config/app.php:190` listed `Barryvdh\Debugbar\ServiceProvider::class` in the `providers` array. That is an **unconditional registration** that completely bypasses any gate in `AppServiceProvider`, and it also re-registers the `_debugbar/*` routes. `composer.json` already excluded the package from auto-discovery (`dont-discover`), so this array entry was the *only* thing loading it — and it loaded it always.

**Fix:** the entry is commented out. The provider is now loaded only by `AppServiceProvider::register()`, behind `config('debugbar.enabled')`. This is a required change, not an optional one: without it, Task 1.1 step 2 alone has no effect.

#### 1.1.3 `app/Providers/AppServiceProvider.php` — single auditable control point

Registers `Barryvdh\Debugbar\ServiceProvider` only when `config('debugbar.enabled')` is true. `Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider` is gated on an explicit `IDE_HELPER_ENABLED` flag rather than on the environment.

#### 1.1.4 `app/Providers/TelescopeServiceProvider.php` — override `authorization()`, not just `Telescope::auth()`

**This was the actual root cause of the public dashboard, and the original spec missed it.**

`Laravel\Telescope\TelescopeApplicationServiceProvider::boot()` calls `$this->authorization()`, and that method registers:

```php
Telescope::auth(function ($request) {
    return app()->environment('local') || Gate::check('viewTelescope', [$request->user()]);
});
```

Because `boot()` runs **after** `register()`, a `Telescope::auth()` call made in `register()` is **silently overwritten** by the parent. The parent's callback grants access to *anyone* whenever the environment is `local` — and production was running `APP_ENV=local`. Result: `https://tdhh.co.uk/telescope` returned HTTP 200 to anonymous visitors, exposing Requests, Queries, **Mail with raw email downloads**, Dumps and Exceptions.

**Fix:** override `authorization()` in the app provider. The parent's `boot()` then calls the subclass method. The replacement callback never consults the environment:

- allow-list is read from `TELESCOPE_ALLOWED_EMAILS` (comma-separated, case-insensitive, whitespace-trimmed);
- **empty allow-list ⇒ deny everyone** (fail closed);
- an authenticated user whose email is on the list is allowed;
- anonymous requests are always denied.

`Telescope::night()` was removed deliberately: in this version it only sets a dark theme and does **not** restrict recording. The real controls are `config('telescope.enabled')`, `Telescope::filter()` and `Telescope::auth()`.

`Telescope::filter()` now returns `false` outright when `config('telescope.enabled')` is false, and in non-local environments records only reportable exceptions, failed requests, failed jobs, scheduled tasks and monitored tags. `hideRequestParameters(['_token'])` and `hideRequestHeaders(['cookie', 'x-csrf-token', 'x-xsrf-token'])` are applied outside local.

#### 1.1.5 `config/telescope.php` — fail closed with a production interlock

```php
'enabled' => env('TELESCOPE_ENABLED', false) && env('APP_ENV', 'production') !== 'production',
```

#### 1.1.6 `bootstrap/cache/routes-v7.php` — the cached route file keeps the routes alive

A cached route file exists in the repo working tree. When Laravel loads cached routes it **does not execute the live route registration**, so the `/telescope/*` and `/_debugbar/*` routes remain served even with `telescope.enabled = false` and `debugbar.enabled = false`. The `Telescope::auth()` override still protects the dashboard in that state, but the routes should not exist at all.

**Fix:** `php artisan route:clear` after deploying, then `php artisan route:cache`. This is a mandatory runbook step (§11.7) and the diagnostic now raises it as a CRITICAL flag.

#### 1.1.7 `.env.example` / local `.env`

`.env.example` gains `APP_NAME="TD Holiday Homes"`, `DEBUGBAR_ENABLED=false`, `TELESCOPE_ENABLED=false`, `TELESCOPE_ALLOWED_EMAILS=`, `IDE_HELPER_ENABLED=false`. The local (gitignored) `.env` sets the dev flags to `true` so the development experience is preserved; the runbook forces them to `false` in production.

#### 1.1.8 Verification performed

| Scenario | `debugbar.enabled` | `telescope.enabled` | DebugBar provider loaded | debug/telescope routes |
|---|---|---|---|---|
| `APP_ENV=local` (dev) | true | true | yes | 51 |
| `APP_ENV=production` | false | false | no | **0** |
| `APP_ENV=production` + both flags `true` (mis-copied `.env`) | false | false | no | **0** |

Telescope authorisation was exercised against 8 cases (anonymous with empty allow-list, anonymous with populated allow-list, allow-listed user, non-allow-listed user, mixed-case email, user with no allow-list, multi-entry allow-list, allow-list with stray spaces) — **all 8 pass**.

**Do not** hard-code `APP_DEBUG=false` in `config/app.php`; it must stay environment-driven so local debugging still works.


### Task 1.2: Fix the listing count, the hardcoded 116, and the pagination

**Target files:** `app/Http/Controllers/Front/FrontPropertyController.php`, `resources/views/property-list.blade.php`, `resources/views/common/pagination.blade.php`

1. **Remove the hardcoded `"Showing 1 - 12 items out of 116"`** entirely. It must be derived from the paginator, never written as a literal.
2. **`FrontPropertyController@propertyList`** — use Eloquent pagination with `->paginate(12)->withQueryString()` and render property cards server-side (never gated behind JavaScript). Keep the `status_id = 2` filter (semantically correct) until the diagnostic resolves D10.
3. **`property-list.blade.php`** — dynamic bounds:

```blade
@if($properties->total() > 0)
    <p class="results-summary">
        Showing {{ $properties->firstItem() }} – {{ $properties->lastItem() }} of {{ $properties->total() }} available short-stay apartments
    </p>
@else
    <p class="results-summary">No apartments are currently available. <a href="{{ route('contact-us.index') }}">Contact our team</a> to register your interest.</p>
@endif
```

The empty state must be a **helpful, indexable page**, not a bare soft-404.

4. **Property card link and image attributes** — every card links to the canonical property URL and carries explicit dimensions and descriptive alt text, using the **real** column names:

```blade
<a href="{{ route('stays.show', ['area' => $property->area_slug ?? 'london', 'slug' => $property->slug]) }}">
    <img src="{{ $property->cover_image->getUrl() }}"
         alt="{{ $property->name }} – {{ $property->bedroom_qty }}-bed short-stay apartment in {{ $property->city_name ?: 'London' }}"
         width="800" height="600" loading="lazy">
</a>
```

> `width`/`height` are hard-coded here because the `media` table has no dimension columns. Phase 2 Task 2.4a adds them; until then, the constants prevent CLS.

5. **`common/pagination.blade.php`** — correct the summary (it currently disagrees with itself) and output crawlable `<a href="{{ $properties->url($page) }}">` anchors so crawlers reach every page without JavaScript. The "Please enable JavaScript" banner must move into a `<noscript>` block so it never pollutes a snippet.

### Task 1.3: Replace `ORDER BY RAND()`

**Target file:** `app/Http/Controllers/Front/FrontPropertyController.php:45`

```php
// BEFORE — re-randomised on every request
DB::table('front_page_cities')->where('status', 1)->inRandomOrder()->limit(8)->get();

// AFTER — deterministic
DB::table('front_page_cities')
    ->where('status', 1)
    ->orderBy('id', 'asc')
    ->limit(8)
    ->get();
```

> **Correction:** the original spec ordered by `priority_order`, which **does not exist** on `front_page_cities`. Deterministic `id` ordering is the correct minimal fix. If a priority ordering is genuinely wanted later, it requires a **new** `priority_order` column (a schema change, not a query change) and should be driven by real inventory counts, not hand-set guesses.

### Task 1.4: Fix NAP, entity identity, typos, and the "vho" leak

**Target files:** `resources/views/common/footer.blade.php`, `resources/views/home.blade.php`, `resources/views/contact/form.blade.php`, `resources/views/common/admin-header.blade.php`, `resources/views/common/partner-header.blade.php`

1. **`common/footer.blade.php`** — replace the footer NAP with the §0.4 block and add the legal disclosure:

```html
<address class="footer-nap">
  <strong>TD Holiday Homes</strong><br>
  Unit 6, Block 3 Woolwich Dockyard Industrial Estate, Woolwich Church Street, Charlton, London, SE18 5PQ, United Kingdom<br>
  <a href="tel:+442032474747">+44 (0) 203 247 4747</a> &middot;
  <a href="mailto:info@tdhh.co.uk">info@tdhh.co.uk</a>
</address>
<p class="footer-legal">
  &copy; 2026 T D HOLIDAY HOMES LTD (Company No. 11247086).
  Registered in England and Wales. Registered office: 53 Tideslea Path, London, England, SE28 0LY.
</p>
```

   Remove the "+00000000" line, the `support@tdholidayhomes.com` line, and the sentence *"TDHolidayHomes.com is a registered trademarks and cannot be used without permission."* Replace the generic blurb with a one-sentence description of the marketplace and the areas served.
2. **`home.blade.php`** — "Have an vacant apartment?" → "Have a vacant apartment in London?"; fix "spend time along".
3. **`contact/form.blade.php`** — the "vho" is **not stray text**. It is `alt="vho"` on the contact image at line 102. Google is already surfacing it as page text. Change it to `alt="TD Holiday Homes customer helpdesk"`.
4. **`common/admin-header.blade.php:114` and `common/partner-header.blade.php:116`** — `alt="VHO logo"` → `alt="TD Holiday Homes logo"` (old brand name).

### Task 1.5: Public cache headers

**Target files:** `app/Http/Middleware/PublicCacheHeaders.php` (new), `app/Http/Kernel.php`

* Anonymous `GET` requests → `Cache-Control: public, max-age=600, s-maxage=600, stale-while-revalidate=300`.
* Authenticated or session-bearing requests → `Cache-Control: private, no-store`.
* **Production runs nginx**, so do not add `.htaccess` rules. Middleware only.

### Task 1.6: Remove dead asset references

**Target files:** `resources/views/layouts/app.blade.php`, `resources/views/property-list.blade.php`

* Delete `<link href="{{ asset('css/app.css') }}">` and `<link href="{{ asset('css/toastr.css') }}">` — `app.css` is **already bundled by Vite** through `resources/js/app.js:2`; the tags are duplicate render-blocking 404s. `toastr.css` must be imported into the Vite entry (`resources/js/app.js`) if the toast styles are wanted.
* Delete the `<script src="{{ asset('/js/toastr.js') }}">` tag and import `resources/js/toastr.js` from the Vite entry instead.
* In `property-list.blade.php`, remove the `/css/price_range_style.css` and `/js/price_range_style.js` references. jQuery UI is **already loaded from CDN**, so inline the min/max numeric-input sync against the existing `.slider()` initialisation rather than resurrecting a missing file.
* **Any new view must include `@vite('resources/js/app.js')`**, or it will render unstyled. (Consolidating the Vite tag into the layout is a worthwhile follow-up but touches ~15 views and is deferred.)

---

## 6\. Phase 2 — SEO Foundation

### Task 2.1: `<x-seo-meta>` and `<x-json-ld>` components

**Target files:** `resources/views/components/seo-meta.blade.php` (new — **the directory does not exist and must be created**), `resources/views/components/json-ld.blade.php` (new), `resources/views/layouts/app.blade.php` (modify)

**The controller → layout contract (standardised across the whole app):**

| Variable | Type | Purpose |
| :---- | :---- | :---- |
| `$seoTitle` | string | `<title>` |
| `$seoDescription` | string | `<meta name="description">` |
| `$seoCanonical` | string | `<link rel="canonical">` |
| `$seoRobots` | string | `<meta name="robots">` (default `index, follow`) |
| `$seoOgImage` | string | OG/Twitter image (absolute URL) |

**`resources/views/components/seo-meta.blade.php`:**

```blade
@props([
    'title' => 'Short-Stay Apartments in Woolwich, Greenwich & SE London | TD Holiday Homes',
    'description' => 'Book verified short-stay apartments and holiday lets in Woolwich, Greenwich, Charlton and South East London. Direct booking, transparent pricing, and stays under £100 per night.',
    'canonical' => null,
    'robots' => 'index, follow',
    'ogType' => 'website',
    'ogImage' => null,
])

@php
    $canonical = $canonical ?: url()->current();
    $ogImage = $ogImage ?: asset('img/bgs/home-bg.jpg');
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="TD Holiday Homes">
<meta property="og:locale" content="en_GB">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
```

> **Corrections:** the original spec's `asset('images/tdhh-og-default.webp')` points at a file that **does not exist** (`public/images/` does not exist; the real path is `public/img/`). `public/img/bgs/home-bg.jpg` exists but is **1280×353** — not the 1200×630 Facebook/Twitter minimum. A compliant 1200×630 OG asset is a **manual deliverable** and is flagged, not blocked.

**`resources/views/components/json-ld.blade.php`:**

```blade
@props(['schema' => null])

@if(!empty($schema))
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
```

**`resources/views/layouts/app.blade.php`:**

* Remove the hardcoded `<title>` and the global hardcoded `og:description`.
* Inject the component and the JSON-LD stack:

```blade
<x-seo-meta
    :title="$seoTitle ?? 'Short-Stay Apartments in Woolwich, Greenwich & SE London | TD Holiday Homes'"
    :description="$seoDescription ?? 'Book verified short-stay apartments and holiday lets in Woolwich, Greenwich, Charlton and South East London.'"
    :canonical="$seoCanonical ?? null"
    :robots="$seoRobots ?? 'index, follow'"
    :ogImage="$seoOgImage ?? null"
/>
@stack('json-ld')
```

### Task 2.2: `NoIndex` middleware

**Target files:** `app/Http/Middleware/NoIndex.php` (new), `app/Http/Kernel.php`, `routes/web.php`, `routes/customers/auth.php`, `routes/admins/auth.php`, `routes/partners/route.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoIndex
{
    public function handle(Request $request, Closure $next, string $directive = 'noindex, nofollow')
    {
        $request->attributes->set('seoRobots', $directive);

        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->headers->set('X-Robots-Tag', $directive);
        }

        return $response;
    }
}
```

> **Correction:** the original spec used `View::share('seoRobots', $directive)`. `View::share` is **global and persists for the rest of the request**, so a `noindex` route sharing a view with an indexable route would poison the indexable page. Setting a **request attribute** scopes it correctly.

Register `'noindex' => \App\Http\Middleware\NoIndex::class` in `$routeMiddleware`.

**Apply to:** `/login`, `/register`, `/account/**` (including `/account/wallet-funding`), `/partners/login`, `/partners/register`, `/partners/forgot-password`, `/admin/**`, `/emails/**`, and the property-reviews page.

**Do not** add these paths to `robots.txt` as `Disallow` — a blocked URL can never be crawled to see its `X-Robots-Tag`, so already-indexed URLs would never be de-indexed.

### Task 2.3: Separate `/property` from `/search`; canonicalise sort/filter

**Target file:** `app/Http/Controllers/Front/FrontPropertyController.php`

**`/search` → `noindex, follow`, canonicalised to `/property`:**

```php
$seoTitle = 'Search Short-Stay Apartments | TD Holiday Homes';
$seoDescription = 'Filter short-stay apartments in South East London by check-in dates, guests, price range, parking and amenities.';
$seoCanonical = route('front.list');
$seoRobots = 'noindex, follow';
```

**`/property` → self-canonical when clean, canonicalised to `/property` + `noindex, follow` when filtered:**

```php
$filterKeys = ['sort', 'price', 'min_price', 'max_price', 'guests', 'adults', 'children',
               'pets', 'check_in_date', 'check_out_date', 'num_of_adult', 'amenities',
               'characteristics', 'parking', 'house_rules', 'type', 'location'];

$hasFilter = false;
foreach ($filterKeys as $key) {
    if ($request->query($key) !== null && $request->query($key) !== '') {
        $hasFilter = true;
        break;
    }
}

$page = max(1, (int) $request->query('page', 1));

if ($hasFilter) {
    $seoCanonical = route('front.list');
    $seoRobots = 'noindex, follow';
} else {
    $seoCanonical = $page > 1 ? route('front.list', ['page' => $page]) : route('front.list');
    $seoRobots = 'index, follow';
}

$seoTitle = $page > 1
    ? "Short-Stay Apartments in London — Page {$page} | TD Holiday Homes"
    : 'Short-Stay Apartments in South East London & UK | TD Holiday Homes';

$seoDescription = 'Browse available short-stay apartments and holiday lets in Woolwich, Greenwich, Charlton and across London. Compare nightly rates and book direct with TD Holiday Homes.';
```

> **Correction:** the original spec used `$request->hasAny([...])`, which is **not a Laravel method**. The explicit loop above is correct.

### Task 2.4a: `slug` + `area_slug` columns — **IMPLEMENTED (verified)**

**Target file:** `database/migrations/2026_10_21_000002_add_slug_and_area_slug_to_properties_table.php`

* Added `slug` (**varchar(191)**, nullable) and `area_slug` (**varchar(191)**, nullable) to `properties`.
* Added `properties_slug_unique` (unique on `slug`), `properties_area_slug_index`, `properties_status_id_index` and `properties_id_index`. The table previously carried **only** its PRIMARY key on `reference`, so every `status_id` filter and every `id` join was a full table scan.
* Both slug columns are **nullable**, so the migration is safe on a live table; the backfill fills them.

**Why varchar(191) and not varchar(255):** at `utf8mb4` a `varchar(255)` unique index is 1020 bytes, which exceeds the 767-byte InnoDB key limit on older row formats. 191 characters is 764 bytes and fits every server. The backfill truncates to the same limit.

**Index discovery without doctrine/dbal:** the migration reads existing index names from `information_schema.STATISTICS` rather than through `Schema::getDoctrineSchemaManager()`, because `doctrine/dbal` is not a production dependency. The check therefore cannot throw on the production host, and the migration is safe to re-run.

**Verified locally:** after `php artisan migrate --force` the table reports `properties_slug_unique`, `properties_area_slug_index`, `properties_status_id_index`, `properties_id_index`.

### Task 2.4b: `seo:backfill-slugs` command — **IMPLEMENTED (verified)**

**Target file:** `app/Console/Commands/BackfillPropertySlugs.php`

* `slug` = slugified `name`; on collision, append `-{reference}`; uniqueness is guaranteed and additionally enforced by the unique index.
* `area_slug` = slugified `city_name`, falling back to `state_name`, then the outward code of `postal_code`, then `london`.
* Idempotent (safe to re-run), supports `--dry-run`, `--force` and `--limit`, and reports a summary.

**Two bugs were found and fixed during verification — both are recorded here because they are easy to reintroduce:**

1. **The "already taken" map was keyed wrongly.** The first implementation used `pluck('slug')->flip()`, which maps slug → collection *index*, and then `pluck('slug', 'reference')`, which maps *reference* → slug. Both are the wrong direction: the resolver needs **slug → owning reference** so that a property can recognise its own slug. With the wrong direction, `--force` reassigned every property the base slug and the unique index rejected the write with `Duplicate entry 'modern-city-centre-apartment'`. The correct call is `pluck('reference', 'slug')`.
2. **A local variable shadowed the counter.** `$collisions = 0;` in `handle()` shadowed the `$this->collisions` property that `uniqueSlug()` incremented, so the summary always reported `0`. The local was removed and the summary now reads `$this->collisions`.

**Verification performed (11 seeded properties covering every edge case):**

| Case | Result |
|---|---|
| Duplicate names | `modern-city-centre-apartment` and `modern-city-centre-apartment-90038` — collision disambiguated by reference |
| Empty `name` | `stay-90045` — falls back to the reference, never empty |
| `&` in the name | `the-old-granary-coach-house` |
| Accented characters | `cafe-apartment-wi-fi-included` |
| `city_name` NULL | falls back to `state_name` → `kent` |
| `city_name`, `state_name` and postcode all NULL | falls back to `london` |
| Distinct slugs after the run | 11 of 11 |
| NULL slugs / NULL area_slugs after the run | 0 / 0 |
| Re-run with `--force` | 0 created, 11 unchanged — **stable, no churn** |
| `--dry-run` | reports the changes, writes nothing (verified by re-reading the table) |

### Task 2.3a: `media` `width` / `height` — **IMPLEMENTED (verified)**

**Target files:** `database/migrations/2026_10_21_000001_add_width_height_to_media_table.php`, `app/Models/Media.php` (new), `config/media-library.php`, `app/Console/Commands/SeoBackfillMediaDimensions.php` (new)

The Spatie `media` table had no dimension columns, so the Open Graph and JSON-LD image objects could not declare `og:image:width` / `og:image:height`.

* **Migration** adds nullable `width` and `height` (`unsignedSmallInteger`) after `size`.
* **`App\Models\Media`** extends the Spatie model and captures the dimensions of every newly registered image in a `saved` hook, so the columns stay populated for future uploads with no per-controller work. `config/media-library.php` now points `media_model` at it.
* The dimension is read with `getimagesize()` from the local path when the disk is local, and falls back to `getimagesizefromstring()` for remote disks, so it works on S3 as well as on local storage.
* **Bug found during verification:** the custom model overrode `boot()` without calling `parent::boot()`. `Model::boot()` is what calls `bootTraits()`, so the trait initialisers were never registered and every instantiation of the model threw `Undefined array key "App\Models\Media"` from `initializeTraits()`. The override now calls `parent::boot()` first.
* **`seo:backfill-media-dimensions`** populates the columns for existing rows. It supports `--dry-run`, `--only-missing` and `--limit`, resolves dimensions from the files on disk, and reports rows scanned / resolved / unchanged / unreadable.
* `App\Models\Media` also exposes `orientation` (landscape / portrait / square) and `aspect_ratio` accessors, which the listing and detail templates use to avoid stretching images with fixed dimensions.

**Verification performed:** three synthetic images of known dimensions (1200×630, 400×900, 800×800) were registered and the stored values matched exactly, with the correct orientation and aspect ratio. The backfill was exercised against two legacy rows inserted directly through the query builder (so the model hook could not fire): `--dry-run` reported 2 resolutions and wrote nothing; the real run wrote 1600×900 and 600×1200. All test rows and files were removed afterwards.

### Task 2.5: Canonical property URL + 301 from legacy URLs

**Target files:** `routes/web.php`, `app/Http/Controllers/Front/FrontPropertyController.php`

```php
Route::get('/stays/{area}/{slug}', [FrontPropertyController::class, 'show'])->name('stays.show');
```

* `FrontPropertyController@show` resolves the property by `slug` (scoped to `area_slug` when it matches, else by `slug` alone).
* `FrontPropertyController@details` becomes a **301** to `route('stays.show', [...])` and **preserves the full query string** (`check_in_date`, `check_out_date`, `num_of_adult`, …) via `request()->getQueryString()`.
* The 14 existing `route('front.details', …)` call sites keep working (D7). Only `common/home-grid.blade.php` should link directly to `stays.show`.

### Task 2.6: `/partners/property` host landing page

**Target files:** `app/Http/Controllers/Front/PartnerLandingController.php` (new), `resources/views/partners/landing.blade.php` (new — the `partners` directory exists), `routes/web.php`, `routes/partners/route.php`, `resources/views/common/partner-sidebar.blade.php`, `resources/views/common/partner-header.blade.php`

* **Rename the authenticated route** in `routes/partners/route.php` from `partner.property.list` (`/partners/property`) to `partner.property-list` (`/partners/dashboard/property`) — this resolves the route shadowing in §1.6 and frees `/partners/property` for the public landing page.
* The landing page must include `@vite('resources/js/app.js')` (see Task 1.6) or it will render unstyled.
* **Per D5: publish no commission percentage.** The page sells "no upfront listing fee", free professional photography, verified guests, and full house-rule control. Any fee/commission table must be added only after the business supplies a verified figure.
* CTAs: "Register as a Partner Host" → `/partners/register`; "Existing Partner Login" → `/partners/login`.
* Update the hardcoded `/partners/property` links in `partner-sidebar.blade.php` and `partner-header.blade.php` to the new dashboard path.

### Task 2.7: `sitemap:generate` + `robots.txt`

**Target files:** `app/Console/Commands/GenerateSitemap.php` (new), `app/Console/Kernel.php`, `public/robots.txt`

* Core static URLs: `/`, `/property`, `/contact`, `/about`, `/partners/property` (plus any trust pages that actually exist).
* Property URLs from `stays.show` for `status_id = 2` properties that pass the thin-content guard.
* Order by **`reference`** (the indexed auto-increment PK), not `id` (an unindexed `char(36)`).
* Schedule daily: `$schedule->command('sitemap:generate')->dailyAt('02:30');`
* `public/robots.txt`:

```txt
User-agent: *
Allow: /
Disallow: /_debugbar/
Disallow: /telescope/

Sitemap: https://tdhh.co.uk/sitemap.xml
```

> Do **not** `Disallow` `/login`, `/account` or `/search` — those must be crawlable so Googlebot can read their `noindex` directive.

---

## 7\. Phase 3 — Page Re-Engineering & Schema (specified, not yet approved)

### Task 3.1: Homepage (`/`)

**Target controller:** `FrontPropertyController@home`

* `$seoTitle`: `Short-Stay Apartments in Woolwich & SE London | TD Holiday Homes`
* `$seoDescription`: `Book verified short-stay apartments in Woolwich, Greenwich, Charlton & SE London. Direct rates, stays near ExCeL & The O2, and apartments under £100 per night.`
* `<h1>`: **Short-Stay Apartments in South East London**
* Replace the four generic lifestyle blurbs (Have Fun / Meditate / Get Focus / Energize) with a trust block: verified local hosts & inspected apartments; direct Elizabeth Line & DLR links to ExCeL and Central London; secure online booking & customer wallet protection; a dedicated London helpdesk in Charlton (SE18).
* Replace the "Properties under £100" module's query-parameter link with a clean collection URL.
* Add `Organization` + `LodgingBusiness` JSON-LD using the §0.4 NAP, `legalName` = **"T D HOLIDAY HOMES LTD"**, `logo` = `asset('img/icons/logo-black.png')` (the spec's `images/logo.png` does not exist).

### Task 3.2: Listing directory (`/property`)

* `<h1>`: **Short-Stay Apartments in London**; add a ~120-word intro with internal quick-filter links.
* Convert the sort links to a `<form method="GET">` dropdown or add `rel="nofollow"`, so sorted permutations do not consume crawl budget.

### Task 3.3: Property detail pages (`/stays/{area}/{slug}`)

**Target file:** `resources/views/property-details.blade.php` (**the existing detail view — the spec's `resources/views/property/show.blade.php` does not exist**)

* `$seoTitle`: `{$property->name} – {$property->bedroom_qty}-Bed Apartment in {$property->city_name} | TD Holiday Homes`
* `$seoDescription`: `Book {$property->name}, a {$property->bedroom_qty}-bed short-stay apartment in {$property->city_name} (sleeps {$property->max_guest}). {$topAmenities}. Instant online booking.`
* **Thin-content guardrail** — automatic `noindex, follow` when the listing cannot support a page:

```php
$isThinListing = strlen(strip_tags($property->description ?? '')) < 150
    || $property->propertyImages->count() < 2
    || !$property->city_name;
$seoRobots = $isThinListing ? 'noindex, follow' : 'index, follow';
```

* Required sections: `<h1>` with the property name and bedroom count; gallery with `width`/`height` and descriptive alt; "About This Apartment in {area}"; "Amenities & Facilities" (rendering `$characteristic->characteristic->name`); "House Rules & Cancellation Policy"; "Location & Nearby Transport Links"; "Verified Guest Reviews".
* `Apartment` JSON-LD using **real column names** — `price` not `nightly_rate`, `bedroom_qty` not `bedrooms`, `max_guest` not `max_guests`, `postal_code` not `postcode_outward`, `$property->propertyImages` not `$property->images`, `$property->city_name` not `$property->area`.
* **`aggregateRating` must only render when a real verified review exists.** There is **no** `verified_reviews_count` accessor today — it must be added, counting `reviews` where `status` indicates verified. Never fabricate it.

### Task 3.4: Host landing page (`/partners/property`)

See Task 2.6. Additionally: `<h1>`, a 3-step "How listing works" block, host protections, and a FAQ section with matching `FAQPage` JSON-LD.

### Task 3.5: Contact page & trust pages

* `/contact` — `<h1>Contact TD Holiday Homes Customer & Host Helpdesk`, a "How to find us" transport block (4-minute walk from Woolwich Dockyard station, 6 minutes from Charlton), and `LocalBusiness` + `BreadcrumbList` JSON-LD matching the footer NAP.
* `/about` — legal name **T D HOLIDAY HOMES LTD**, Company No. **11247086**, registered office **53 Tideslea Path, London SE28 0LY**, operating address, incorporation date 10 March 2018, SIC codes 55209/55900.
* Additional trust pages: `/how-booking-works`, `/cancellation-and-refunds`, `/safety-and-verification`.

---

## 8\. Phase 4 — Topical Authority (specified, not yet approved)

> **Guardrail:** never generate empty doorway pages. Every area hub and collection page must query for real `status_id = 2` inventory, and if the count is **< 3**, the page must set `noindex, follow` (and the link must be hidden) until real supply exists.

### Task 4.1: Area hubs (`/stays/{area}`)

Because `properties.city_name` / `state_name` are free-text (the `states` table is a generic country list, §1.3), area hubs must be driven by the **`area_slug`** column created in Task 2.4a, with a config map of the supported areas: `woolwich`, `greenwich`, `charlton`, `royal-arsenal-riverside`, `abbey-wood`, `thamesmead`.

Each hub: a unique ~200-word local introduction; a server-rendered grid of real properties; a "Getting there" block (Elizabeth Line, DLR, Thames Clippers, Southeastern); a "What's nearby" block (ExCeL, The O2, Greenwich Park); an area FAQ with `FAQPage` JSON-LD.

### Task 4.2: Collections (`/stays/{collection}`)

* `/stays/under-100` — `where('price', '<=', 100)`
* `/stays/near-excel` — Woolwich / Royal Arsenal / Custom House corridor
* `/stays/near-the-o2` — Greenwich / Charlton / North Greenwich corridor
* `/stays/pet-friendly` — properties where the "Pets not allowed" house rule is absent
* `/stays/long-stays` — properties supporting 7+ or 28+ night stays

Each must be backed by a real query and gated by the ≥3 listings rule.

### Task 4.3: Guides (`/guides/{slug}`)

Seven guides interlinking to area hubs, collections, and `/partners/property`. The UK short-let / Greater London 90-night rule guide must be verified against current legislation before publishing.

---

## 9\. Phase 5 — Sitemap & Robots (specified, not yet approved)

Covered operationally by Task 2.7. The generator streams published property URLs in chunks of 100, writes `public/sitemap.xml`, and is scheduled daily at 02:30.

---

## 10\. 90-Day Roadmap

### Week 1 — Stabilisation, index hygiene, core fixes

1. Disable DebugBar & Telescope **in code** (Task 1.1) and run the §11 runbook.
2. Run `seo:diagnose` on production; record the result in this file; resolve the `status_id` decision (D10).
3. Fix `/property`: remove the hardcoded 116, fix the paginator summary, ensure server-rendered cards and crawlable `?page=2` links (Task 1.2).
4. Deploy `<x-seo-meta>`, `<x-json-ld>`, and `NoIndex`; give every page a unique title, description, and canonical (Tasks 2.1–2.3).
5. Fix NAP, entity identity, typos, and the "vho" alt-text leak; remove dead asset tags (Tasks 1.4, 1.6).
6. Generate and submit `sitemap.xml`; verify `tdhh.co.uk` in Google Search Console.

### Weeks 2–4 — Money pages, host landing, trust architecture

7. Add `slug`/`area_slug`, backfill, publish `/stays/{area}/{slug}`, and 301 the legacy URLs (Tasks 2.4a–2.5).
8. Launch the rebuilt `/partners/property` host landing page (Task 2.6).
9. Publish `/about`, `/how-booking-works`, `/cancellation-and-refunds`, and site-wide `Organization` / `LodgingBusiness` / `BreadcrumbList` schema (Task 3.5).
10. Resolve sister-site cannibalisation; assign a distinct phone extension for TD Holiday Homes.

### Months 2–3 — Authority, reviews, off-page

11. Launch area hubs and collections under the ≥3-listing guardrail.
12. Publish the guides and interlink them.
13. Post-stay review collection (enabling `aggregateRating` legitimately), Trustpilot, and consistent UK citations.

---

## 11\. Deployment Runbook (operator-executed — D3)

The agent makes **repo changes only**. The operator runs the following on the production host.

```sh
# 0. Pull the branch
git pull

# 1. Install production dependencies
composer install --no-dev --optimize-autoloader

# 2. Environment (edit production .env by hand)
#    APP_ENV=production
#    APP_DEBUG=false
#    APP_URL=https://tdhh.co.uk
#    APP_NAME="TD Holiday Homes"
#    DEBUGBAR_ENABLED=false
#    TELESCOPE_ENABLED=false
#    TELESCOPE_ALLOWED_EMAILS=            # leave EMPTY to deny everyone
#    IDE_HELPER_ENABLED=false
#    NOTE: APP_ENV=production is what actually closed the public Telescope
#    dashboard. The in-code interlocks are a second line of defence, not a
#    substitute for it.

# 3. Schema
php artisan migrate --force

# 4. Slug backfill (safe to re-run; use --dry-run first to preview)
php artisan seo:backfill-slugs --dry-run
php artisan seo:backfill-slugs

# 4b. Media dimensions (safe to re-run; use --dry-run first to preview)
php artisan seo:backfill-media-dimensions --dry-run
php artisan seo:backfill-media-dimensions

# 5. Verify the inventory state and capture the result
php artisan seo:diagnose

# 6. Sitemap
php artisan sitemap:generate

# 7. Clear and rebuild caches
#    route:clear is MANDATORY before route:cache. A stale cached route file
#    keeps /telescope and /_debugbar routes live even when the packages are
#    disabled, because Laravel serves the cached routes instead of running
#    the live registrations.
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Reload nginx
sudo systemctl reload nginx

# 9. Verify
curl -s https://tdhh.co.uk/ | grep -ci -E "phpdebugbar|telescope"   # expect 0
curl -sI https://tdhh.co.uk/ | grep -i cache-control                   # expect public, max-age=600
curl -s https://tdhh.co.uk/sitemap.xml | head -5
curl -sI https://tdhh.co.uk/telescope | head -1                       # expect 404 (not 200)
curl -sI https://tdhh.co.uk/_debugbar/open | head -1                  # expect 404 (not 200)
```

**Not performed by the agent (requires operator or account access):** production `.env` edits, nginx config changes, `tdholidayhomes.com` DNS creation, the 301 from `tdholidayhomes.com` → `tdhh.co.uk`, Google Search Console verification, and any Google Business Profile registration.

---

## 12\. QA Checklist

```sh
BASE=https://tdhh.co.uk

# No telemetry leakage
curl -s $BASE/ | grep -ci -E "phpdebugbar|telescope|FrontPropertyController"   # expect 0
curl -sI $BASE/telescope | head -1                                            # expect 404
curl -sI $BASE/_debugbar/open | head -1                                       # expect 404
curl -s $BASE/_debugbar/assets/stylesheets | grep -ci debugbar                # expect 0

# Server-rendered property links, no JavaScript required
curl -s $BASE/property | grep -c "/stays/"                                     # expect >= 12 (once inventory exists)

# Crawlable pagination
curl -s "$BASE/property?page=2" | grep -c 'href="[^"]*page=2"'

# Unique metadata per page
for p in / /property /search /contact /partners/property; do
  echo "== $p"; curl -s "$BASE$p" | grep -E '<title>|name="description"|rel="canonical"'
done

# Noindex enforcement
for p in /login /account /emails /admin /property/1/reviews; do
  curl -sI "$BASE$p" | grep -i "x-robots-tag"
done

# Filter canonicalisation
curl -s "$BASE/property?sort=price-low" | grep -E 'rel="canonical"|name="robots"'

# Legacy 301 chain (query string must survive)
curl -sI "$BASE/property/1?check_in_date=2026-11-01&num_of_adult=2" | grep -i location

# NAP correctness
curl -s $BASE/ | grep -c -E "\+00000000|support@tdholidayhomes.com|registered trademarks|Have an vacant|vho"

# Sitemap and robots
curl -sI $BASE/sitemap.xml | head -1
curl -s $BASE/robots.txt
```

**Manual checks (cannot be automated):** every page has exactly one `<h1>`; the footer NAP matches §0.4 verbatim; `tdholidayhomes.com` resolves and 301s; `sitemap.xml` contains only 200-OK canonical URLs.

---

## 13\. Production Diagnostic Result

> **PENDING.** To be populated with the verbatim output of `php artisan seo:diagnose` run against the production database. This section gates the `status_id` decision (D10) and every listing-related task in Phase 1.

| Metric | Value | Source |
| :---- | :---- | :---- |
| Total properties | _pending_ | `seo:diagnose` |
| Properties by status | _pending_ | `seo:diagnose` |
| Available (`status_id = 2`) | _pending_ | `seo:diagnose` |
| Pending (`status_id = 1`) | _pending_ | `seo:diagnose` |
| Properties with zero images | _pending_ | `seo:diagnose` |
| `front_page_cities` (status = 1) | _pending_ | `seo:diagnose` |
| Rows with `slug` | _pending_ | `seo:diagnose` (post-backfill) |

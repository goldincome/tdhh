<?php

namespace App\Support;

use App\Models\Property\Property;
use Illuminate\Support\Str;

/**
 * Builds the metadata contract consumed by the seo-meta and json-ld Blade
 * components.
 *
 * Every public controller returns one of these arrays merged into its view
 * data, so the title, description, canonical, Open Graph image and structured
 * data for a page are decided in exactly one place instead of being spread
 * across the controller and the Blade template.
 */
class Seo
{
    /**
     * The default Open Graph image. 1200x675 is the smallest asset on disk
     * that clears the 1200x630 minimum for a large summary card.
     */
    public const DEFAULT_OG_IMAGE = 'img/bgs/home-sample-2.jpg';

    /**
     * Metadata for the home page.
     *
     * @return array<string, mixed>
     */
    public static function home(): array
    {
        $nap = tdhh_nap();

        return self::base([
            'seoTitle' => 'Short-Stay Apartments in Woolwich, Greenwich & South East London | '.$nap['brand'],
            'seoDescription' => 'Book verified short-stay apartments and holiday lets in Woolwich, Greenwich, Charlton and South East London. Direct booking, transparent pricing, and stays from under £100 per night.',
            'seoCanonical' => $nap['url'],
            'seoJsonLd' => [
                self::localBusiness(),
                self::webSite(),
            ],
        ]);
    }

    /**
     * Metadata for the paginated listing of every available property.
     *
     * @param  string|null  $heading
     * @param  string|null  $description
     * @param  string|null  $canonical
     * @return array<string, mixed>
     */
    public static function listing(?string $heading = null, ?string $description = null, ?string $canonical = null): array
    {
        $nap = tdhh_nap();

        return self::base([
            'seoTitle' => trim(($heading ?: 'Short-Stay Apartments & Holiday Lets').' | '.$nap['brand']),
            'seoDescription' => $description ?: 'Browse every verified short-stay apartment and holiday let available to book directly with TD Holiday Homes.',
            'seoCanonical' => $canonical ?: url('/property'),
            'seoJsonLd' => [
                self::localBusiness(),
                self::breadcrumbs([
                    ['name' => 'Home', 'item' => $nap['url'].'/'],
                    ['name' => 'Short-stay apartments', 'item' => url('/property')],
                ]),
            ],
        ]);
    }

    /**
     * Metadata for a location filtered listing, for example /property/location/woolwich.
     *
     * @param  string  $location
     * @return array<string, mixed>
     */
    public static function locationListing(string $location): array
    {
        $location = trim($location);
        $nicely = ucwords(str_replace('-', ' ', $location));

        return self::listing(
            'Short-Stay Apartments in '.$nicely,
            'Find verified short-stay apartments and holiday lets in '.$nicely.'. Book directly with TD Holiday Homes, no hidden fees.',
            url('/property/location/'.$location)
        );
    }

    /**
     * Metadata for the search results page.
     *
     * Search results are generated from user supplied filters, so they are
     * kept out of the index. The canonical still points at the clean
     * /property listing so the two pages do not compete.
     *
     * @return array<string, mixed>
     */
    public static function searchResults(): array
    {
        $nap = tdhh_nap();

        return self::base([
            'seoTitle' => 'Search Short-Stay Apartments | '.$nap['brand'],
            'seoDescription' => 'Search available short-stay apartments and holiday lets by dates, price, guest count and location.',
            'seoCanonical' => url('/property'),
            'seoRobots' => 'noindex, follow',
            'seoJsonLd' => [
                self::localBusiness(),
            ],
        ]);
    }

    /**
     * Metadata for a single property.
     *
     * @param  \App\Models\Property\Property  $property
     * @param  string  $canonical
     * @param  string  $currencyCode
     * @return array<string, mixed>
     */
    public static function property(Property $property, string $canonical, string $currencyCode = 'GBP'): array
    {
        $nap = tdhh_nap();

        $cover = $property->cover_image;
        $imageUrl = $cover ? $cover->getUrl() : asset(self::DEFAULT_OG_IMAGE);
        $imageWidth = $cover && $cover->width ? $cover->width : 1200;
        $imageHeight = $cover && $cover->height ? $cover->height : 675;

        $location = trim(implode(', ', array_filter([
            $property->city_name,
            $property->state_name,
        ])));

        $title = trim($property->name.' | '.$nap['brand']);

        $description = trim(strip_tags((string) $property->description));
        $description = Str::limit(preg_replace('/\s+/u', ' ', $description), 300, '…');
        if ($description === '') {
            $description = 'Book '.$property->name.' in '.$location.' directly with '.$nap['brand'].'.';
        }

        $meta = self::base([
            'seoTitle' => $title,
            'seoDescription' => $description,
            'seoCanonical' => $canonical,
            'seoOgImage' => $imageUrl,
            'seoOgImageWidth' => $imageWidth,
            'seoOgImageHeight' => $imageHeight,
            'seoOgImageAlt' => $property->name,
            'seoJsonLd' => [
                self::localBusiness(),
                self::breadcrumbs([
                    ['name' => 'Home', 'item' => $nap['url'].'/'],
                    ['name' => 'Short-stay apartments', 'item' => url('/property')],
                    ['name' => $property->name, 'item' => $canonical],
                ]),
                self::apartment($property, $canonical, $imageUrl, $currencyCode),
            ],
        ]);

        return $meta;
    }

    /**
     * Metadata for the contact page.
     *
     * @return array<string, mixed>
     */
    public static function contact(): array
    {
        $nap = tdhh_nap();

        return self::base([
            'seoTitle' => 'Contact TD Holiday Homes | Bookings, Hosts & Support',
            'seoDescription' => 'Talk to the TD Holiday Homes team about a booking, a stay or listing your property. Call '.$nap['phone_display'].' or email '.$nap['email'].'.',
            'seoCanonical' => url('/contact'),
            'seoJsonLd' => [
                self::localBusiness(),
                self::contactPoint(),
            ],
        ]);
    }

    /**
     * Metadata for the public property host landing page.
     *
     * @return array<string, mixed>
     */
    public static function partnerLanding(): array
    {
        $nap = tdhh_nap();

        return self::base([
            'seoTitle' => 'List Your Property With TD Holiday Homes | London Short-Lets',
            'seoDescription' => 'List your apartment or holiday let in London with TD Holiday Homes. Direct bookings, transparent pricing and a hands-on partner team.',
            'seoCanonical' => url('/partners/property'),
            'seoJsonLd' => [
                self::localBusiness(),
                self::breadcrumbs([
                    ['name' => 'Home', 'item' => $nap['url'].'/'],
                    ['name' => 'List your property', 'item' => url('/partners/property')],
                ]),
            ],
        ]);
    }

    /**
     * The shared defaults applied to every metadata array.
     *
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    protected static function base(array $meta): array
    {
        return array_merge([
            'seoTitle' => null,
            'seoDescription' => null,
            'seoCanonical' => null,
            'seoRobots' => null,
            'seoOgImage' => null,
            'seoOgImageWidth' => null,
            'seoOgImageHeight' => null,
            'seoOgImageAlt' => null,
            'seoJsonLd' => null,
        ], $meta);
    }

    /**
     * The business entity for the company behind the site.
     *
     * @return array<string, mixed>
     */
    public static function localBusiness(): array
    {
        $nap = tdhh_nap();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => $nap['url'].'/#business',
            'name' => $nap['brand'],
            'legalName' => $nap['legal_name'],
            'url' => $nap['url'],
            'telephone' => $nap['phone_href'],
            'email' => $nap['email'],
            'image' => asset(self::DEFAULT_OG_IMAGE),
            'address' => tdhh_schema_address(),
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => 'Company number',
                'value' => $nap['company_number'],
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'London',
            ],
            'priceRange' => '££',
        ];
    }

    /**
     * The site entity that publishes the business.
     *
     * @return array<string, mixed>
     */
    public static function webSite(): array
    {
        $nap = tdhh_nap();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $nap['url'].'/#website',
            'name' => $nap['brand'],
            'url' => $nap['url'],
            'publisher' => ['@id' => $nap['url'].'/#business'],
            'inLanguage' => 'en-GB',
        ];
    }

    /**
     * The contact channel for bookings and support.
     *
     * @return array<string, mixed>
     */
    public static function contactPoint(): array
    {
        $nap = tdhh_nap();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPoint',
            'telephone' => $nap['phone_href'],
            'email' => $nap['email'],
            'contactType' => 'customer service',
            'areaServed' => 'GB',
            'availableLanguage' => ['en-GB'],
        ];
    }

    /**
     * A breadcrumb trail.
     *
     * @param  array<int, array<string, string>>  $items
     * @return array<string, mixed>
     */
    public static function breadcrumbs(array $items): array
    {
        $elements = [];
        $position = 1;

        foreach ($items as $item) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => $item['item'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * A single let described as an apartment with an offer.
     *
     * @param  \App\Models\Property\Property  $property
     * @param  string  $canonical
     * @param  string  $imageUrl
     * @param  string  $currencyCode
     * @return array<string, mixed>
     */
    protected static function apartment(Property $property, string $canonical, string $imageUrl, string $currencyCode): array
    {
        $apartment = [
            '@context' => 'https://schema.org',
            '@type' => 'Apartment',
            'name' => $property->name,
            'url' => $canonical,
            'image' => [$imageUrl],
            'description' => trim(strip_tags((string) $property->description)),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $property->address,
                'addressLocality' => $property->city_name,
                'addressRegion' => $property->state_name,
                'postalCode' => $property->postal_code,
                'addressCountry' => $property->country_name ?: 'GB',
            ],
            'numberOfRooms' => $property->room_quantity ?: null,
            'numberOfBedrooms' => $property->bedroom_qty ?: null,
            'numberOfBathrooms' => $property->bathroom_qty ?: null,
            'numberOfBathroomsTotal' => $property->bathroom_qty ?: null,
            'amenityFeature' => self::amenities($property),
            'offers' => [
                '@type' => 'Offer',
                'url' => $canonical,
                'price' => (string) $property->price,
                'priceCurrency' => $currencyCode,
                'availability' => 'https://schema.org/InStock',
                'businessFunction' => 'https://schema.org/LeaseOut',
            ],
        ];

        if ($property->max_guest) {
            $apartment['occupancy'] = [
                '@type' => 'QuantitativeValue',
                'maxValue' => $property->max_guest,
                'unitText' => 'guests',
            ];
        }

        return array_filter($apartment, fn ($value) => $value !== null && $value !== '' && $value !== []);
    }

    /**
     * The amenity list for a property.
     *
     * @param  \App\Models\Property\Property  $property
     * @return array<int, array<string, string>>
     */
    protected static function amenities(Property $property): array
    {
        $amenities = [];

        try {
            foreach ($property->characteristics as $link) {
                $name = optional($link->characteristic)->name;

                if ($name) {
                    $amenities[] = [
                        '@type' => 'LocationFeatureSpecification',
                        'name' => $name,
                        'value' => true,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // A property without characteristics simply has no amenities.
        }

        return $amenities;
    }
}

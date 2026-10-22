@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'robots' => null,
    'ogType' => 'website',
    'ogImage' => null,
    'ogImageWidth' => null,
    'ogImageHeight' => null,
    'ogImageAlt' => null,
])

@php
    $nap = tdhh_nap();

    $defaultTitle = 'Short-Stay Apartments in Woolwich, Greenwich & South East London | '.$nap['brand'];
    $defaultDescription = 'Book verified short-stay apartments and holiday lets in Woolwich, Greenwich, Charlton and South East London. Direct booking, transparent pricing, and stays under £100 per night.';

    // The legacy views still declare @section('title'). Read it so the
    // existing pages keep a sensible title while the controllers are moved
    // over to the $seoTitle contract.
    $sectionTitle = trim(preg_replace('/\s+/u', ' ', strip_tags((string) \Illuminate\Support\Facades\View::yieldContent('title'))));

    $resolvedTitle = trim((string) $title);
    if ($resolvedTitle === '') {
        $resolvedTitle = $sectionTitle !== '' ? $sectionTitle.' | '.$nap['brand'] : $defaultTitle;
    }

    $resolvedDescription = trim((string) $description);
    if ($resolvedDescription === '') {
        $resolvedDescription = $defaultDescription;
    }

    $resolvedCanonical = trim((string) $canonical);
    if ($resolvedCanonical === '') {
        $resolvedCanonical = url()->current();
    }

    // The NoIndex middleware stores its directive on the request, which
    // scopes it to a single request instead of leaking it to every other
    // view rendered in the same request.
    $resolvedRobots = trim((string) $robots);
    if ($resolvedRobots === '') {
        $resolvedRobots = trim((string) request()->attributes->get('seoRobots', ''));
    }
    if ($resolvedRobots === '') {
        $resolvedRobots = 'index, follow';
    }

    $resolvedOgImage = trim((string) $ogImage);
    if ($resolvedOgImage === '') {
        $resolvedOgImage = asset('img/bgs/home-sample-2.jpg');
    }

    if ($ogImageWidth === null || $ogImageHeight === null) {
        $dimensions = @getimagesize(public_path('img/bgs/home-sample-2.jpg'));
        $ogImageWidth = $ogImageWidth ?: ($dimensions[0] ?? null);
        $ogImageHeight = $ogImageHeight ?: ($dimensions[1] ?? null);
    }
@endphp

<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $resolvedDescription }}">
<meta name="robots" content="{{ $resolvedRobots }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">

<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ $resolvedDescription }}">
<meta property="og:image" content="{{ $resolvedOgImage }}">
@if($ogImageWidth)
    <meta property="og:image:width" content="{{ $ogImageWidth }}">
@endif
@if($ogImageHeight)
    <meta property="og:image:height" content="{{ $ogImageHeight }}">
@endif
@if($ogImageAlt)
    <meta property="og:image:alt" content="{{ $ogImageAlt }}">
@endif
<meta property="og:site_name" content="{{ $nap['brand'] }}">
<meta property="og:locale" content="en_GB">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ $resolvedDescription }}">
<meta name="twitter:image" content="{{ $resolvedOgImage }}">

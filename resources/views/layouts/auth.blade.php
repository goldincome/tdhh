<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="msapplication-TileImage" content="{{ asset('favicon.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <x-seo-meta
        :title="$seoTitle ?? null"
        :description="$seoDescription ?? null"
        :canonical="$seoCanonical ?? null"
        robots="noindex, nofollow"
        :ogImage="$seoOgImage ?? null"
    />
    <x-json-ld :schema="$seoJsonLd ?? null" />
    @stack('json-ld')

    <meta name="author" content="TD Holiday Homes">
    @yield('og')

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    @yield('css')

    <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>
    <script src="https://kit.fontawesome.com/841fd16d98.js" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('js/custom.js') }}"></script> --}}
    @yield('js')

</head>


<body>
    <noscript id="jsc">
        <div>
            Please enable JavaScript in your browser.
            <span class="d-none d-sm-inline">
                Some functionality will not work if this is disabled.
                <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
            </span>
        </div>
        <div></div>
    </noscript>

    <div class="md:flex min-h-screen text-black">
        <div class="w-full bg-tdhh-orange md:w-2/4 md:min-h-screen relative overflow-hidden">
            <div class="w-full h-full md:h-screen p-6 pb-10 md:pb-20 md:flex bg-cover relative z-20 bg-no-repeat">


                <a href="/" class="h-8 sm:h-10 inline-block animate hover:scale-110">
                    <img src={{asset('img/icons/logo-all-black.png')}} alt="tdhh" class="h-full object-contain" />
                </a>


                <div class="auth-slideshow max-w-sm m-auto">

                    <div class="slideshow-each">
                        <div class="max-w-sm text-center">
                            <div class="w-56 h-56 md:w-64 md:h-64 lg:w-96 lg:h-96 p-6 md:p-8 lg:p-10 mx-auto flex bg-opacity-5 rounded-xl">
                                <div class="w-full h-full bg-white m-auto flex rounded-xl overflow-hidden shadow-lg">
                                    <img src={{asset('img/bgs/home-bg.jpg')}} alt="TDHH" class="object-cover mx-auto lg:mx-0" />
                                </div>
                            </div>
                            <div class="mt-4 lg:-mt-3">
                                <div class="font-semibold text-xl sm:text-2xl md:text-3xl">
                                    Stay, unwind, relax
                                </div>
                                <div class="mt-3 md:mt-6">
                                    <div class="w-20 h-1.5 bg-black rounded-full mx-auto"></div>
                                </div>
                                <div class="mt-3 md:mt-6">
                                    TDHolidayHomes.com offers its esteemed users, the opportunity to choose awesome places to spend time along or with people.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <div class="bg-auth"></div>
        </div>

        <div class="w-full md:w-2/4">
            @include('layouts.errors-and-messages')
            <div class="md:flex md:min-h-screen">
                @yield('content')
            </div>
        </div>

    </div>

</body>
</html>


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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <script src="{{ asset('js/custom.js') }}"></script> --}}
    <script>
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        // toastr.options.timeOut = 300000;
    </script>
    <script>
        $(document).ready(function(){
            $("#dropdownButtonHeaderNav").click(() => {
                if ($("#dropdownHeaderNav").hasClass("block")){
                    $("#dropdownHeaderNav").css({"transform":"none"});
                }
            });
            $("#dropdownButtonHeaderUser").click(() => {
                if ($("#dropdownHeaderUser").hasClass("block")){
                    $("#dropdownHeaderUser").css({"transform":"none"});
                }
            });
        });
    </script>
    @yield('js')
    @vite('resources/js/app.js')

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

    <div class="flex min-h-screen bg-gray-100">
        @include('common.admin-sidebar')
        <div class="h-screen flex-none hidden lg:block" style="width: 250px;"></div>
        <div class="flex-grow overflow-hidden">
            @include('common.admin-header')
            <div class="pt-6 sm:pt-10 pb-24 md:px-10">
                @include('layouts.errors-and-messages')

                @yield('content')
            </div>
        </div>
    </div>


</body>
</html>

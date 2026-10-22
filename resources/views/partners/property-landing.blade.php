@extends('layouts.app')

@section('title')
    List Your Property
@endsection

@section('content')

    @php
        $nap = tdhh_nap();
    @endphp

    <section class="bg-white">
        <div class="container py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-tdhh-black">
                        List your property with TD Holiday Homes
                    </h1>

                    <p class="mt-6 text-lg text-tdhh-gray">
                        Turn your London apartment, room or holiday let into a booked-out
                        short stay. TD Holiday Homes connects verified guests with verified
                        hosts, and you stay in control of your listing, your prices and your
                        availability.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ url('/partners/register') }}" class="btn btn-tdhh-orange">
                            List your property
                        </a>
                        <a href="{{ url('/partners/login') }}" class="btn btn-transparent-black">
                            Partner sign in
                        </a>
                    </div>

                    <p class="mt-6 text-sm text-tdhh-gray">
                        Questions first? Call
                        <a href="{{ $nap['phone_href'] }}" class="text-tdhh-orange">{{ $nap['phone_display'] }}</a>
                        or email
                        <a href="mailto:{{ $nap['email'] }}" class="text-tdhh-orange">{{ $nap['email'] }}</a>.
                    </p>
                </div>

                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="border border-tdhh-gray p-6">
                            <div class="text-tdhh-orange font-bold text-lg">Direct bookings</div>
                            <p class="mt-2 text-tdhh-gray">
                                Guests book with you directly. No third-party broker, no
                                opaque reseller, no chasing a booking you never saw.
                            </p>
                        </div>
                        <div class="border border-tdhh-gray p-6">
                            <div class="text-tdhh-orange font-bold text-lg">Transparent pricing</div>
                            <p class="mt-2 text-tdhh-gray">
                                You set the nightly rate and you see the full breakdown
                                before you publish. Nothing is deducted behind your back.
                            </p>
                        </div>
                        <div class="border border-tdhh-gray p-6">
                            <div class="text-tdhh-orange font-bold text-lg">Verified guests</div>
                            <p class="mt-2 text-tdhh-gray">
                                Every booking comes from a guest with a verified email and
                                payment method on file.
                            </p>
                        </div>
                        <div class="border border-tdhh-gray p-6">
                            <div class="text-tdhh-orange font-bold text-lg">A real local team</div>
                            <p class="mt-2 text-tdhh-gray">
                                You are supported by people based in South East London who
                                answer the phone and know the market.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-tdhh-gray">
        <div class="container py-16">
            <h2 class="text-3xl font-bold text-tdhh-black">How it works</h2>

            <div class="mt-10 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="text-tdhh-orange font-bold text-2xl">01</div>
                    <h3 class="mt-2 font-bold text-tdhh-black">Create your partner account</h3>
                    <p class="mt-2 text-tdhh-gray">
                        Register as a host and tell us about the property you want to list.
                    </p>
                </div>
                <div>
                    <div class="text-tdhh-orange font-bold text-2xl">02</div>
                    <h3 class="mt-2 font-bold text-tdhh-black">Add your property</h3>
                    <p class="mt-2 text-tdhh-gray">
                        Upload photos, describe the space, set your price and your
                        availability calendar.
                    </p>
                </div>
                <div>
                    <div class="text-tdhh-orange font-bold text-2xl">03</div>
                    <h3 class="mt-2 font-bold text-tdhh-black">Get booked</h3>
                    <p class="mt-2 text-tdhh-gray">
                        Verified guests request and pay for their stay. You approve the
                        request and the booking is confirmed.
                    </p>
                </div>
                <div>
                    <div class="text-tdhh-orange font-bold text-2xl">04</div>
                    <h3 class="mt-2 font-bold text-tdhh-black">Get paid</h3>
                    <p class="mt-2 text-tdhh-gray">
                        Payouts are handled on a set schedule, with a clear statement of
                        every charge.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="container py-16">
            <div class="max-w-3xl">
                <h2 class="text-3xl font-bold text-tdhh-black">
                    Properties we are asked for most
                </h2>

                <ul class="mt-8 space-y-4 text-tdhh-gray">
                    <li class="flex items-start gap-3">
                        <span class="fa fa-check text-tdhh-orange mt-1"></span>
                        <span>Serviced and short-stay apartments in Woolwich, Greenwich, Charlton and the wider South East London.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="fa fa-check text-tdhh-orange mt-1"></span>
                        <span>Studio, one and two bedroom lets that are easy to reach from central London.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="fa fa-check text-tdhh-orange mt-1"></span>
                        <span>Properties that are furnished and ready for a guest to arrive and settle in.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="fa fa-check text-tdhh-orange mt-1"></span>
                        <span>Hosts who want a straightforward agreement and a clear payout schedule.</span>
                    </li>
                </ul>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ url('/partners/register') }}" class="btn btn-tdhh-orange">
                        Start listing
                    </a>
                    <a href="{{ url('/contact') }}" class="btn btn-transparent-black">
                        Talk to the team
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-tdhh-black">
        <div class="container py-16 text-center">
            <h2 class="text-3xl font-bold text-white">
                Ready to list with a team that answers the phone?
            </h2>
            <p class="mt-4 text-tdhh-gray">
                {{ $nap['legal_name'] }} is a company registered in England and Wales
                (company number {{ $nap['company_number'] }}). Registered office:
                {{ $nap['registered_office'] }}.
            </p>
            <p class="mt-2 text-tdhh-gray">
                Operating address: {{ $nap['operating_address'] }}.
            </p>
            <a href="{{ url('/partners/register') }}" class="btn btn-tdhh-orange mt-8 inline-block">
                Create your partner account
            </a>
        </div>
    </section>

@endsection

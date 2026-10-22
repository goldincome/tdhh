<div class="h-screen flex-none fixed bg-black hidden lg:block shadow-lg" style="width: 250px;">
    <div class="w-full h-full sidebar-bg-ep-pattern bg-cover bg-no-repeat" style="background-position: 0 20px;">

        <div class="p-6 flex">
            <a href="/" target="_blank" class="h-8 inline-block mx-auto">
                <img src={{asset('img/icons/logo.png')}} alt="EarniPay logo" class="h-full object-contain" />
            </a>
        </div>

        <div class="mt-4 sidebar-body h-screen overflow-auto">
            <a href="{{route('partner.dashboard')}}" class="sidebar-links admin-sidebar-dashboard">
                <span class="fa fa-tachometer w-7"></span>
                Dashboard
            </a>

            <div class="sidebar-links-divider">
                Main Navigation
            </div>


            <a href="/partners/dashboard/property" class="sidebar-links admin-sidebar-property">
                <span class="fa fa-home w-7"></span>
                Properties
            </a>

            <a href="/partners/reservations" class="sidebar-links admin-sidebar-reservations">
                <span class="fa fa-calendar-check w-7"></span>
                Reservations
            </a>

            <a href="/partners/finance" class="sidebar-links admin-sidebar-finance">
                <span class="fa fa-money w-7"></span>
                Finance
            </a>

            {{--
            <a href="/partners/reports" class="sidebar-links admin-sidebar-reports">
                <span class="fa fa-files-o w-7"></span>
                Reports
            </a>
             --}}

            <div class="sidebar-links-divider">
                Others
            </div>

            {{-- <a href="/partners/reviews" class="sidebar-links admin-sidebar-reviews">
                <span class="fa fa-star w-7"></span>
                Reviews
            </a> --}}

            <a href="/partners/messages" class="sidebar-links admin-sidebar-messages">
                <span class="fa fa-envelope w-7"></span>
                Messages
            </a>

            <div class="sidebar-links-divider">
                Account
            </div>

            <a href="{{route('partner.logout')}}" class="sidebar-links">
                <span class="fa fa-sign-out w-7"></span>
                Logout
            </a>

            <div class="pb-28"></div>
        </div>


    </div>
</div>

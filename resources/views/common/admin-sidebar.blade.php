<div class="h-screen flex-none fixed bg-black hidden lg:block shadow-lg" style="width: 250px;">
    <div class="w-full h-full sidebar-bg-ep-pattern bg-cover bg-no-repeat" style="background-position: 0 20px;">

        <div class="p-6 flex">
            <a href="/" target="_blank" class="h-8 inline-block mx-auto">
                <img src={{asset('img/icons/logo.png')}} alt="EarniPay logo" class="h-full object-contain" />
            </a>
        </div>

        <div class="mt-4 sidebar-body h-screen overflow-auto">
            <a href="/admin/dashboard" class="sidebar-links admin-sidebar-dashboard">
                <span class="fa fa-tachometer w-7"></span>
                Dashboard
            </a>

            <div class="sidebar-links-divider">
                Main Navigation
            </div>


            <a href="/admin/property" class="sidebar-links admin-sidebar-property">
                <span class="fa fa-home w-7"></span>
                Properties
            </a>

            <a href="/admin/reservations" class="sidebar-links admin-sidebar-reservations">
                <span class="fa fa-calendar-check w-7"></span>
                Reservations
            </a>

            <a href="/admin/finance" class="sidebar-links admin-sidebar-finance">
                <span class="fa fa-money w-7"></span>
                Finance
            </a>
            <a href="/admin/wallet-funding" class="sidebar-links admin-sidebar-wallet">
                <span class="fa fa-money w-7"></span>
                Wallet Funding
            </a>

            <a href="/admin/refunds" class="sidebar-links admin-sidebar-refunds">
                <span class="fa fa-money w-7"></span>
                Refunds
            </a>


            {{-- <a href="/admin/reports" class="sidebar-links admin-sidebar-reports">
                <span class="fa fa-files-o w-7"></span>
                Reports
            </a> --}}

            <div class="sidebar-links-divider">
                Others
            </div>
            
            {{--
            <a href="/admin/reviews" class="sidebar-links admin-sidebar-reviews">
                <span class="fa fa-star w-7"></span>
                Reviews
            </a>
            --}}

            <a href="/admin/customers" class="sidebar-links admin-sidebar-customers">
                <span class="fa fa-user-group w-7"></span>
                Customers
            </a>

            <a href="/admin/partners" class="sidebar-links admin-sidebar-partners">
                <span class="fa fa-user-shield w-7"></span>
                Partners
            </a>

            <a href="/admin/admins" class="sidebar-links admin-sidebar-admins">
                <span class="fa fa-user-cog w-7"></span>
                Admins
            </a>

            <a href="/admin/messages" class="sidebar-links admin-sidebar-messages">
                <span class="fa fa-envelope w-7"></span>
                Messages
            </a>

            <div class="sidebar-links-divider">
                Settings
            </div>

            <a href="/admin/general-settings" class="sidebar-links admin-sidebar-general">
                <span class="fa fa-cog w-7"></span>
                General Settings
            </a>

            <a href="/admin/bank-accounts" class="sidebar-links admin-sidebar-bank-accounts">
                <span class="fa fa-money w-7"></span>
                Bank Accounts
            </a>

            <a href="/admin/currencies" class="sidebar-links admin-sidebar-currencies">
                <span class="fa fa-cog w-7"></span>
                Currencies
            </a>

            <a href="/admin/front-page-cities" class="sidebar-links admin-sidebar-cities">
                <span class="fa fa-cog w-7"></span>
                Front Page Cities
            </a>

            <a href="/admin/states" class="sidebar-links admin-sidebar-states">
                <span class="fa fa-flag w-7"></span>
               States
            </a>

            <div class="sidebar-links-divider">
                Account
            </div>

            <a href="{{route('admin.logout')}}" class="sidebar-links">
                <span class="fa fa-sign-out w-7"></span>
                Logout
            </a>

            <div class="pb-28"></div>
        </div>


    </div>
</div>

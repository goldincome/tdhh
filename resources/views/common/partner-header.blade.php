
<div class="h-16 bg-white px-6 hidden lg:flex">
    <div class="w-full my-auto relative flex space-x-5 justify-end">

        <div class=" w-12 h-12 my-auto cursor-pointer relative flex rounded hover:bg-gray-200">
            <div class="w-4 h-4 flex rounded-full text-white bg-red-500 absolute z-10 top-2 right-0.5">
                <span class="m-auto text-xs">4</span>
            </div>
            <span class="fa fa-bell text-xl m-auto"></span>
        </div>

        <div class="hidden w-px h-12 my-auto bg-gray-200">&nbsp;</div>

        <div class="relative">
            <button type="button" id="dropdownButtonAdminHeaderUser" data-dropdown-toggle="dropdownAdminHeaderUser" class="h-12 relative px-2 flex cursor-pointer hover:bg-gray-200 rounded">
                <div class="text-right my-auto mr-3">
                    <div class="text-ep-blue text-sm font-semibold capitalize">
                        @auth
                            {{auth('partner')->user()->full_name}}
                        @endauth
                    </div>
                    <div class="text-xs capitalize">
                        Partner
                    </div>
                </div>
                <div class="w-10 h-10 my-auto mr-3 bg-black flex font-bold rounded-full overflow-hidden">
                    <span class="fa fa-user-tie m-auto text-white text-xl"></span>
                </div>
                <div class="h-full flex">
                    <span class="fa fa-caret-down m-auto"></span>
                </div>
            </button>
            <div id="dropdownAdminHeaderUser" class="dropdown-menu-nav hidden">
                <ul class="py-1" aria-labelledby="dropdownButton1">
                    <li>
                        <a href="/" class="dropdown-menu-option">Goto Website</a>
                    </li>
                    <li>
                        <a href="/partners/login" class="dropdown-menu-option">Logout</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>



<div class="w-full h-14 fixed z-50 bg-black lg:hidden">
    <div class="container h-14 px-4 mx-auto flex items-center justify-between">

        <div>
            <div class="w-full flex justify-end">
                <button type="button" id="dropdownButtonHeaderNav" data-dropdown-toggle="dropdownHeaderNav" class="w-10 h-10 my-auto text-white group flex rounded cursor-pointer">
                    <span class="fa fa-bars text-2xl m-auto text-white group-hover:text-tdhh-orange"></span>
                </button>
            </div>
            <div id="dropdownHeaderNav" class="dropdown-menu-nav hidden">
                <div class="pt-4 pb-4 w-full min-h-screen fixed z-50 top-14 inset-x-0 bg-black text-white bg-opacity-90 border-t border-white border-opacity-25">
                    <div class="h-screen pb-40 overflow-auto overscroll-contain">

                        <a href="/partners/dashboard" class="sidebar-links admin-sidebar-dashboard">
                            <span class="fa fa-tachometer w-7"></span>
                            Dashboard
                        </a>

                        <div class="w-full h-px my-4 bg-white opacity-25"></div>

                        <a href="/partners/dashboard/property" class="sidebar-links admin-sidebar-property">
                            <span class="fa fa-home w-7"></span>
                            Properties
                        </a>

                        <a href="/partners/bookings" class="sidebar-links admin-sidebar-bookings">
                            <span class="fa fa-calendar-check w-7"></span>
                            Bookings
                        </a>

                        <a href="/partners/payments" class="sidebar-links admin-sidebar-payments">
                            <span class="fa fa-money w-7"></span>
                            Payments
                        </a>

                        <a href="/partners/reports" class="sidebar-links admin-sidebar-reports">
                            <span class="fa fa-files-o w-7"></span>
                            Reports
                        </a>

                        <div class="w-full h-px my-4 bg-white opacity-25"></div>

                        <a href="/partners/reviews" class="sidebar-links admin-sidebar-reviews">
                            <span class="fa fa-star w-7"></span>
                            Reviews
                        </a>

                        <a href="/partners/messages" class="sidebar-links admin-sidebar-messages">
                            <span class="fa fa-envelope w-7"></span>
                            Messages
                        </a>

                        <div class="w-full h-px my-4 bg-white opacity-25"></div>

                        <a href="/login" class="sidebar-links">
                            <span class="fa fa-sign-out w-7"></span>
                            Logout
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0 my-auto">
            <a href="/" class="h-8 flex">
                <img src={{asset('img/icons/logo.png')}} alt="TD Holiday Homes logo" class="h-8 my-auto object-contain" />
            </a>
        </div>

        <div as="div">
            <button type="button" id="dropdownButtonHeaderUser" data-dropdown-toggle="dropdownHeaderUser" class="w-10 h-10 text-white group flex rounded cursor-pointer" onClick={this.toggleHeaderMobileSidebar}>
                <span class="fa fa-user-tie text-2xl m-auto text-white group-hover:text-tdhh-orange"></span>
            </button>
            <div id="dropdownHeaderUser" class="dropdown-menu-nav hidden">
                <div class="fixed min-h-screen z-50 top-14 inset-x-0 bg-black text-white bg-opacity-90  border-t border-white border-opacity-25">
                    <div class="h-screen py-20 overflow-auto overscroll-contain">
                        <div class="space-y-10 pb-14 text-center">
                            <div>
                                <a href="/" class="text-xl mx-auto font-bold btn btn-lg btn-transparent-yellow hover:text-black hover:bg-pc-yellow">
                                    Goto Website
                                </a>
                            </div>
                            <div>
                                <a href="/login" class="header-links-mobile cursor-pointer">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="w-full h-14 lg:hidden"></div>

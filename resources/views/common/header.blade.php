<div class="header-bg bg-white w-full fixed z-50 shadow-lg">
    <div class="h-14 lg:h-20 mx-auto flex">
        
        {{-- 
        <div class="hidden nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown-currency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Currency
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown-currency">
                @foreach(currency()->getCurrencies() as $currency)
                    @if($currency['code']=='USD')
                        <a class="dropdown-item" href="?currency={{$currency['code']}}">
                            <span>
                                @if(currency()->getUserCurrency()=='USD')<span class="fa fa-check-circle active"></span>@endif
                            </span>
                            <span class="flag-icon flag-icon-us"> </span>  US Dollar ($)
                        </a>
                    @endif
                    @if($currency['code']=='NGN')
                        <a class="dropdown-item" href="?currency={{$currency['code']}}">
                            <span>
                                @if(currency()->getUserCurrency()=='NGN')<span class="fa fa-check-circle active"></span>@endif
                            </span>
                            <span class="flag-icon flag-icon-ng"> </span>  Nigerian Naira (₦)
                        </a>
                    @endif
                    @if($currency['code']=='GBP')
                        <a class="dropdown-item" href="?currency={{$currency['code']}}">
                            <span>
                                @if(currency()->getUserCurrency()=='GBP')<span class="fa fa-check-circle active"></span>@endif
                            </span>
                            <span class="flag-icon flag-icon-gb"> </span>  British Pounds (£)
                        </a>
                    @endif
                    @if($currency['code']=='EUR')
                        <a class="dropdown-item" href="?currency={{$currency['code']}}">
                            <span>
                                @if(currency()->getUserCurrency()=='EUR')<span class="fa fa-check-circle active"></span>@endif
                            </span>
                            <span class="flag-icon flag-icon-eu"> </span>  Euro (€)
                        </a>
                    @endif
                 @endforeach 
            </div>
        </div>
         --}}

         
         
         <div class="w-full hidden lg:block">
             <div class="w-full h-6 bg-gray-200">
                <div class="container h-6 flex justify-end">
                    
                    <div>
                        <button id="dropdownButton" data-dropdown-toggle="dropdown1" class="w-max h-full bg-gray-300 font-medium text-xs px-4 text-center flex items-center" type="button">
                            Currency
                            <span class="fa fa-angle-down ml-2"></span>
                        </button>
                        <div id="dropdown1" class="dropdown-menu-nav hidden">
                            <div class="py-0 dropdown-options-list" aria-labelledby="dropdownButton">
                                @foreach(currency()->getCurrencies() as $currency)
                                    @if($currency['code']=='USD')
                                        <a class="dropdown-menu-option" href="?currency={{$currency['code']}}">
                                            <span class="flag-icon flag-icon-us"> </span>  US Dollar ($)
                                            <span>
                                                @if(currency()->getUserCurrency()=='USD')<span class="fa fa-check-circle active text-tdhh-orange ml-1"></span>@endif
                                            </span>
                                        </a>
                                    @endif
                                    @if($currency['code']=='NGN')
                                        <a class="dropdown-menu-option" href="?currency={{$currency['code']}}">
                                            <span class="flag-icon flag-icon-ng"> </span>  Nigerian Naira (₦)
                                            <span>
                                                @if(currency()->getUserCurrency()=='NGN')<span class="fa fa-check-circle active text-tdhh-orange ml-1"></span>@endif
                                            </span>
                                        </a>
                                    @endif
                                    @if($currency['code']=='GBP')
                                        <a class="dropdown-menu-option" href="?currency={{$currency['code']}}">
                                            <span class="flag-icon flag-icon-gb"> </span>  British Pounds (£)
                                            <span>
                                                @if(currency()->getUserCurrency()=='GBP')<span class="fa fa-check-circle active text-tdhh-orange ml-1"></span>@endif
                                            </span>
                                        </a>
                                    @endif
                                    @if($currency['code']=='EUR')
                                        <a class="dropdown-menu-option" href="?currency={{$currency['code']}}">
                                            <span class="flag-icon flag-icon-eu"> </span>  Euro (€)
                                            <span>
                                                @if(currency()->getUserCurrency()=='EUR')<span class="fa fa-check-circle active text-tdhh-orange ml-1"></span>@endif
                                            </span>
                                        </a>
                                    @endif
                                @endforeach 
                            </div>
                        </div>
                    </div>
                    
                </div>
             </div>
             
             <div class="container mt-1 lg:flex">
                <div class="flex-shrink-0 my-auto">
                    <a href="/" class="h-12 py-1 block">
                        <img src={{asset('img/icons/logo-black.png')}} alt="TD Holiday Homes" class="h-full object-contain" />
                    </a>
                </div>

                <div class="flex-grow flex" style="font-size: 14px;">
                    <div class="flex flex-grow h-full">
                        <div class="flex m-auto space-x-6 xl:space-x-10">
                            <a href="/property" class="header-links hidden">
                                All properties
                            </a>
                            <a href="{{route('front.property.search')}}" class="header-links">
                                Search property
                            </a>
                            <a href="/partners/property" class="header-links">
                                List your property
                            </a>
                            <a href="{{ route('contact-us.index') }}" class="header-links">
                                Contact Us
                            </a>
                        </div>
                    </div>
                    <div class="flex-shrink-0 h-full flex">
                        <div class="flex my-auto space-x-6">
                            @auth
                                <a href="/account" class="btn hover:text-black hover:bg-gray-200 flex" style="padding: 8px;">
                                    <div class="w-6 h-6">
                                        <img src={{asset('img/users/dp.png')}} alt="TD Holiday Homes" class="h-full object-contain" />
                                    </div>
                                    <div>
                                        {{auth()->user()->first_name}}
                                    </div>
                                    <span class="fa fa-angle-down ml-2"></span>
                                </a>
                            @else
                                <a href="/login" class="my-auto header-links">
                                    Login
                                </a>
                                <a href="{{route('front.property.search')}}" class="btn bg-tdhh-orange">
                                    Book an apartment
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="w-full h-14 flex lg:hidden">
            <div class="container my-auto text-white flex items-center justify-between">



                <div>
                    <div class="w-full flex justify-end">
                        <button type="button" id="dropdownButton" data-dropdown-toggle="dropdownHeaderMobileNav" class="w-10 h-10 my-auto text-xl hover:bg-tdhh-orange flex rounded cursor-pointer">
                            <div class="w-8 h-8 m-auto flex text-lg bg-white rounded overflow-hidden">
                                <span class="fa fa-bars text-black m-auto"></span>
                            </div>
                        </button>
                    </div>

                    <div id="dropdownHeaderMobileNav" class="hidden w-full fixed min-h-screen z-40 top-14 inset-x-0 bg-black text-white bg-opacity-90">
                        <div aria-labelledby="dropdownButton">
                            <div class="">
                                <div class="h-screen py-20 overflow-auto overscroll-contain">
                                    <div class="space-y-10 pb-14 text-center">
                                        <div>
                                            <a href="/" exact class="header-links-mobile">
                                                Home
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{route('front.property.search')}}" class="header-links-mobile">
                                                Search property
                                            </a>
                                        </div>
                                        <div>
                                            <a href="/partners/property" class="header-links-mobile">
                                                Add your property
                                            </a>
                                        </div>
                                        <div>
                                            <a href="/contact" class="header-links-mobile">
                                                Contact us
                                            </a>
                                        </div>

                                        <div>
                                            <a href="{{route('front.property.search')}}" class="text-xl mx-auto font-light btn btn-lg btn-transparent-yellow hover:text-black hover:bg-tdhh-orange">
                                                Book an apartment
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex-shrink-0 my-auto">
                    <a href="/" class="h-12 py-1 block">
                        <img src={{asset('img/icons/logo-black.png')}} alt="TD Holiday Homes" class="h-full object-contain" />
                    </a>
                </div>


                <div>
                    <a href="/account" class="w-10 h-10 group hover:bg-tdhh-orange flex rounded cursor-pointer">
                        <div class="w-8 h-8 m-auto bg-white flex text-lg font-bold rounded overflow-hidden">
                            <div class="m-auto">
                                <span class="fa fa-user text-black"></span>
                            </div>
                        </div>
                    </a>

                    {{-- 
                    <button type="button" id="dropdownButton" data-dropdown-toggle="dropdownHeaderMobileAccount" class="w-10 h-10 group hover:bg-tdhh-orange flex rounded cursor-pointer">
                        <div class="w-8 h-8 m-auto bg-white flex text-lg font-bold rounded overflow-hidden">
                            <div class="m-auto">
                                <span class="fa fa-user text-black"></span>
                            </div>
                        </div>
                    </button>

                    <div id="dropdownHeaderMobileAccount" class="hidden w-full fixed min-h-screen z-40 top-14 inset-x-0 bg-black text-white bg-opacity-90">
                        <div aria-labelledby="dropdownButton">
                            <div class="">
                                <div class="h-screen py-20 overflow-auto overscroll-contain">
                                    <div class="space-y-10 pb-14 text-center">
                                        @auth
                                            <div>
                                                <a href="/account/profile" class="text-xl mx-auto font-bold btn btn-lg btn-transparent-yellow hover:text-black hover:bg-tdhh-orange">
                                                    My Profile
                                                </a>
                                            </div>
                                            <div>
                                                <a href="/account/bookings" class="text-xl mx-auto font-bold btn btn-lg btn-transparent-yellow hover:text-black hover:bg-tdhh-orange">
                                                    My Reservations
                                                </a>
                                            </div>
                                            <div>
                                                <a href="/account/reviews" class="text-xl mx-auto font-bold btn btn-lg btn-transparent-yellow hover:text-black hover:bg-tdhh-orange">
                                                    My Reviews
                                                </a>
                                            </div>
                                            <div>
                                                <a href="/account/settings" class="text-xl mx-auto font-bold btn btn-lg btn-transparent-yellow hover:text-black hover:bg-tdhh-orange">
                                                    Settings
                                                </a>
                                            </div>
                                            <div>
                                                <div onClick={this.props.logout} class="header-links-mobile cursor-pointer">
                                                    Logout
                                                </div>
                                            </div>
                                        @else
                                            <div>
                                                <a href="/login" class="header-links-mobile">
                                                    Log In
                                                </a>
                                            </div>
                                            <div>
                                                <a href="/register" class="text-xl mx-auto font-light btn btn-lg btn-transparent-yellow hover:text-black hover:bg-tdhh-orange">
                                                    Create New Account
                                                </a>
                                            </div>
                                        @endauth

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     --}}
                </div>


            </div>
        </div>



    </div>
</div>

<div class="header-bg-under h-14 lg:h-16 bg-white">
</div>

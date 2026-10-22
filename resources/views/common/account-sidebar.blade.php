<div>

    <div class="w-36 h-36 mx-auto flex bg-gray-100 border-8 border-white rounded-full shadow-md">
        <span class="fa fa-user text-7xl m-auto"></span>
    </div>

    <div class="mt-4 text-center">
        <div class="font-bold">
            Firstname Lastname
        </div>
        <div class="text-fade">
            Registered: Nov 2019
        </div>
        <div class="mt-2">
            <a href="/account/profile" class="btn btn-transparent-black btn-sm mx-auto">
                View Profile
            </a>
        </div>
    </div>

    <div class="mt-10">
        <a href="/account/profile" class="account-sidebar <?=$page === "profile" ? "active" : "";?>">
            My profile
        </a>
        <a href="/account/bookings" class="account-sidebar <?=$page === "bookings" ? "active" : "";?>">
            My reservations
        </a>
        <a href="{{route('front.account.wallet-funding.index')}}" class="account-sidebar <?=$page === "wallet-funding" ? "active" : "";?>">
            My wallet
        </a>
        <a href="/account/reviews" class="account-sidebar <?=$page === "reviews" ? "active" : "";?>">
            My reviews
        </a>
        <a href="/account/settings" class="account-sidebar <?=$page === "settings" ? "active" : "";?>">
            Account settings
        </a>
        <a href="{{route('user.logout')}}" class="account-sidebar">
            Logout
        </a>
    </div>

</div>

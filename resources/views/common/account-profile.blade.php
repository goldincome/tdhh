<div class="flex justify-between">
    <div>
        <a href="/account" class="lg:hidden btn btn-transparent-black btn-md">
            <span class="fa fa-angle-left mr-2"></span>
            Back
        </a>
    </div>
    <a href="{{route('front.account.profile.edit')}}" class="btn btn-tdhh-orange btn-md">
        Edit profile
    </a>
</div>


<div class="mt-8 border-t space-y-8">
    

    <div>
        <div class="p-4 font-bold bg-gray-200">
            Basic Information
        </div>
        <div class="border p-8 space-y-4">

            <div class="py-4 border-b lg:flex lg:space-x-4">
                <div class="w-full lg:w-[1/2] text-fade flex items-center">
                    <div class="w-10">
                        <span class="fa fa-user"></span>
                    </div>
                    <div>
                        Firstname
                    </div>
                </div>
                <div class="w-full lg:w-[1/2]">
                    <div class="mt-2 lg:mt-0 ml-10 lg:ml-0">
                        {{auth()->user()->first_name}} 
                    </div>
                </div>
            </div>

            <div class="py-4 border-b lg:flex lg:space-x-4">
                <div class="w-full lg:w-[1/2] text-fade flex items-center">
                    <div class="w-10">
                        <span class="fa fa-user"></span>
                    </div>
                    <div>
                        Lastname
                    </div>
                </div>
                <div class="w-full lg:w-[1/2]">
                    <div class="mt-2 lg:mt-0 ml-10 lg:ml-0">
                        {{auth()->user()->last_name}}
                    </div>
                </div>
            </div>

            <div class="py-4 border-b lg:flex lg:space-x-4">
                <div class="w-full lg:w-[1/2] text-fade flex items-center">
                    <div class="w-10">
                        <span class="fa fa-envelope"></span>
                    </div>
                    <div>
                        Email Address
                    </div>
                </div>
                <div class="w-full lg:w-[1/2]">
                    <div class="mt-2 lg:mt-0 ml-10 lg:ml-0">
                        {{auth()->user()->email}}
                    </div>
                </div>
            </div>

            <div class="py-4 border-b lg:flex lg:space-x-4">
                <div class="w-full lg:w-[1/2] text-fade flex items-center">
                    <div class="w-10">
                        <span class="fa fa-phone"></span>
                    </div>
                    <div>
                        Phone Number
                    </div>
                </div>
                <div class="w-full lg:w-[1/2]">
                    <div class="mt-2 lg:mt-0 ml-10 lg:ml-0">
                        {{auth()->user()->phone}}
                    </div>
                </div>
            </div>

            <div class="py-4 border-b lg:flex lg:space-x-4">
                <div class="w-full lg:w-[1/2] text-fade flex items-center">
                    <div class="w-10">
                        <span class="fa fa-flag"></span>
                    </div>
                    <div>
                        Country
                    </div>
                </div>
                <div class="w-full lg:w-[1/2]">
                    <div class="mt-2 lg:mt-0 ml-10 lg:ml-0">
                        {{auth()->user()->country->name}}
                    </div>
                </div>
            </div>


        </div>
    </div>


    <div>
        <div class="p-4 font-bold bg-gray-200">
            Security Information
        </div>
        <div class="border p-8 space-y-4">
            <div>
                Change the login password for accessing your account
            </div>
            <div class="mt-10">
                <a href="/account/settings/change-password" class="btn btn-tdhh-orange btn-md">
                    Change Password
                    <span class="fa fa-angle-right ml-2"></span>
                </a>
            </div>
        </div>
    </div>


</div>
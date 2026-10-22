<div class="p-4 mt-8 border">
    <div class="text-xs">
        Property Owner
    </div>
    <div class="mt-4 mb-4">
        <div class="flex space-x-2">
            <div class="w-12 h-12 flex bg-black rounded-lg overflow-hidden">
                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
            </div>
            <div class="my-auto">
                <div class="font-bold">
                    {{$property->partner->first_name}} {{$property->partner->last_name}}
                    <span class="fa fa-check-circle text-green-500"></span>
                </div>
                <div class="text-fade text-xs">
                    Member since {{$property->partner->created_at->format('M Y')}}
                </div>
            </div>
        </div>
    </div>
    {{-- <div>
        <a href="" class="btn btn-transparent-black btn-block">
            View Profile
        </a>
    </div> --}}
</div>
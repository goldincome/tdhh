@isset($property)
    <a href="{{route('front.stays.show', ['area' => $property->area_slug ?? 'london', 'slug' => $property->slug ?? $property->reference])}}?check_in_date={{$data->check_in_date??now()->addDays(3)}}&check_out_date={{$data->check_out_date??now()->addDays(6)}}&num_of_adult={{$data->num_of_adult?? 1}}" class="w-full max-w-md mx-auto block group shadow-lg rounded-lg overflow-hidden border-b-8 border-gray-400 hover:border-tdhh-orange">

        <div class="h-48 bg-black relative overflow-hidden">
            <img src={{$property->cover_image->getUrl()}} alt="tdhh" class="object-cover opacity-80 transform animate group-hover:scale-125" />
        </div>

        <div class="p-6 border-l border-r border-gray-200">
            <div class="font-bold group-hover:underline ellipsis-2-lines">
                {{$property->name}}
                {{-- Beautiful Pinnacle Port condo w/ pools and basketball court right next to beach --}}
            </div>

            <div class="text-fade mt-1 text-sm ellipsis">
                <span class="fa fa-map-marker mr-1"></span>
                {{$property->address}}
            </div>

            <div class="mt-4 flex justify-between">
                <div class="flex space-x-4">
                    <div class="flex space-x-1">
                        <span class="fa fa-bed my-auto"></span>
                        <span class="my-auto">{{$property->bedroom_qty}} </span>
                    </div>
                    <div class="flex space-x-1">
                        <span class="fa fa-shower my-auto"></span>
                        <span class="my-auto"> {{$property->bathroom_qty}}</span>
                    </div>
                </div>
                <div>
                    <span class="text-tdhh-orange text-lg font-bold">
                        {{currency_convert($property->todayPrice,$property->country->code,currency()->getUserCurrency())}}
                    </span>
                    <span class="text-black text-xs">
                        /night
                    </span>
                </div>
            </div>
        </div>

    </a>
@endisset

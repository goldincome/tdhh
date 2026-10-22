@extends('layouts.admin')


@section('title')
    TDHH Properties
@endsection


@section('css')
    <style>
        .admin-sidebar-property{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('js')
    <script>

    </script>
@endsection


@section('content')

    <div class="container">


        <div class="page-title-account">
            <div>
                TDHH Properties
            </div>
            <div class="pull-right">

            </div>
        </div>


        <div>

            <div class="tabs">
                <div class="grid grid-cols-2">
                    <a href="/admin/property" class="active">
                        All Properties
                    </a>
                    <a href="/admin/property?status=active">
                        Active Properties
                    </a>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Property Info</th>
                            <th style="min-width: 150px;">Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties as $property)
                            <tr>
                                <td>
                                    {{$loop->iteration}}
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="/admin/property/{{$property->id}}" target="_blank" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                            <img src="{{asset($property->cover_image->getUrl())}}" alt="tdhh" class="object-cover m-auto" />
                                        </a>
                                        <div class="md:my-auto">
                                            <div>
                                                <a href="/admin/property/{{$property->id}}" target="_blank"  class="text-fade hover:underline">
                                                    #{{$property->reference}}
                                                </a>
                                                <a href="/admin/property/{{$property->id}}" target="_blank"  class="font-bold hover:underline ellipsis-2-lines">
                                                    {{$property->name}}
                                                </a>
                                                <div class="mt-1 text-fade text-xs">
                                                    <span class="fa fa-map-marker mr-1"></span>
                                                    {{$property->city_name}} {{$property->state_name}} {{$property->country_name}}
                                                </div>
                                            </div>
                                            <div class="mt-1 flex space-x-4 text-sm">
                                                <div>
                                                    <span class="fa fa-bed"></span>
                                                    {{$property->bedroom_qty}}
                                                </div>
                                                <div>
                                                    <span class="fa fa-shower"></span>
                                                    {{$property->bathroom_qty}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        Created
                                        <div class="font-bold">
                                            {{$property->created_at->format('d M, Y')}}
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        Last updated
                                        <div class="font-bold">
                                            {{$property->updated_at->format('d M, Y')}}
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="label label-gray">
                                        {{$property->status->name}}  {{$property->status_id}}
                                    </div>
                                </td>

                                <td>
                                    <a href="{{route('admin.property.show', $property->id)}}" class="btn btn-transparent-black btn-sm">View</a>
                                    {{--
                                    <div>
                                        <button id="dropdownButton{{$property->id}}" data-dropdown-toggle="dropdown-{{$property->id}}" class="dropdown" type="button">
                                            Option
                                            <span class="fa fa-angle-down ml-2"></span>
                                        </button>
                                        <div id="dropdown-{{$property->id}}" class="dropdown-menu-nav hidden" >
                                            <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                <li>
                                                    <a href="/property/{{$property->id}}" target="_blank" class="dropdown-menu-option">View property</a>
                                                </li>
                                                <form method="POST" action="{{route('admin.disable-enable-property', $property->id)}}">
                                                    @csrf
                                                    <li>
                                                        <button type="submit" class="dropdown-menu-option w-full text-left"
                                                            onclick="return confirm('Are you sure you want to disable this property? \nOnce done, customers will not be able to book this property. You can re-activate the property anytime.');">
                                                            Disable
                                                        </button>
                                                    </li>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                    --}}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No properties yet
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection


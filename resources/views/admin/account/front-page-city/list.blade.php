@extends('layouts.admin')


@section('title')
    Front Page Cities
@endsection


@section('css')
    <style>
        .admin-sidebar-finance{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">


        <div class="page-title-account">
            <div>
                Front Page Cities
            </div>
            <div class="pull-right">
            </div>
        </div>


        <div>
            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Created On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cities  as $city)

                            <tr>

                                <td>
                                   {{$loop->iteration}}
                                </td>

                                <td>
                                   {{$city->state->name}}
                                </td>

                                <td>
                                   {{$city->status ? 'active': 'disable'}}
                                </td>

                                <td>
                                    <div class="h-12 w-20 bg-black relative overflow-hidden">
                                        <img src="{{asset($city->coverImage)}}" alt="">
                                    </div>
                                </td>
                                <td>
                                   {{$city->created_at}}
                                </td>

                                <td>
                                    <div>
                                        <button id="dropdownButton{{$city->id}}" data-dropdown-toggle="dropdown-{{$city->id}}" class="dropdown" type="button">
                                            Option
                                            <span class="fa fa-angle-down ml-2"></span>
                                        </button>
                                        <div id="dropdown-{{$city->id}}" class="dropdown-menu-nav hidden" >
                                            <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                <li>
                                                    <a href="{{route('admin.fpc.edit', $city->id)}}" class="dropdown-menu-option">Edit</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No city yet
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


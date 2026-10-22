@extends('layouts.admin')


@section('title')
    States
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
                States
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
                            <th>Country</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($states  as $state)

                            <tr>

                                <td>
                                   {{$loop->iteration}}
                                </td>
                                <td>
                                   {{$state->name}}
                                </td>

                                <td>
                                   {{$state->country->name}}
                                </td>

                                 <td>
                                    <div>
                                        <button id="dropdownButton{{$state->id}}" data-dropdown-toggle="dropdown-{{$state->id}}" class="dropdown" type="button">
                                            Option
                                            <span class="fa fa-angle-down ml-2"></span>
                                        </button>

                                        <div id="dropdown-{{$state->id}}" class="dropdown-menu-nav hidden" >
                                            <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                <form method="POST" action="{{route('admin.addToFPC', $state->id)}}">
                                                    @csrf
                                                    <li>
                                                        <button type="submit" class="dropdown-menu-option w-full text-left"
                                                            onclick="return confirm('Are you sure you want to disable this property? \nOnce done, customers will not be able to book this property. You can re-activate the property anytime.');">
                                                            Add to FPC
                                                        </button>
                                                    </li>
                                                </form>
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


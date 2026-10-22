@extends('layouts.admin')


@section('title')
    General Setting
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
                General Settings
            </div>
            <div class="pull-right">
                <a class="btn btn-tdhh-orange" href="{{route('admin.general-setting.create')}}">Create Setting</a>
            </div>
        </div>
        <div>
            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($settings  as $setting)
                            <tr>
                                <td>
                                   {{$loop->iteration}}
                                </td>
                                <td>
                                   {{$setting->name}}
                                </td>
                                <td>
                                   {{$setting->value}}
                                </td>
                                <td>
                                   {{$setting->description}}
                                </td>
                                 <td>
                                    <div>
                                        <button id="dropdownButton{{$setting->id}}" data-dropdown-toggle="dropdown-{{$setting->id}}" class="dropdown" type="button">
                                            Option
                                            <span class="fa fa-angle-down ml-2"></span>
                                        </button>
                                        <div id="dropdown-{{$setting->id}}" class="dropdown-menu-nav hidden" >
                                            <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                <li>
                                                    <a href="{{route('admin.general-setting.edit', $setting->id)}}" class="dropdown-menu-option">Edit</a>
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
                                            No settings yet
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


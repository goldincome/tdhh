@extends('layouts.admin')


@section('title')
    Admin Settings
@endsection


@section('css')
    <style>
        .admin-sidebar-admins{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">
        <div class="page-title-account">
            <div>
                Administrators
            </div>
            <div class="pull-right">
                <a class="btn btn-tdhh-orange btn-sm" href="/admin/admins/create">Create new admin</a>
            </div>
        </div>
        <div>
            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Info</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Date added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                <div>
                                    Firstname Lastname
                                </div>
                                <div class="text-fade">
                                    email@gmail.com
                                </div>
                                <div class="text-fade">
                                    08010101010
                                </div>
                            </td>
                            <td>
                                General Admin
                            </td>
                            <td>
                                <div class="label label-green">
                                    Active
                                </div>
                                {{-- 
                                <div class="label label-red">
                                    Inactive
                                </div>
                                    --}}
                            </td>
                            <td>
                                Sunday, 09 April, 2023
                            </td>
                                <td>
                                <div>
                                    <button id="dropdownButton-1" data-dropdown-toggle="dropdown-1" class="dropdown" type="button">
                                        Option
                                        <span class="fa fa-angle-down ml-2"></span>
                                    </button>
                                    <div id="dropdown-1" class="dropdown-menu-nav hidden" >
                                        <ul class="py-1" aria-labelledby="dropdownButton-1">
                                            <li>
                                                <a href="/admin/admins/edit/12345" class="dropdown-menu-option">Edit</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-menu-option">Disable</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                <div>
                                    Firstname Lastname
                                </div>
                                <div class="text-fade">
                                    email@gmail.com
                                </div>
                                <div class="text-fade">
                                    08010101010
                                </div>
                            </td>
                            <td>
                                Financial Admin
                            </td>
                            <td>
                                <div class="label label-green">
                                    Active
                                </div>
                                {{-- 
                                <div class="label label-red">
                                    Inactive
                                </div>
                                    --}}
                            </td>
                            <td>
                                Sunday, 09 April, 2023
                            </td>
                                <td>
                                <div>
                                    <button id="dropdownButton-1" data-dropdown-toggle="dropdown-1" class="dropdown" type="button">
                                        Option
                                        <span class="fa fa-angle-down ml-2"></span>
                                    </button>
                                    <div id="dropdown-1" class="dropdown-menu-nav hidden" >
                                        <ul class="py-1" aria-labelledby="dropdownButton-1">
                                            <li>
                                                <a href="/admin/admins/edit/12345" class="dropdown-menu-option">Edit</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-menu-option">Disable</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        
                        {{-- 
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No admins yet
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                         --}}

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


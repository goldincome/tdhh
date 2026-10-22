@extends('layouts.admin')


@section('title')
    Currency
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
                Currency
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
                            <th>Name</th>
                            <th>Symbol</th>
                            <th>Format</th>
                            <th>Exchange Rate</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($currencies  as $currency)

                            <tr>

                                <td>
                                   {{$loop->iteration}}
                                </td>
                                <td>
                                   {{$currency->name}}
                                </td>
                                <td>
                                   {{$currency->symbol}}
                                </td>
                                <td>
                                   {{$currency->format}}
                                </td>
                                <td>
                                   {{$currency->exchange_rate}}
                                </td>
                                <td>
                                   {{$currency->active}}
                                </td>
                                <td>
                                    <div>
                                        <button id="dropdownButton{{$currency->id}}" data-dropdown-toggle="dropdown-{{$currency->id}}" class="dropdown" type="button">
                                            Option
                                            <span class="fa fa-angle-down ml-2"></span>
                                        </button>
                                        <div id="dropdown-{{$currency->id}}" class="dropdown-menu-nav hidden" >
                                            <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                <li>
                                                    <a href="{{route('admin.currency.edit', $currency->id)}}" class="dropdown-menu-option">Edit</a>
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
                                            No data yet
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


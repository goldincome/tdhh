@extends('layouts.admin')


@section('title')
    Bank Accounts
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
                Bank Accounts
            </div>
            <div class="pull-right">
            </div>
        </div>


        <div>
             <div class="float-right  my-3">
                <a class="btn btn-tdhh-orange" href="{{route('admin.bank-account.create')}}">Bank Account</a>
            </div>
            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Country Name</th>
                            <th>Bank Name</th>
                            <th>Account Name</th>
                            <th>Account Number</th>
                            <th>Currency Code</th>
                            <th>Bank Logo</th>
                            <th>Routing Number</th>
                            <th>Swift Code</th>
                            <th>Status</th>
                            <th>Added On</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts  as $account)

                            <tr>

                                <td>
                                   {{$loop->iteration}}
                                </td>

                                <td>
                                   {{$account->country->name}}
                                </td>

                                <td>
                                   {{$account->bank_name}}
                                </td>

                                <td>
                                   {{$account->account_name}}
                                </td>

                                <td>
                                   {{$account->account_number}}
                                </td>

                                <td>
                                   {{$account->currency_code}}
                                </td>

                                <td>
                                   {{$account->bank_logo}}
                                </td>

                                <td>
                                   {{$account->routing_number}}
                                </td>

                                <td>
                                   {{$account->swift_code}}
                                </td>

                                <td>
                                   {{$account->status}}
                                </td>
                                <td>
                                   {{$account->created_at}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No bank account yet
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


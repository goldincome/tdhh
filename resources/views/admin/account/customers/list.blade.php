@extends('layouts.admin')


@section('title')
    Customers
@endsection


@section('css')
    <style>
        .admin-sidebar-customers{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">
        <div class="page-title-account">
            <div>
                Customers
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
                            <th>Info</th>
                            <th>Status</th>
                            <th>Date registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>
                                    {{$loop->iteration}}
                                </td>
                                <td>
                                    <div>
                                        <a href="{{route('admin.customer.show', $customer->id)}}" class="font-bold hover:underline">
                                            {{$customer->fullName}}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{route('admin.customer.show', $customer->id)}}" class="text-fade hover:underline">
                                          {{$customer->email}}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{route('admin.customer.show', $customer->id)}}" class="text-fade hover:underline">
                                            {{$customer->phone}}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->status_id)
                                        <div class="label label-green">
                                            Active
                                        </div>
                                    @else
                                        <div class="label label-red">
                                            Inactive
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{$customer->created_at->format('d M Y')}}
                                </td>
                                <td>
                                    <a href="{{route('admin.customer.show', $customer->id)}}" class="btn btn-transparent-black btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No customers yet
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


@extends('layouts.admin')


@section('title')
    Partners
@endsection


@section('css')
    <style>
        .admin-sidebar-partners{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">
        <div class="page-title-account">
            <div>
                Partners
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
                            <th>No. of properties</th>
                            <th>Status</th>
                            <th>Date registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($partners as $partner)
                            <tr>
                                <td>
                                    {{$loop->iteration}}
                                </td>
                                <td>
                                    <div>
                                        <a href="{{route('admin.partner.show', $partner->id)}}" class="font-bold hover:underline">
                                            {{$partner->fullName}}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{route('admin.partner.show', $partner->id)}}" class="text-fade hover:underline">
                                           {{$partner->email}}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{route('admin.partner.show', $partner->id)}}" class="text-fade hover:underline">
                                           {{$partner->phone}}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    {{$partner->properties->count()}} properties
                                </td>
                                <td>
                                     @if($partner->status)
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
                                    {{$partner->created_at->format('M d, Y')}}
                                </td>
                                <td>
                                    <a href="{{route('admin.partner.show', $partner->id)}}" class="btn btn-transparent-black btn-sm">View</a>
                                </td>
                            </tr>
                        @empty

                        @endforelse


                        {{--
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No partners yet
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


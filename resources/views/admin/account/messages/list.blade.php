@extends('layouts.admin')


@section('title')
    Messages
@endsection


@section('css')
    <style>
        .admin-sidebar-messages{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">

        <div class="page-title-account">
            <div>
                All Messages
            </div>
            <div>
                <form action="">
                    <label class="relative block">
                        <div class="pointer-events-none w-10 h-full absolute flex">
                            <i class="fas fa-search m-auto"></i>
                        </div>
                        <input type="search" name="search" placeholder="Search messages" class="form-input w-full" style="padding-left: 40px !important;">
                   </label>
                </form>
            </div>
        </div>


        <div>

            <div class="table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>Date Registered</th>
                            <th style="min-width: 200px;">Message</th>
                            <th>User Info</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $message)
                        <tr>
                            <td>
                               {{$message->created_at->format('M d, Y')}}
                            </td>

                            <td>
                                <div>
                                    <div class="font-bold">
                                       {{$message->subject}}
                                    </div>
                                    <div class="text-fade ellipsis-2-lines">
                                        {{$message->message}}
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div>
                                    <div class="font-bold">
                                        {{$message->name}}
                                    </div>
                                    <div class="text-fade">
                                        <div>
                                            {{$message->email}}
                                        </div>
                                        <div>
                                            {{$message->phone}}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($message->read_status)
                                    <div class="label label-green">
                                        Read
                                    </div>
                                @else
                                    <div class="label label-red">
                                        Unread
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <a href="{{route('admin.viewMessage', $message)}}" class="btn btn-nin-green btn-sm">
                                        <span class="fa fa-envelope mr-2"></span>
                                        Open
                                    </a>
                                </div>
                                <div class="mt-1">
                                    <form action="">
                                        <button type="button" class="btn btn-red btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No results found
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{$messages->links()}}

        </div>

    </div>
@endsection

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
                Message Details
            </div>
            <div class="pull-right flex space-x-4">
                <form action="{{route('admin.markMessageAsUnread', $message)}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-transparent-black btn-sm">
                        Mark as unread
                    </button>
                </form>
                <form action="">
                    <button type="submit" class="btn btn-red btn-sm">
                        Delete
                    </button>
                </form>
                <a href="/admin/messages" class="btn btn-nin-green btn-sm">
                    <span class="fa fa-angle-left mr-2"></span>
                    Back
                </a>
            </div>
        </div>


        <div class="bg-white p-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                <div>
                    <div class="p-4 bg-nin-green bg-opacity-20">
                        <span class="fa fa-user mr-2"></span>
                        User Details
                    </div>
                    <div class="border border-t-0 border-nin-green border-opacity-40">
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Name:
                            </div>
                            <div class="mt-1">
                               {{$message->name}}
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Email Address:
                            </div>
                            <div class="mt-1">
                                {{$message->email}}
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Phone Number:
                            </div>
                            <div class="mt-1">
                                {{$message->phone}}
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="p-4 bg-nin-green bg-opacity-20">
                        <span class="fa fa-envelope mr-2"></span>
                        Message Details
                    </div>
                    <div class="border border-t-0 border-nin-green border-opacity-40">
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Order Number:
                            </div>
                            <div class="mt-1">
                                #1234567890
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Category:
                            </div>
                            <div class="mt-1">
                                Order Confirmation
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Subject:
                            </div>
                            <div class="mt-1">
                                {{$message->subject}}
                            </div>
                        </div>
                        <div class="px-4 py-4">
                            <div class="font-bold">
                                Message:
                            </div>
                            <div class="mt-1">
                                {!! nl2br( $message->message)!!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

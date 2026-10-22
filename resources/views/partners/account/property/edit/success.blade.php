@extends('layouts.partner')


@section('title')
    Edit property - #09390309323223
@endsection


@section('css')
    <style>
        .admin-sidebar-property{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">

        <div class="page-title-account">
            <div>
                Edit property
                <a href="/partners/property/12345" target="_blank" class="hover:underline font-montserrat text-sm text-tdhh-orange">
                    #09390309323223
                </a>
            </div>
            <div class="pull-right">
                <a href="/partners/dashboard/property" type="button" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="mt-4">

            <div class="tabs">
                <div class="grid grid-cols-4">
                    <a href="/partners/property/edit/123" class="active">
                        General Info
                    </a>
                    <a href="/partners/property/edit/123/options" class="active">
                        Property Options
                    </a>
                    <a href="/partners/property/edit/123/room" class="active">
                        Room Details
                    </a>
                    <a href="/partners/property/edit/123/preview" class="active">
                        Property Preview
                    </a>
                </div>
            </div>

            <div class="mt-1 p-8 min-h-[400px] bg-white">
                <div class="max-w-xl py-10 mx-auto">
                
                    <div class="mx-auto text-center">
                        <div class="h-32">
                            <img src={{asset('img/icons/status-success.png')}} alt="tdhh" class="object-contain m-auto" />
                        </div>
                        <div class="mt-4 font-semibold text-2xl sm:text-3xl">
                            Property edited successfully
                        </div>
                        <div class="mt-4 text-gray-500">
                            Please wait while we review and approve your property. An email will be sent to you once the approval phase is completed.
                        </div>
                        <div class="mt-4">
                            <a href="/partners/dashboard/property" class="btn btn-orange mx-auto">
                                My properties
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>

    </div>
@endsection


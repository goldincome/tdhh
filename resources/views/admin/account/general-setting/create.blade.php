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
                Create General Settings
            </div>
            <div class="pull-right">
            </div>
        </div>

        <div class="mt-1 p-8 min-h-[400px] bg-white">
            <form method="post" action="{{route('admin.general-setting.store')}}" class="max-w-xl mx-auto" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>
                        Setting name
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="name" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                        Value
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="value" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                        Description
                        <span class="form-input-required">*</span>
                    </label>
                    <textarea name="description" class="form-input" required></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange"> Submit </button>
                </div>
            </form>
        </div>
    </div>
@endsection


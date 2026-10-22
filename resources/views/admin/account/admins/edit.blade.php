@extends('layouts.admin')


@section('title')
    Edit Admin
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
                Edit Admin
            </div>
            <div class="pull-right">
                <a href="{{ url()->previous() }}" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>

        <div class="mt-1 p-8 min-h-[400px] bg-white">
            <form method="post" action="" class="max-w-xl mx-auto" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-2 gap-x-6">
                    <div class="form-group">
                        <label>
                            Firstname
                            <span class="form-input-required">*</span>
                        </label>
                        <input type="text" name="name" class="form-input" required/>
                    </div>

                    <div class="form-group">
                        <label>
                            Lastname
                            <span class="form-input-required">*</span>
                        </label>
                        <input type="text" name="name" class="form-input" required/>
                    </div>

                    <div class="form-group">
                        <label>
                            Email Address
                            <span class="form-input-required">*</span>
                        </label>
                        <input type="email" name="email" class="form-input" required/>
                    </div>

                    <div class="form-group">
                        <label>
                            Phone number
                            <span class="form-input-required">*</span>
                        </label>
                        <input type="tel" name="tel" class="form-input" required/>
                    </div>

                    <div class="form-group">
                        <label>
                            Role
                            <span class="form-input-required">*</span>
                        </label>
                        <select name="role" id="" class="form-input" required>
                            <option value="">Select</option>
                            <option value="">General Admin</option>
                            <option value="">Financial Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            Status
                            <span class="form-input-required">*</span>
                        </label>
                        <select name="status" id="" class="form-input" required>
                            <option value="">Select</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange"> Save changes </button>
                </div>
            </form>
        </div>
    </div>
@endsection


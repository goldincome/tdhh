@extends('layouts.admin')


@section('title')
    Edit fpc
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
                Edit Front Page City
            </div>
            <div class="pull-right">
            </div>
        </div>

        <div class="mt-1 p-8 min-h-[400px] bg-white">
            <form method="post" action="{{route('admin.fpc.update', $fpc->id)}}" class="max-w-xl mx-auto" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>
                        City Name
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" disabled value="{{$fpc->state->name}}" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                        Cover Image
                        <span class="form-input-required">*</span>
                    </label>
                   <input type="file" src="" name="cover_image" accept="image/png, image/gif, image/jpeg" class="form-input">
                </div>

                <div class="form-group">
                    <label>
                        Status
                        <span class="form-input-required">*</span>
                    </label>
                    <select name="status" class="form-input">
                        <option value="1" @if($fpc->status == 1 )selected @endif > Active</option>
                        <option value="0" @if($fpc->status == 0 )selected @endif>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange"> Submit </button>
                </div>
            </form>
        </div>
    </div>
@endsection


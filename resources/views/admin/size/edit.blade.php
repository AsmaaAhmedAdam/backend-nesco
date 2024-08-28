@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('main_title') {{ trans('home.edit_size') }} @endsection

@section('top_title') {{ trans('home.edit_size') }}  @endsection

@section('header')
    <style>
        .card-body .col-sm-12 , .card-body .col-sm-6, .card-body .col-sm-4 { margin-bottom: 20px }

        th,td {
            text-align: center !important
        }

    </style>
@endsection


@section('content')

@include('flash-message')


<!-- Start Row -->
<div class="row">

   <div class="col-md-12">

      <!--begin::Card-->
      <div class="card card-custom">

         <div class="card-header">
            <h3 class="card-title">
                {{ trans('home.edit_size') }}
            </h3>
         </div>

        <div class="card-body">
            <!--begin::Form-->
            {!! Form::model($Item, [ 'route' => ['admin_panel.size.update' , $Item->id ] , 'method' => 'patch', 'files' => true, 'role'=>'form','id'=>'edit' ]) !!}

                <input type="hidden" name="id" value="{{$Item->id}}">

                <div class="row">
                    <div class="col-sm-12 {{ $errors->has('title') ? ' has-error' : '' }}">
                        <label> {{ trans('home.name') }}  <span class="text-danger">*</span> </label>

                        <input type="text" name="title" class="form-control m-input" required="required" value="{{ $Item->title }}" placeholder="{{ trans('home.name') }}">

                        @if ($errors->has('title'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('title') }} </strong>
                                </span>
                        @endif
                    </div>

                </div>


                <button type="submit" form="edit" class="btn btn-primary mr-2">{{ trans('home.update') }}</button>


            {!! Form::close() !!}
            <!--end::Form-->
        </div>







      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection



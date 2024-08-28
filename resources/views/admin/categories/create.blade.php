@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


@extends('admin.layouts.master')

@section('main_title')   {{ trans('home.add_cat') }}   @endsection

@section('top_title') {{ trans('home.add_cat') }}   @endsection

@section('header')
    <style>
        .card-body .col-sm-12 , .card-body .col-sm-6 { margin-bottom: 20px }
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
            <h3 class="card-title">  {{ trans('home.add_cat') }}  </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/categories", 'role'=>'form','files' => true,'id'=>'add','method'=>'post']) !!}

            <div class="card-body">

                <div class="row">

                    <div class="col-sm-6 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.en_name') }}    <span class="text-danger">*</span> </label>

                        <input type="text" name="en_title" class="form-control m-input" required="required" value="{{ old('en_title') }}" placeholder="  {{ trans('home.en_name') }}  ">

                        @if ($errors->has('en_title'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('en_title') }} </strong>
                             </span>
                        @endif
                    </div>


                    <div class="col-sm-6 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.ar_name') }}    <span class="text-danger">*</span> </label>

                        <input type="text" name="ar_title" class="form-control m-input" required="required" value="{{ old('ar_title') }}" placeholder="  {{ trans('home.ar_name') }}  ">

                        @if ($errors->has('ar_title'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('ar_title') }} </strong>
                             </span>
                        @endif
                    </div>

                    <div class="col-sm-12 {{ $errors->has('pic') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.pic') }} <span class="text-danger">*</span> </label>
                        <div class="custom-file">
                           <input type="file" class="custom-file-input" required name="pic" accept="image/*">
                           <label class="custom-file-label"> {{ trans('home.pic_msg') }}  </label>
                        </div>
                        @if ($errors->has('pic'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('pic') }}</strong>
                            </span>
                        @endif
                    </div>


                </div>



            </div>

            <div class="card-footer">
               <button type="submit" form="add" class="btn btn-primary mr-2">{{ trans('home.save') }} </button>
            </div>

        {!! Form::close() !!}
         <!--end::Form-->


      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection

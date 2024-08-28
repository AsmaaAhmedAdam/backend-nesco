@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);

    $setting = App\Models\Setting::first();
@endphp


@extends('admin.layouts.master')

@section('main_title') {{ trans('home.add_user') }} @endsection

@section('top_title')  {{ trans('home.add_user') }}  @endsection

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
            <h3 class="card-title">
                {{ trans('home.add_user') }}
            </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/users", 'role'=>'form','id'=>'add', 'files' => true,'method'=>'post']) !!}

            <div class="card-body">

                <div class="row">

                    <div class="col-lg-6 col-sm-6 {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.name') }}  <span class="text-danger">*</span>  </label>
                        <input type="text" name="name" class="form-control m-input" required="required" value="{{ old('name') }}" placeholder="  {{ trans('home.name') }}   ">
                        @if ($errors->has('name'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('name') }} </strong>
                             </span>
                        @endif
                    </div>



                    <div class="col-lg-6 col-sm-6 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.email') }} <span class="text-danger">*</span>   </label>
                        <input type="email" name="email" class="form-control m-input" required="required" value="{{ old('email') }}" placeholder="  {{ trans('home.email') }}   ">
                        @if ($errors->has('email'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('email') }} </strong>
                             </span>
                        @endif
                    </div>


                    <div class="col-lg-6 col-sm-6 {{ $errors->has('mobile') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.mobile') }} <span class="text-danger">*</span>   </label>
                        <input type="text" name="mobile" class="form-control m-input" required="required" value="{{ old('mobile') }}" placeholder=" {{ trans('home.mobile') }} ">
                        @if ($errors->has('mobile'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('mobile') }} </strong>
                             </span>
                        @endif
                    </div>


                    <div class="col-lg-6 col-sm-6  {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label> {{ trans('home.password') }}  </label>
                        <input type="text" name="password" class="form-control m-input" value="" required placeholder="{{ trans('home.password') }}">
                        @if ($errors->has('password'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('password') }} </strong>
                             </span>
                        @endif
                    </div>

                </div>

            </div>

            <div class="card-footer">
               <button type="submit" form="add" class="btn btn-primary mr-2">{{ trans('home.save') }}</button>
            </div>

        {!! Form::close() !!}
         <!--end::Form-->


      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection

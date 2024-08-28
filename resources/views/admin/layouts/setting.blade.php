@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{ trans('home.setting') }}  @endsection

@section('main_title')  {{ trans('home.setting') }}  @endsection


@section('header')
    <style>
        .card-body .col-sm-12 , .card-body .col-sm-6, .card-body .col-sm-4 { margin-bottom: 20px }
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
            <h3 class="card-title"> {{ trans('home.setting') }} </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/setting", 'files'=> true,'role'=>'form','id'=>'update','method'=>'post']) !!}

            <div class="card-body">


               <div class="row">

                  <div class="col-sm-4 {{ $errors->has('website_name') ? ' has-error' : '' }}">
                     <label> {{ trans('home.website_name') }} </label>
                     <input type="text" name="website_name" class="form-control m-input" required="required" value="{{ $Setting->website_name }}" placeholder="  {{ trans('home.website_name') }}  ">
                     @if ($errors->has('website_name'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('website_name') }}</strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-4 {{ $errors->has('mobile') ? ' has-error' : '' }}">
                     <label> {{ trans('home.mobile') }} </label>
                     <input type="text" name="mobile"  onkeypress="return isNumberKey(event)" class="form-control m-input" required="required" value="{{ $Setting->mobile }}" placeholder=" {{ trans('home.mobile') }} ">
                     @if ($errors->has('mobile'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('mobile') }}</strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-4 {{ $errors->has('whatsapp') ? ' has-error' : '' }}">
                     <label> {{ trans('home.whatsapp') }} </label>
                     <input type="text" name="whatsapp"  onkeypress="return isNumberKey(event)" class="form-control m-input" required="required" value="{{ $Setting->whatsapp }}" placeholder=" {{ trans('home.whatsapp') }} ">
                     @if ($errors->has('whatsapp'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('whatsapp') }}</strong>
                     </span>
                     @endif
                  </div>


                  <div class="col-sm-4 {{ $errors->has('email') ? ' has-error' : '' }}">
                     <label> {{ trans('home.email') }} </label>
                     <input type="email" name="email" class="form-control m-input" required="required" value="{{ $Setting->email }}" placeholder=" {{ trans('home.email') }} ">
                     @if ($errors->has('email'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('email') }}</strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-4 {{ $errors->has('en_address') ? ' has-error' : '' }}">
                     <label> {{ trans('home.en_address') }} </label>
                     <input type="text" name="en_address" class="form-control m-input" value="{{ $Setting->en_address }}" placeholder=" {{ trans('home.en_address') }} ">
                     @if ($errors->has('en_address'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('en_address') }}</strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-4 {{ $errors->has('ar_address') ? ' has-error' : '' }}">
                     <label> {{ trans('home.ar_address') }} </label>
                     <input type="text" name="ar_address" class="form-control m-input" value="{{ $Setting->ar_address }}" placeholder=" {{ trans('home.ar_address') }} ">
                     @if ($errors->has('ar_address'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('ar_address') }}</strong>
                     </span>
                     @endif
                  </div>



                  <div class="col-sm-4 {{ $errors->has('facebook_link') ? ' has-error' : '' }}">
                     <label>  {{ trans('home.facebook_link') }}   </label>
                     <input type="text"  name="facebook_link" class="form-control m-input" value="{{ $Setting->facebook_link }}" placeholder=" {{ trans('home.facebook_link') }} ">
                     @if ($errors->has('facebook_link'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('facebook_link') }} </strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-4 {{ $errors->has('twitter_link') ? ' has-error' : '' }}">
                     <label>  {{ trans('home.twitter_link') }} </label>
                     <input type="text" name="twitter_link" class="form-control m-input" value="{{ $Setting->twitter_link }}" placeholder=" {{ trans('home.twitter_link') }} ">
                     @if ($errors->has('twitter_link'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('twitter_link') }}</strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-4 {{ $errors->has('instgram_link') ? ' has-error' : '' }}">
                     <label> {{ trans('home.instgram_link') }} </label>
                     <input type="text"  name="instgram_link" class="form-control m-input" value="{{ $Setting->instgram_link }}" placeholder=" {{ trans('home.instgram_link') }} ">
                     @if ($errors->has('instgram_link'))
                     <span class="help-block" style="color:red">
                     <strong>{{ $errors->first('instgram_link') }} </strong>
                     </span>
                     @endif
                  </div>

                  <div class="col-sm-6 {{ $errors->has('android_link') ? ' has-error' : '' }}">
                    <label>  {{ trans('home.android_link') }} </label>
                    <input type="text" name="android_link" class="form-control m-input" required value="{{ $Setting->android_link }}" placeholder=" {{ trans('home.android_link') }} ">
                    @if ($errors->has('android_link'))
                    <span class="help-block" style="color:red">
                    <strong>{{ $errors->first('android_link') }}</strong>
                    </span>
                    @endif
                 </div>

                 <div class="col-sm-6 {{ $errors->has('ios_link') ? ' has-error' : '' }}">
                    <label> {{ trans('home.ios_link') }} </label>
                    <input type="text"  name="ios_link" class="form-control m-input" required value="{{ $Setting->ios_link }}" placeholder=" {{ trans('home.ios_link') }} ">
                    @if ($errors->has('ios_link'))
                    <span class="help-block" style="color:red">
                    <strong>{{ $errors->first('ios_link') }} </strong>
                    </span>
                    @endif
                 </div>


                </div>




            </div>

            <div class="card-footer">
               <button type="submit" form="update" class="btn btn-primary mr-2">
                  {{ trans('home.update') }}
               </button>
            </div>

        {!! Form::close() !!}
         <!--end::Form-->


      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection


@section('footer')
   <script>
      // is number //
      function isNumberKey(evt){
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
         return true;
      }
   </script>
@endsection














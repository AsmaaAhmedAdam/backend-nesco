@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('main_title') {{ trans('home.add_city') }} @endsection

@section('top_title')  {{ trans('home.add_city') }}  @endsection

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
                {{ trans('home.add_city') }}
            </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/cities", 'role'=>'form','id'=>'add', 'files' => true,'method'=>'post']) !!}

            <div class="card-body">

                <div class="row">

                    <div class="col-sm-6 {{ $errors->has('en_name') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.en_name') }}  <span class="text-danger">*</span> </label>

                        <input type="text" name="en_name" class="form-control m-input" required="required" value="{{ old('en_name') }}" placeholder=" {{ trans('home.en_name') }} ">

                        @if ($errors->has('en_name'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('en_name') }} </strong>
                             </span>
                        @endif
                    </div>

                    <div class="col-sm-6 {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.ar_name') }}  <span class="text-danger">*</span> </label>

                        <input type="text" name="ar_name" class="form-control m-input" required="required" value="{{ old('ar_name') }}" placeholder=" {{ trans('home.ar_name') }} ">

                        @if ($errors->has('ar_name'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('ar_name') }} </strong>
                             </span>
                        @endif
                    </div>

                    <div class="col-sm-12 {{ $errors->has('shipping_value') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.shipping_value') }}  </label>

                        <input type="number" min="0" name="shipping_value" onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ old('shipping_value') != null ? old('shipping_value') : 0 }}" placeholder=" {{ trans('home.shipping_value') }} ">

                        @if ($errors->has('shipping_value'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('shipping_value') }} </strong>
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

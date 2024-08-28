@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


@extends('admin.layouts.master')

@section('top_title') {{ trans('home.coupon') }}  @endsection

@section('main_title') {{ trans('home.add-coupon') }}  @endsection

@section('header')

    <link href="{{asset('datepicker')}}/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

    <style>

        .card-body .col-sm-12 , .card-body .col-sm-6 { margin-bottom: 20px }

        .un_active { display: none }

        .active { display: block }

        @if($lang == 'ar')

        .datepicker { float: right !important }
        /*.datepicker.dropdown-menu { right:auto !important }*/
        .datepicker.dropdown-menu { right:24% !important }

        @endif


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
            <h3 class="card-title">{{ trans('home.add-coupon') }} </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/coupon", 'role'=>'form','id'=>'add', 'files' => true,'method'=>'post']) !!}

            <div class="card-body">

                <div class="row">

                    <div class="col-lg-6 col-sm-6 {{ $errors->has('title') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.name') }} </label>
                        <input type="text" name="title" required class="form-control m-input" value="{{ old('title') }}" placeholder=" {{ trans('home.name') }} ">
                        @if ($errors->has('title'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('title') }} </strong>
                            </span>
                        @endif
                     </div>

                     <div class="col-lg-6 col-sm-6 {{ $errors->has('value_type') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.value-type') }} </label>
                        <select name="value_type" id="value_type" required class="form-control">
                            <option value="" disabled selected="true"> {{ trans('home.value-type') }}  </option>
                            <option value="value" @if(old('value_type') == 'value') {{ 'selected' }} @endif> {{ trans('home.value') }}  ( {{ trans('home.aed') }} ) </option>
                            <option value="percentage" @if(old('value_type') == 'percentage') {{ 'selected' }} @endif> {{ trans('home.percentage') }}  ( % ) </option>
                        </select>
                        @if ($errors->has('value_type'))
                         <span class="help-block" style="color:red">
                             <strong>{{ $errors->first('value_type') }}</strong>
                         </span>
                        @endif
                     </div>


                     <div class="col-lg-12 col-sm-12 {{ $errors->has('value') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.value') }} </label>
                        <input type="text" name="value" required class="form-control m-input"   value="{{ old('value') }}" placeholder=" {{ trans('home.value') }} ">
                        @if ($errors->has('value'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('value') }} </strong>
                            </span>
                        @endif
                     </div>


                     <div class="col-lg-12 col-sm-12 {{ $errors->has('value') ? ' has-error' : '' }}">
                        <input id="end_date" type="checkbox" value="1" name="date_type" style="cursor: pointer">
                        <label for="end_date" style="cursor: pointer">  {{ trans('home.have-end-date') }} </label>

                     </div>


                     <div class="col-lg-12 col-sm-12 date un_active {{ $errors->has('date') ? ' has-error' : '' }}" style="margin-bottom: 20px;">
                        <label> {{ trans('home.end-date') }} </label>
                        <label> </label>
                        <input type="text" id="m_datepicker_1"  name="date" readonly  class="form-control m-input" value="{{ old('date') }}" placeholder=" {{ trans('home.end-date') }}   .....  " />
                        @if($errors->has('date'))
                        <span class="help-block" style="color: red;">
                        <strong>{{ $errors->first('date') }} </strong>
                        </span>
                        @endif
                     </div>



                </div>

            </div>

            <div class="card-footer">
               <button type="submit" form="add" class="btn btn-primary mr-2">
                {{ trans('home.save') }}
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

         var field = document.querySelector('[name="title"]');

         field.addEventListener('keypress', function ( event ) {
            var key = event.keyCode;
            if (key === 32) {
               event.preventDefault();
            }
         });

      </script>


     <script>
         $(document).ready(function() {

            $('#end_date').on('click',function () {
               if ($(this).is(':checked')) {
                   $('.date').addClass('active').removeClass('un_active');
               } else {
                   $('.date').addClass('un_active').removeClass('active');
               }
           });

         });
     </script>


     <script src="{{asset('datepicker')}}/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
     <script src="{{asset('datepicker')}}/js/bootstrap-datepicker.init.js" type="text/javascript"></script>


     <script>
         $(document).ready(function() {

            $('#m_datepicker_1').datepicker({
               format: 'yyyy-mm-dd',
               todayHighlight: true,
               orientation: 'bottom left',
            });

         });
      </script>

@endsection


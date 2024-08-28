@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


@extends('admin.layouts.master')

@section('top_title') {{ trans('home.policy') }}  @endsection

@section('main_title')  {{ trans('home.policy') }}  @endsection


@section('header')
    <style>

        .card-body .col-sm-12 , .card-body .col-sm-4, .card-body .col-sm-6 { margin-bottom: 20px }

        @if($lang == 'ar')
        .bootstrap-timepicker-widget table {
            direction: ltr;
        }
        @endif
    </style>
@endsection


@section('content')

@include('flash-message')


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Start Row -->
<div class="row">

   <div class="col-md-12">

      <!--begin::Card-->
      <div class="card card-custom">

         <div class="card-header">
            <h3 class="card-title"> {{ trans('home.policy') }} </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/policy", 'files'=> true,'role'=>'form','id'=>'update','method'=>'post']) !!}

            <div class="card-body">


               <div class="row">

                <div class="col-sm-6 {{ $errors->has('en_policy') ? ' has-error' : '' }}">
                    <label> {{ trans('home.en_policy') }}  <span class="text-danger">*</span>  </label>
                    <textarea name="en_policy" required class="form-control" rows="10" placeholder=" {{ trans('home.en_policy') }}  ">{{ $Setting->en_policy }}</textarea>
                    @if ($errors->has('en_policy'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('en_policy') }} </strong>
                        </span>
                    @endif
                </div>

                <div class="col-sm-6 {{ $errors->has('ar_policy') ? ' has-error' : '' }}">
                    <label> {{ trans('home.ar_policy') }}  <span class="text-danger">*</span>  </label>
                    <textarea name="ar_policy" required class="form-control" rows="10" placeholder=" {{ trans('home.ar_policy') }}  ">{{ $Setting->ar_policy }}</textarea>
                    @if ($errors->has('ar_policy'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('ar_policy') }} </strong>
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

    <script>

        // Class definition

        var KTBootstrapTimepicker = function () {

            // Private functions
            var demos = function () {

                // minimum setup
                $('#kt_timepicker_2, #kt_timepicker_3').timepicker({
                    minuteStep: 5,
                    defaultTime: '',
                    showSeconds: false,
                    showMeridian: false,
                    snapToStep: true
                });

            }

            return {
                // public functions
                init: function() {
                    demos();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTBootstrapTimepicker.init();
        });

    </script>

@endsection














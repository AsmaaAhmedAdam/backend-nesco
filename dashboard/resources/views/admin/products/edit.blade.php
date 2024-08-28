@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')


@section('main_title') {{ trans('home.edit_product') }} @endsection

@section('top_title') {{ trans('home.edit_product') }}  @endsection


@php
    $setting = App\Models\Setting::first();
@endphp

@section('header')

    <style>
        .card-body .col-sm-12 , .card-body .col-sm-6, .card-body .col-sm-4,.card-body .col-sm-3  { margin-bottom: 20px }
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
            <h3 class="card-title">
                {{ trans('home.edit_product') }}
            </h3>
         </div>


        <div class="card-body">

            @include('admin.products.edit_form')

        </div>



      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection


@Section('footer')


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

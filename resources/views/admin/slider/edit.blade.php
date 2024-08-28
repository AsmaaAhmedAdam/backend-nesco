@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


@extends('admin.layouts.master')

@section('top_title')  {{ trans('home.edit-item') }}  @endsection

@section('main_title') {{ trans('home.slider') }}   @endsection


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
                {{ trans('home.edit-item') }}
            </h3>
         </div>


         <!--begin::Form-->
         {!! Form::model($Item, [ 'route' => ['admin_panel.slider.update' , $Item->id ] , 'method' => 'patch', 'files' => true, 'role'=>'form','id'=>'edit' ]) !!}

            <div class="card-body">

                <input type="hidden" name="id" value="{{$Item->id}}">


                <div class="row">


                    <div class="col-lg-12 col-sm-12 {{ $errors->has('pic') ? ' has-error' : '' }}">
                        <label> {{ trans('home.pic') }} </label>
                        <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile1" name="pic" accept="image/*">
                        <label class="custom-file-label" for="customFile1"> {{ trans('home.pic_msg') }}  </label>
                        </div>
                        <br><br>
                        <img src="{{ $Item->pic }}?{{rand()}}"
                            style="width: 200px;height: 150px;margin-bottom: 20px;margin-top: 00px"/>
                        @if ($errors->has('pic'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('pic') }}</strong>
                            </span>
                        @endif
                    </div>


                </div>




            </div>

            <div class="card-footer">
               <button type="submit" form="edit" class="btn btn-primary mr-2">{{ trans('home.update') }}</button>
            </div>

        {!! Form::close() !!}
         <!--end::Form-->


      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection

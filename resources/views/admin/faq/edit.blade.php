@extends('admin.layouts.master')

@section('top_title')  {{ trans('home.edit-faq') }}  @endsection

@section('main_title') {{ trans('home.faq') }}   @endsection


@section('header')

    <style>
        .card-body .col-sm-12 ,.card-body .col-lg-12 , .card-body .col-sm-6 { margin-bottom: 20px }
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
                {{ trans('home.edit-faq') }}
            </h3>
         </div>


         <!--begin::Form-->
         {!! Form::model($Item, [ 'route' => ['admin_panel.faq.update' , $Item->id ] , 'method' => 'patch', 'files' => true, 'role'=>'form','id'=>'edit' ]) !!}

            <div class="card-body">

                <input type="hidden" name="id" value="{{$Item->id}}">

                <div class="row">

                    <div class="col-lg-6 col-sm-6 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                        <label> {{ trans('home.en_ques') }}    </label>
                        <input type="text" name="en_title" class="form-control m-input" required="required" value="{{ $Item->en_title }}" placeholder=" {{ trans('home.en_ques') }} ">
                        @if ($errors->has('en_title'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('en_title') }} </strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-lg-6 col-sm-6 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                        <label> {{ trans('home.ar_ques') }}    </label>
                        <input type="text" name="ar_title" class="form-control m-input" required="required" value="{{ $Item->ar_title }}" placeholder=" {{ trans('home.ar_ques') }} ">
                        @if ($errors->has('ar_title'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('ar_title') }} </strong>
                            </span>
                        @endif
                    </div>


                    <div class="col-lg-6 col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}">
                        <label> {{ trans('home.en_ans') }}   </label>
                        <textarea name="en_description" id="editor1" required class="form-control" rows="10" placeholder=" {{ trans('home.en_ans') }}  ">{{ $Item->en_description }}</textarea>
                        @if ($errors->has('en_description'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('en_description') }} </strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-lg-6 col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}">
                        <label> {{ trans('home.ar_ans') }}   </label>
                        <textarea name="ar_description" id="editor2" required class="form-control" rows="10" placeholder=" {{ trans('home.ar_ans') }}  ">{{ $Item->ar_description }}</textarea>
                        @if ($errors->has('ar_description'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('ar_description') }} </strong>
                            </span>
                        @endif
                    </div>



                </div>


            </div>

            <div class="card-footer">
               <button type="submit" form="edit" class="btn btn-primary mr-2">
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




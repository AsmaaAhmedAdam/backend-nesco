@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')


@section('main_title') {{ trans('home.add_product') }} @endsection

@section('top_title') {{ trans('home.add_product') }}  @endsection


@php
    $setting = App\Models\Setting::first();
@endphp

@section('header')

    <style>

        .card-body .col-sm-12 , .card-body .col-sm-6, .card-body .col-sm-4,.card-body .col-md-3 { margin-bottom: 20px }

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
                {{ trans('home.add_product') }}
            </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/products", 'role'=>'form','id'=>'add', 'files' => true,'method'=>'post']) !!}

            <div class="card-body">

                <div class="row">

                    <div class="col-sm-6 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.en_name') }}  <span class="text-danger">*</span> </label>

                        <input type="text" name="en_title" class="form-control m-input" required value="{{ old('en_title') }}" placeholder=" {{ trans('home.en_name') }} ">

                        @if ($errors->has('en_title'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('en_title') }} </strong>
                             </span>
                        @endif
                    </div>

                    <div class="col-sm-6 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                        <label>  {{ trans('home.ar_name') }}  <span class="text-danger">*</span> </label>

                        <input type="text" name="ar_title" class="form-control m-input" required value="{{ old('ar_title') }}" placeholder=" {{ trans('home.ar_name') }} ">

                        @if ($errors->has('ar_title'))
                             <span class="help-block" style="color:red">
                                  <strong>{{ $errors->first('ar_title') }} </strong>
                             </span>
                        @endif
                    </div>


                    <div class="col-md-6 col-sm-6 {{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <label>   {{ trans('home.categories') }}  <span class="text-danger">*</span> </label>
                        <select name="category_id" id="category_id" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true" required>
                            <option value="" disabled selected="true">  {{ trans('home.categories') }}  </option>
                            @foreach (H_Category($lang) as $key => $value)
                                <option value="{{ $key }}" @if(old('category_id') == $key) {{ 'selected' }} @endif> {{ $value }} </option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                         <span class="help-block" style="color:red">
                             <strong>{{ $errors->first('category_id') }}</strong>
                         </span>
                        @endif
                    </div>

                    <div class="col-sm-6 {{ $errors->has('pic') ? ' has-error' : '' }}">
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



                <div class="row">

                    <div class="col-lg-6 col-md-6 {{ $errors->has('price_before_discount') ? ' has-error' : '' }}">
                       <label> {{ trans('home.price_before_discount') }} </label>
                       <input type="text" name="price_before_discount" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="0"  placeholder="{{ trans('home.price_before_discount') }} ">

                       @if ($errors->has('price_before_discount'))
                           <span class="help-block" style="color:red">
                               <strong>{{ $errors->first('price_before_discount') }}</strong>
                           </span>
                       @endif
                    </div>

                    <div class="col-lg-6 col-md-6 {{ $errors->has('discount') ? ' has-error' : '' }}">
                        <label> {{ trans('home.discount') }} </label>
                        <input type="text" name="discount" onkeypress="return isNumberKey(event)" required class="form-control m-input" value="{{ old('discount') }}"  placeholder="{{ trans('home.discount') }}">

                        @if ($errors->has('discount'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('discount') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-6 col-md-6 {{ $errors->has('stock') ? ' has-error' : '' }}">
                       <label> {{ trans('home.stock') }} </label>
                       <input type="text" name="stock" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="1"  placeholder="{{ trans('home.stock') }} ">

                       @if ($errors->has('stock'))
                           <span class="help-block" style="color:red">
                               <strong>{{ $errors->first('stock') }}</strong>
                           </span>
                       @endif
                    </div>
                </div>



                <div class="row">

                    <div class="col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                        <label> {{ trans('home.en_description') }}  <span class="text-danger">*</span>  </label>
                        <textarea name="en_description" required class="form-control" rows="10" placeholder=" {{ trans('home.en_description') }}  ">{{ old('en_description') }}</textarea>
                        @if ($errors->has('en_description'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('en_description') }} </strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                        <label> {{ trans('home.ar_description') }}  <span class="text-danger">*</span>  </label>
                        <textarea name="ar_description" required class="form-control" rows="10" placeholder=" {{ trans('home.ar_description') }}   ">{{ old('ar_description') }}</textarea>
                        @if ($errors->has('ar_description'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('ar_description') }} </strong>
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

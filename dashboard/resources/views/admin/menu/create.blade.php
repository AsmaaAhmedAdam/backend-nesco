@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')


@section('main_title') {{ trans('home.add_menu') }} @endsection

@section('top_title') {{ trans('home.add_menu') }}  @endsection

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
                {{ trans('home.add_menu') }}
            </h3>
         </div>


         <!--begin::Form-->
         {!! Form::open(['url' => "admin_panel/menu", 'role'=>'form','id'=>'add', 'files' => true,'method'=>'post']) !!}

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

                    <div class="col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                        <label> {{ trans('home.en_description') }}  <span class="text-danger">*</span>  </label>
                        <textarea name="en_description" class="form-control" rows="10" placeholder=" {{ trans('home.en_description') }}  ">{{ old('en_description') }}</textarea>
                        @if ($errors->has('en_description'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('en_description') }} </strong>
                            </span>
                        @endif
                    </div>
        
                    <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                        <label> {{ trans('home.ar_description') }}  <span class="text-danger">*</span>  </label>
                        <textarea name="ar_description" class="form-control" rows="10" placeholder=" {{ trans('home.ar_description') }}   ">{{ old('ar_description') }}</textarea>
                        @if ($errors->has('ar_description'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('ar_description') }} </strong>
                            </span>
                        @endif
                    </div>


                    <div class="col-md-6 col-sm-6 {{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <label>   {{ trans('home.categories') }}  <span class="text-danger">*</span> </label>
                        <select name="category_id" id="category_id" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true" required>
                            <option value="" disabled selected="true">  {{ trans('home.categories') }}  </option>
                            @foreach (M_Category($lang) as $key => $value)
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

                    <div class="col-lg-12 col-sm-6 {{ $errors->has('has_nutrition_facts') ? ' has-error' : '' }}">
                        <label>   {{ trans('home.has_nutrition_facts') }}  <span class="text-danger">*</span> </label>
                        <select name="has_nutrition_facts" id="has_nutrition_facts" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true" required onchange="toggleDiv()">
                            <option value="0" selected="true">  {{ trans('home.false') }}  </option>
                            <option value="1" @if(old('has_nutrition_facts')) {{ 'selected' }} @endif> {{ trans('home.true') }} </option>
                        </select>
                        @if ($errors->has('has_nutrition_facts'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('has_nutrition_facts') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div id="nutrition_facts">
                    <div class="row">
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('serving_size') ? ' has-error' : '' }}">
                        <label> {{ trans('home.serving_size') }} </label>
                        <input type="text" name="serving_size"  class="form-control m-input" value="{{ old('serving_size') }}"  placeholder="xxx.x">
            
                        @if ($errors->has('serving_size'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('serving_size') }}</strong>
                            </span>
                        @endif
                        </div>
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('serving_per_container') ? ' has-error' : '' }}">
                            <label> {{ trans('home.serving_per_container') }} </label>
                            <input type="text" name="serving_per_container"  class="form-control m-input" value="{{ old('serving_per_container') }}" placeholder="x">
            
                            @if ($errors->has('serving_per_container'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('serving_per_container') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('calories') ? ' has-error' : '' }}">
                            <label> {{ trans('home.calories') }} </label>
                            <input type="text" name="calories" class="form-control m-input" value="{{ old('calories') }}" placeholder="x.x">
            
                            @if ($errors->has('calories'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('calories') }}</strong>
                                </span>
                            @endif
                        </div>
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('calories_from_fat') ? ' has-error' : '' }}">
                            <label> {{ trans('home.calories_from_fat') }} </label>
                            <input type="text" name="calories_from_fat"  class="form-control m-input" value="{{ old('calories_from_fat') }}" placeholder="x">
            
                            @if ($errors->has('calories_from_fat'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('calories_from_fat') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_fat') ? ' has-error' : '' }}">
                        <label> {{ trans('home.total_fat') }} </label>
                        <input type="text" name="total_fat" class="form-control m-input" value="{{ old('total_fat') }}"  placeholder="<x.x">
            
                        @if ($errors->has('total_fat'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('total_fat') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('saturated_fat') ? ' has-error' : '' }}">
                            <label> {{ trans('home.saturated_fat') }} </label>
                            <input type="text" name="saturated_fat"  class="form-control m-input" value="{{ old('saturated_fat') }}"  placeholder="<x.x">
            
                            @if ($errors->has('saturated_fat'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('saturated_fat') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('trans_fat') ? ' has-error' : '' }}">
                            <label> {{ trans('home.trans_fat') }} </label>
                            <input type="text" name="trans_fat"  class="form-control m-input" value="{{ old('trans_fat') }}"  placeholder="<x.x">
            
                            @if ($errors->has('trans_fat'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('trans_fat') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('cholesterol') ? ' has-error' : '' }}">
                            <label> {{ trans('home.cholesterol') }} </label>
                            <input type="text" name="cholesterol"  class="form-control m-input" value="{{ old('cholesterol') }}"  placeholder="<x">
            
                            @if ($errors->has('cholesterol'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('cholesterol') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sodium') ? ' has-error' : '' }}">
                        <label> {{ trans('home.sodium') }} </label>
                        <input type="text" name="sodium"   class="form-control m-input" value="{{ old('sodium') }}"  placeholder="<x">
            
                        @if ($errors->has('sodium'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('sodium') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates') ? ' has-error' : '' }}">
                            <label> {{ trans('home.total_carbohydrates') }} </label>
                            <input type="text" name="total_carbohydrates"  class="form-control m-input" value="{{ old('total_carbohydrates') }}"  placeholder="x.x">
            
                            @if ($errors->has('total_carbohydrates'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('total_carbohydrates') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('dietary_fiber') ? ' has-error' : '' }}">
                            <label> {{ trans('home.dietary_fiber') }} </label>
                            <input type="text" name="dietary_fiber"  class="form-control m-input" value="{{ old('dietary_fiber') }}"  placeholder="<x">
            
                            @if ($errors->has('dietary_fiber'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('dietary_fiber') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_sugar') ? ' has-error' : '' }}">
                            <label> {{ trans('home.total_sugar') }} </label>
                            <input type="text" name="total_sugar"  class="form-control m-input" value="{{ old('total_sugar') }}"  placeholder="<x.x">
            
                            @if ($errors->has('total_sugar'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('total_sugar') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('added_suger') ? ' has-error' : '' }}">
                        <label> {{ trans('home.added_suger') }} </label>
                        <select name="added_suger" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                            <option value="false" selected="true">  {{ trans('home.false') }}  </option>
                            <option value="false" @if(old('added_suger')) {{ 'selected' }} @endif> {{ trans('home.true') }} </option>
                        </select>
            
                        @if ($errors->has('added_suger'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('added_suger') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('protein') ? ' has-error' : '' }}">
                            <label> {{ trans('home.protein') }} </label>
                            <input type="text" name="protein"  class="form-control m-input" value="{{ old('protein') }}"  placeholder="x.x">
            
                            @if ($errors->has('protein'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('protein') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('iron') ? ' has-error' : '' }}">
                            <label> {{ trans('home.iron') }} </label>
                            <input type="text" name="iron"  class="form-control m-input" value="{{ old('iron') }}"  placeholder="x%">
            
                            @if ($errors->has('iron'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('iron') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('calcium') ? ' has-error' : '' }}">
                            <label> {{ trans('home.calcium') }} </label>
                            <input type="text" name="calcium"  class="form-control m-input" value="{{ old('calcium') }}"  placeholder="x%">
            
                            @if ($errors->has('calcium'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('calcium') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_fat_calories') ? ' has-error' : '' }}">
                        <label> {{ trans('home.total_fat_calories') }} </label>
                        <select name="total_fat_calories" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                            <option value="less than" selected="true">  {{ trans('home.less_than') }}  </option>
                            <option value="more than" @if(old('total_fat_calories')) {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                            <option value="equal" @if(old('total_fat_calories')) {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                        </select>
            
                        @if ($errors->has('total_fat_calories'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('total_fat_calories') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_fat_2000') ? ' has-error' : '' }}">
                            <label> {{ trans('home.total_fat_2000') }} </label>
                            <input type="text" name="total_fat_2000"   class="form-control m-input" value="{{ old('total_fat_2000') }}"  placeholder="xxxx">
            
                            @if ($errors->has('total_fat_2000'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('total_fat_2000') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_fat_2500') ? ' has-error' : '' }}">
                            <label> {{ trans('home.total_fat_2500') }} </label>
                            <input type="text" name="total_fat_2500"   class="form-control m-input" value="{{ old('total_fat_2500') }}"  placeholder="xxxx">
            
                            @if ($errors->has('total_fat_2500'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('total_fat_2500') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sat_fat_calories') ? ' has-error' : '' }}">
                            <label> {{ trans('home.sat_fat_calories') }} </label>
                            <select name="sat_fat_calories" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                                <option value="less than" selected="true">  {{ trans('home.less_than') }}  </option>
                                <option value="more than" @if(old('sat_fat_calories')) {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                                <option value="equal" @if(old('sat_fat_calories')) {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                            </select>
            
                            @if ($errors->has('sat_fat_calories'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('sat_fat_calories') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sat_fat_2000') ? ' has-error' : '' }}">
                        <label> {{ trans('home.sat_fat_2000') }} </label>
                        <input type="text" name="sat_fat_2000"  class="form-control m-input" value="{{ old('sat_fat_2000') }}"  placeholder="xxxx">
            
                        @if ($errors->has('sat_fat_2000'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('sat_fat_2000') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sat_fat_2500') ? ' has-error' : '' }}">
                            <label> {{ trans('home.sat_fat_2500') }} </label>
                            <input type="text" name="sat_fat_2500"  class="form-control m-input" value="{{ old('sat_fat_2500') }}"  placeholder="xxxx">
            
                            @if ($errors->has('sat_fat_2500'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('sat_fat_2500') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('cholesterol_calories') ? ' has-error' : '' }}">
                            <label> {{ trans('home.cholesterol_calories') }} </label>
                            <select name="cholesterol_calories" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                                <option value="less than" selected="true">  {{ trans('home.less_than') }}  </option>
                                <option value="more than" @if(old('cholesterol_calories')) {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                                <option value="equal" @if(old('cholesterol_calories')) {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                            </select>
            
                            @if ($errors->has('cholesterol_calories'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('cholesterol_calories') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('cholesterol_2000') ? ' has-error' : '' }}">
                            <label> {{ trans('home.cholesterol_2000') }} </label>
                            <input type="text" name="cholesterol_2000"  class="form-control m-input" value="{{ old('cholesterol_2000') }}"  placeholder="xxxx">
            
                            @if ($errors->has('cholesterol_2000'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('cholesterol_2000') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('cholesterol_2500') ? ' has-error' : '' }}">
                        <label> {{ trans('home.cholesterol_2500') }} </label>
                        <input type="text" name="cholesterol_2500"  class="form-control m-input" value="{{ old('cholesterol_2500') }}"  placeholder="xxxx">
            
                        @if ($errors->has('cholesterol_2500'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('cholesterol_2500') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sodium_calories') ? ' has-error' : '' }}">
                            <label> {{ trans('home.sodium_calories') }} </label>
                            <select name="sodium_calories" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                                <option value="less than" selected="true">  {{ trans('home.less_than') }}  </option>
                                <option value="more than" @if(old('sodium_calories')) {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                                <option value="equal" @if(old('sodium_calories')) {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                            </select>
            
                            @if ($errors->has('sodium_calories'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('sodium_calories') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sodium_2000') ? ' has-error' : '' }}">
                            <label> {{ trans('home.sodium_2000') }} </label>
                            <input type="text" name="sodium_2000"  class="form-control m-input" value="{{ old('sodium_2000') }}"  placeholder="xxxx">
            
                            @if ($errors->has('sodium_2000'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('sodium_2000') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('sodium_2500') ? ' has-error' : '' }}">
                            <label> {{ trans('home.sodium_2500') }} </label>
                            <input type="text" name="sodium_2500"  class="form-control m-input" value="{{ old('sodium_2500') }}"  placeholder="xxxx">
            
                            @if ($errors->has('sodium_2500'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('sodium_2500') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates_calories') ? ' has-error' : '' }}">
                        <label> {{ trans('home.total_carbohydrates_calories') }} </label>
                        <select name="total_carbohydrates_calories" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                            <option value="less than" selected="true">  {{ trans('home.less_than') }}  </option>
                            <option value="more than" @if(old('total_carbohydrates_calories')) {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                            <option value="equal" @if(old('total_carbohydrates_calories')) {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                        </select>
            
                        @if ($errors->has('total_carbohydrates_calories'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('total_carbohydrates_calories') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates_fat_2000') ? ' has-error' : '' }}">
                            <label> {{ trans('home.total_carbohydrates_fat_2000') }} </label>
                            <input type="text" name="total_carbohydrates_fat_2000"  class="form-control m-input" value="{{ old('total_carbohydrates_fat_2000') }}"  placeholder="xxxx">
            
                            @if ($errors->has('total_carbohydrates_fat_2000'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('total_carbohydrates_fat_2000') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates_fat_2500') ? ' has-error' : '' }}">
                            <label> {{ trans('home.total_carbohydrates_fat_2500') }} </label>
                            <input type="text" name="total_carbohydrates_fat_2500"  class="form-control m-input" value="{{ old('total_carbohydrates_fat_2500') }}"  placeholder="xxxx">
            
                            @if ($errors->has('total_carbohydrates_fat_2500'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('total_carbohydrates_fat_2500') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('dietary_fiber_calories') ? ' has-error' : '' }}">
                            <label> {{ trans('home.dietary_fiber_calories') }} </label>
                            <select name="dietary_fiber_calories" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true">
                                <option value="less than" selected="true">  {{ trans('home.less_than') }}  </option>
                                <option value="more than" @if(old('dietary_fiber_calories')) {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                                <option value="equal" @if(old('dietary_fiber_calories')) {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                            </select>
            
                            @if ($errors->has('dietary_fiber_calories'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('dietary_fiber_calories') }}</strong>
                                </span>
                            @endif
                        </div>
            
                    </div>
            
                    <div class="row">
            
                        <div class="col-lg-3 col-md-3 {{ $errors->has('dietary_fiber_2000') ? ' has-error' : '' }}">
                        <label> {{ trans('home.dietary_fiber_2000') }} </label>
                        <input type="text" name="dietary_fiber_2000"   class="form-control m-input" value="{{ old('dietary_fiber_2000') }}"  placeholder="xxxx">
            
                        @if ($errors->has('dietary_fiber_2000'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('dietary_fiber_2000') }}</strong>
                            </span>
                        @endif
                        </div>
                        
                        <div class="col-lg-3 col-md-3 {{ $errors->has('dietary_fiber_2500') ? ' has-error' : '' }}">
                            <label> {{ trans('home.dietary_fiber_2500') }} </label>
                            <input type="text" name="dietary_fiber_2500"   class="form-control m-input" value="{{ old('dietary_fiber_2500') }}"  placeholder="xxxx">
            
                            @if ($errors->has('dietary_fiber_2500'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('dietary_fiber_2500') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                    </div>
            
                    <div class="row">
                        <div class="col-sm-6 {{ $errors->has('more_info_en') ? ' has-error' : '' }}" style="margin-top: 30px">
                            <label> {{ trans('home.more_info_en') }}  <span class="text-danger">*</span>  </label>
                            <textarea name="more_info_en" class="form-control" rows="10" placeholder=" {{ trans('home.more_info_en') }}  ">{{ old('more_info_en') }}</textarea>
                            @if ($errors->has('more_info_en'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('more_info_en') }} </strong>
                                </span>
                            @endif
                        </div>
            
                        <div class="col-sm-6 {{ $errors->has('more_info_ar') ? ' has-error' : '' }}" style="margin-top: 30px">
                            <label> {{ trans('home.more_info_ar') }}  <span class="text-danger">*</span>  </label>
                            <textarea name="more_info_ar" class="form-control" rows="10" placeholder=" {{ trans('home.more_info_ar') }}   ">{{ old('more_info_ar') }}</textarea>
                            @if ($errors->has('more_info_ar'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('more_info_ar') }} </strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-sm-3 {{ $errors->has('allergens_icon') ? ' has-error' : '' }}">
                            <label>  {{ trans('home.allergens_icon') }}  </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="allergens_icon" accept="image/*">
                                <label class="custom-file-label" for="customFile"> {{ trans('home.allergens_icon') }}   </label>
                            </div>
                            @if ($errors->has('allergens_icon'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('allergens_icon') }}</strong>
                                </span>
                            @endif
                            <br>
                            <img src=" @if(!empty($nutritionFacts->allergens_icon)){{ Custom_Image_Path('images',$nutritionFacts->allergens_icon) }}@else{{Custom_Image_Path('img','no-image.png')}}@endif"
                                    style="width: 200px;height: 150px;margin-bottom: 20px;margin-top: 20px"/>
                        </div>
            
                        <div class="col-lg-4 col-md-3 {{ $errors->has('allergens_en_title') ? ' has-error' : '' }}">
                            <label> {{ trans('home.allergens_en_title') }} </label>
                            <input type="text" name="allergens_en_title"   class="form-control m-input" value="{{ old('allergens_en_title') }}"  placeholder=" {{ trans('home.allergens_en_title') }} ">
            
                            @if ($errors->has('allergens_en_title'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('allergens_en_title') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-lg-4 col-md-3 {{ $errors->has('allergens_ar_title') ? ' has-error' : '' }}">
                            <label> {{ trans('home.allergens_ar_title') }} </label>
                            <input type="text" name="allergens_ar_title"   class="form-control m-input" value="{{ old('allergens_ar_title') }}"  placeholder=" {{ trans('home.allergens_ar_title') }} ">
            
                            @if ($errors->has('allergens_ar_title'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('allergens_ar_title') }}</strong>
                                </span>
                            @endif
                        </div>
            
                        <div class="col-sm-6 {{ $errors->has('allergens_en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                            <label> {{ trans('home.allergens_en_description') }}  <span class="text-danger">*</span>  </label>
                            <textarea name="allergens_en_description" class="form-control" rows="10" placeholder=" {{ trans('home.allergens_en_description') }}  ">{{ old('allergens_en_description') }}</textarea>
                            @if ($errors->has('allergens_en_description'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('allergens_en_description') }} </strong>
                                </span>
                            @endif
                        </div>
            
                        <div class="col-sm-6 {{ $errors->has('allergens_ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                            <label> {{ trans('home.allergens_ar_description') }}  <span class="text-danger">*</span>  </label>
                            <textarea name="allergens_ar_description" class="form-control" rows="10" placeholder=" {{ trans('home.allergens_ar_description') }}   ">{{ old('allergens_ar_description') }}</textarea>
                            @if ($errors->has('allergens_ar_description'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('allergens_ar_description') }} </strong>
                                </span>
                            @endif
                        </div>
            
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

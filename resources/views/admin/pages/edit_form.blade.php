@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


<!--begin::Form-->
    {!! Form::model($Item, [ 'route' => ['site_pages.update' , $Item->id ] , 'method' => 'patch', 'files' => true, 'role'=>'form','id'=>'edit' ]) !!}

        <input type="hidden" name="id" value="{{$Item->id}}">



        {{--<div class="row">
            <div class="col-sm-6 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.en_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="en_title" class="form-control m-input" required value="{{ $Item->en_title }}" placeholder=" {{ trans('home.en_name') }} ">

                @if ($errors->has('en_title'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('en_title') }} </strong>
                        </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.ar_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="ar_title" class="form-control m-input" required value="{{ $Item->ar_title }}" placeholder=" {{ trans('home.ar_name') }} ">

                @if ($errors->has('ar_title'))
                     <span class="help-block" style="color:red">
                          <strong>{{ $errors->first('ar_title') }} </strong>
                     </span>
                @endif
            </div> --}}



            {{-- <div class="col-sm-6 {{ $errors->has('category_id') ? ' has-error' : '' }}">
                <label>   {{ trans('home.categories') }}  <span class="text-danger">*</span> </label>
                <select name="category_id" id="category_id" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true" required>
                    <option value="" disabled selected="true">  {{ trans('home.categories') }}  </option>
                    @foreach (H_Category($lang) as $key => $value)
                        <option value="{{ $key }}" @if($Item->category_id == $key) {{ 'selected' }} @endif> {{ $value }} </option>
                    @endforeach
                </select>
                @if ($errors->has('category_id'))
                <span class="help-block" style="color:red">
                    <strong>{{ $errors->first('category_id') }}</strong>
                </span>
                @endif
            </div>     
        </div>
        --}}



        {{-- <div class="row">

            <div class="col-lg-6 col-md-6 {{ $errors->has('price_before_discount') ? ' has-error' : '' }}">
               <label> {{ trans('home.price_before_discount') }} </label>
               <input type="text" name="price_before_discount" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $Item->price_before_discount }}"  placeholder=" {{ trans('home.price_before_discount') }} ">

               @if ($errors->has('price_before_discount'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('price_before_discount') }}</strong>
                   </span>
               @endif
            </div>

            <div class="col-lg-6 col-md-6 {{ $errors->has('discount') ? ' has-error' : '' }}">
                <label> {{ trans('home.discount') }} </label>
                <input type="text" name="discount" onkeypress="return isNumberKey(event)" required class="form-control m-input" value="{{ $Item->discount }}" placeholder="{{ trans('home.discount') }}">

                @if ($errors->has('discount'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('discount') }}</strong>
                    </span>
                @endif
            </div>

        </div> --}}




        {{-- <div class="row">

            <div class="col-lg-6 col-md-6 {{ $errors->has('stock') ? ' has-error' : '' }}">
               <label> {{ trans('home.stock') }} </label>
               <input type="text" name="stock" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $Item->stock }}"  placeholder=" {{ trans('home.stock') }} ">

               @if ($errors->has('stock'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('stock') }}</strong>
                   </span>
               @endif
            </div>

        </div> --}}

<!-- /////////////////////////////////////// -->


        <div class="row">

            <div class="col-sm-4 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.en_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="first_en_title" class="form-control m-input" required value="@if(!empty($en_title->{1})){{ $en_title->{1} }}@endif" placeholder=" {{ trans('home.first_en_name') }} ">

                @if ($errors->has('en_title'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('en_title') }} </strong>
                        </span>
                @endif
            </div>

            <div class="col-sm-4 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.ar_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="first_ar_title" class="form-control m-input" required value="@if(!empty($ar_title->{1})){{ $ar_title->{1} }}@endif" placeholder=" {{ trans('home.first_ar_name') }} ">

                @if ($errors->has('ar_title'))
                     <span class="help-block" style="color:red">
                          <strong>{{ $errors->first('ar_title') }} </strong>
                     </span>
                @endif
            </div> 

            <div class="col-sm-4 {{ $errors->has('pic') ? ' has-error' : '' }}">
                <label>  {{ trans('home.first_pic') }}  </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="first_pic" accept="image/*">
                    <label class="custom-file-label" for="customFile"> {{ trans('home.pic_msg') }}   </label>
                </div>
                @if ($errors->has('pic'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('pic') }}</strong>
                    </span>
                @endif
                <br>
                <img src=" @if(!empty($images->{1})){{ Custom_Image_Path('images',$images->{1}) }}@else{{Custom_Image_Path('img','no-image.png')}}@endif"
                        style="width: 200px;height: 150px;margin-bottom: 20px;margin-top: 20px"/>
            </div>

            <div class="col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.first_en_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="first_en_description" required class="form-control" rows="10" placeholder=" {{ trans('home.first_en_description') }}  ">@if(!empty($en_description->{1})){{ $en_description->{1} }}@endif</textarea>
                @if ($errors->has('en_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('en_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.first_ar_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="first_ar_description" required class="form-control" rows="10" placeholder=" {{ trans('home.first_ar_description') }}   ">@if(!empty($ar_description->{1})){{ $ar_description->{1} }}@endif</textarea>
                @if ($errors->has('ar_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('ar_description') }} </strong>
                    </span>
                @endif
            </div>


        </div>
        <hr>
 <!-- /////////////////////////////////////////// -->


        <div class="row">

            <div class="col-sm-4 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.en_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="second_en_title" class="form-control m-input" required value="@if(!empty($en_title->{2})){{ $en_title->{2} }}@endif" placeholder=" {{ trans('home.second_en_name') }} ">

                @if ($errors->has('en_title'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('en_title') }} </strong>
                        </span>
                @endif
            </div>

            <div class="col-sm-4 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.ar_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="second_ar_title" class="form-control m-input" required value="@if(!empty($ar_title->{2})){{ $ar_title->{2} }}@endif" placeholder=" {{ trans('home.second_ar_name') }} ">

                @if ($errors->has('ar_title'))
                     <span class="help-block" style="color:red">
                          <strong>{{ $errors->first('ar_title') }} </strong>
                     </span>
                @endif
            </div> 

            <div class="col-sm-4 {{ $errors->has('pic') ? ' has-error' : '' }}">
                <label>  {{ trans('home.second_pic') }}  </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="second_pic" accept="image/*">
                    <label class="custom-file-label" for="customFile"> {{ trans('home.pic_msg') }}   </label>
                </div>
                @if ($errors->has('pic'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('pic') }}</strong>
                    </span>
                @endif
                <br>
                <img src=" @if(!empty($images->{2})){{ Custom_Image_Path('images',$images->{2}) }}@else{{Custom_Image_Path('img','no-image.png')}}@endif"
                        style="width: 200px;height: 150px;margin-bottom: 20px;margin-top: 20px"/>
            </div>

            <div class="col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.second_en_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="second_en_description" required class="form-control" rows="10" placeholder=" {{ trans('home.second_en_description') }}  ">@if(!empty($en_description->{2})){{ $en_description->{2} }}@endif</textarea>
                @if ($errors->has('en_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('en_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.second_ar_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="second_ar_description" required class="form-control" rows="10" placeholder=" {{ trans('home.second_ar_description') }}   ">@if(!empty($ar_description->{2})){{ $ar_description->{2} }}@endif</textarea>
                @if ($errors->has('ar_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('ar_description') }} </strong>
                    </span>
                @endif
            </div>


        </div>
        <hr>


<!-- //////////////////////////////////////////////////// -->
        
        <div class="row">

            <div class="col-sm-4 {{ $errors->has('en_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.en_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="third_en_title" class="form-control m-input" required value="@if(!empty($en_title->{3})){{ $en_title->{3} }}@endif" placeholder=" {{ trans('home.third_en_name') }} ">

                @if ($errors->has('en_title'))
                        <span class="help-block" style="color:red">
                            <strong>{{ $errors->first('en_title') }} </strong>
                        </span>
                @endif
            </div>

            <div class="col-sm-4 {{ $errors->has('ar_title') ? ' has-error' : '' }}">
                <label>  {{ trans('home.ar_name') }}  <span class="text-danger">*</span> </label>

                <input type="text" name="third_ar_title" class="form-control m-input" required value="@if(!empty($ar_title->{3})){{ $ar_title->{3} }}@endif" placeholder=" {{ trans('home.third_ar_name') }} ">

                @if ($errors->has('ar_title'))
                     <span class="help-block" style="color:red">
                          <strong>{{ $errors->first('ar_title') }} </strong>
                     </span>
                @endif
            </div> 

            <div class="col-sm-4 {{ $errors->has('pic') ? ' has-error' : '' }}">
                <label>  {{ trans('home.third_pic') }}  </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="third_pic" accept="image/*">
                    <label class="custom-file-label" for="customFile"> {{ trans('home.pic_msg') }}   </label>
                </div>
                @if ($errors->has('pic'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('pic') }}</strong>
                    </span>
                @endif
                <br>
                <img src=" @if(!empty($images->{3})){{ Custom_Image_Path('images',$images->{3}) }}@else{{Custom_Image_Path('img','no-image.png')}}@endif"
                        style="width: 200px;height: 150px;margin-bottom: 20px;margin-top: 20px"/>
            </div>

            <div class="col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.third_en_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="third_en_description" required class="form-control" rows="10" placeholder=" {{ trans('home.third_en_description') }}  ">@if(!empty($en_description->{3})){{ $en_description->{3} }}@endif</textarea>
                @if ($errors->has('en_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('en_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.third_ar_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="third_ar_description" required class="form-control" rows="10" placeholder=" {{ trans('home.third_ar_description') }}   ">@if(!empty($ar_description->{3})){{ $ar_description->{3} }}@endif</textarea>
                @if ($errors->has('ar_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('ar_description') }} </strong>
                    </span>
                @endif
        </div>
        </div>
        <hr>
        <!-- ////////////////////////////////// -->

       

        <button type="submit" style="margin-bottom:50px" form="edit" class="btn btn-primary mr-2">{{ trans('home.update') }} </button>


    {!! Form::close() !!}
    <!--end::Form-->











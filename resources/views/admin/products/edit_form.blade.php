@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

<!--begin::Form-->
    {!! Form::model($Item, [ 'route' => ['admin_panel.products.update' , $Item->id ] , 'method' => 'patch', 'files' => true, 'role'=>'form','id'=>'edit' ]) !!}

        <input type="hidden" name="id" value="{{$Item->id}}">

        <div class="row">
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
            </div>

            <div class="col-sm-6 {{ $errors->has('category_id') ? ' has-error' : '' }}">
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

            <div class="col-sm-6 {{ $errors->has('pic') ? ' has-error' : '' }}">
                <label>  {{ trans('home.pic') }}  </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="pic" accept="image/*">
                    <label class="custom-file-label" for="customFile"> {{ trans('home.pic_msg') }}   </label>
                </div>
                @if ($errors->has('pic'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('pic') }}</strong>
                    </span>
                @endif
                <br>
                <img src=" {{ $Item->pic }}?{{rand()}}"
                        style="width: 200px;height: 150px;margin-bottom: 20px;margin-top: 20px"/>
            </div>



        </div>

        <div class="row">

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

        </div>

        <div class="row">

            <div class="col-lg-6 col-md-6 {{ $errors->has('stock') ? ' has-error' : '' }}">
               <label> {{ trans('home.stock') }} </label>
               <input type="text" name="stock" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $Item->stock }}"  placeholder=" {{ trans('home.stock') }} ">

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
                <textarea name="en_description" required class="form-control" rows="10" placeholder=" {{ trans('home.en_description') }}  ">{{ $Item->en_description }}</textarea>
                @if ($errors->has('en_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('en_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.ar_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="ar_description" required class="form-control" rows="10" placeholder=" {{ trans('home.ar_description') }}   ">{{ $Item->ar_description }}</textarea>
                @if ($errors->has('ar_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('ar_description') }} </strong>
                    </span>
                @endif
            </div>


        </div>


        <button type="submit" style="margin-bottom:50px" form="edit" class="btn btn-primary mr-2">{{ trans('home.update') }} </button>


    {!! Form::close() !!}
    <!--end::Form-->











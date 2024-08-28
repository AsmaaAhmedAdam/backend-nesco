@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

{!! Form::model($Item, [ 'route' => ['admin_panel.users.update' , $Item->id ] , 'method' => 'patch', 'files'=>true, 'role'=>'form','id'=>'edit', 'class'=> 'm-form m-form--fit m-form--label-align-left' ]) !!}

        {{ csrf_field() }}

            <input type="hidden" name="id" value="{{ $Item->id }}">

            <div class="form-group m-form__group row" style="margin-top: 30px">

                <div class="col-lg-6 col-sm-6 {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label>  {{ trans('home.name') }}  <span class="text-danger">*</span>  </label>
                    <input type="text" name="name" class="form-control m-input" required="required" value="{{ $Item->name }}" placeholder="  {{ trans('home.name') }}   ">
                    @if ($errors->has('name'))
                         <span class="help-block" style="color:red">
                              <strong>{{ $errors->first('name') }} </strong>
                         </span>
                    @endif
                </div>



                <div class="col-lg-6 col-sm-6 {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label>  {{ trans('home.email') }} <span class="text-danger">*</span>   </label>
                    <input type="email" name="email" class="form-control m-input" required="required" value="{{ $Item->email }}" placeholder="  {{ trans('home.email') }}   ">
                    @if ($errors->has('email'))
                         <span class="help-block" style="color:red">
                              <strong>{{ $errors->first('email') }} </strong>
                         </span>
                    @endif
                </div>


                <div class="col-lg-6 col-sm-6 {{ $errors->has('mobile') ? ' has-error' : '' }}">
                    <label>  {{ trans('home.mobile') }} <span class="text-danger">*</span>   </label>
                    <input type="text" name="mobile" class="form-control m-input" required="required" value="{{ $Item->mobile }}" placeholder=" {{ trans('home.mobile') }} ">
                    @if ($errors->has('mobile'))
                         <span class="help-block" style="color:red">
                              <strong>{{ $errors->first('mobile') }} </strong>
                         </span>
                    @endif
                </div>




                <div class="col-lg-6 col-sm-6  {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label> {{ trans('home.password') }}  </label>
                    <input type="text" name="password" class="form-control m-input" value="" placeholder="{{ trans('home.password') }}">
                    @if ($errors->has('password'))
                         <span class="help-block" style="color:red">
                              <strong>{{ $errors->first('password') }} </strong>
                         </span>
                    @endif
                </div>


                <div class="col-lg-12">
                    <button type="submit" form="edit" class="btn btn-success" style="margin-top:20px;margin-bottom: 50px;">{{ trans('home.update') }}</button>
                </div>

            </div>


        {!! Form::close() !!}

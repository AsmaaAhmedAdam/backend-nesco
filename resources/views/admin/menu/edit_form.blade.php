@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

<!--begin::Form-->
    {!! Form::model($Item, [ 'route' => ['menu.update' , $Item->id ] , 'method' => 'patch', 'files' => true, 'role'=>'form','id'=>'edit' ]) !!}

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

            <div class="col-sm-6 {{ $errors->has('en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.en_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="en_description" class="form-control" rows="10" placeholder=" {{ trans('home.en_description') }}  ">{{ $Item->en_description }}</textarea>
                @if ($errors->has('en_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('en_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.ar_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="ar_description" class="form-control" rows="10" placeholder=" {{ trans('home.ar_description') }}   ">{{ $Item->ar_description }}</textarea>
                @if ($errors->has('ar_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('ar_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('category_id') ? ' has-error' : '' }}">
                <label>   {{ trans('home.categories') }}  <span class="text-danger">*</span> </label>
                <select name="category_id" id="category_id" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true" required>
                    <option value="" disabled selected="true">  {{ trans('home.categories') }}  </option>
                    @foreach (M_Category($lang) as $key => $value)
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
        <hr>
        <div class="row">

            <div class="col-lg-12 col-sm-6 {{ $errors->has('has_nutrition_facts') ? ' has-error' : '' }}">
                <label>   {{ trans('home.has_nutrition_facts') }}  <span class="text-danger">*</span> </label>
                <select name="has_nutrition_facts" id="has_nutrition_facts" class="form-control m-bootstrap-select m_selectpicker" data-live-search="true" required>
                    <option value="0" selected="true">  {{ trans('home.false') }}  </option>
                    <option value="1" @if($Item->has_nutrition_facts == 1) {{ 'selected' }} @endif> {{ trans('home.true') }} </option>
                </select>
                @if ($errors->has('has_nutrition_facts'))
                <span class="help-block" style="color:red">
                    <strong>{{ $errors->first('has_nutrition_facts') }}</strong>
                </span>
                @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('serving_size') ? ' has-error' : '' }}">
               <label> {{ trans('home.serving_size') }} </label>
               <input type="text" name="serving_size" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->serving_size ?? null }}"  placeholder=" {{ trans('home.serving_size') }} ">

               @if ($errors->has('serving_size'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('serving_size') }}</strong>
                   </span>
               @endif
            </div>

            <div class="col-lg-3 col-md-3 {{ $errors->has('serving_per_container') ? ' has-error' : '' }}">
                <label> {{ trans('home.serving_per_container') }} </label>
                <input type="text" name="serving_per_container" onkeypress="return isNumberKey(event)" required class="form-control m-input" value="{{ $nutritionFacts->serving_per_container ?? null }}" placeholder="{{ trans('home.serving_per_container') }}">

                @if ($errors->has('serving_per_container'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('serving_per_container') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('calories') ? ' has-error' : '' }}">
                <label> {{ trans('home.calories') }} </label>
                <input type="text" name="calories" onkeypress="return isNumberKey(event)" required class="form-control m-input" value="{{ $nutritionFacts->calories ?? null }}" placeholder="{{ trans('home.calories') }}">

                @if ($errors->has('calories'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('calories') }}</strong>
                    </span>
                @endif
            </div>

            <div class="col-lg-3 col-md-3 {{ $errors->has('calories_from_fat') ? ' has-error' : '' }}">
                <label> {{ trans('home.calories_from_fat') }} </label>
                <input type="text" name="calories_from_fat" onkeypress="return isNumberKey(event)" required class="form-control m-input" value="{{ $nutritionFacts->calories_from_fat ?? null }}" placeholder="{{ trans('home.calories_from_fat') }}">

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
               <input type="text" name="total_fat" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_fat ?? null }}"  placeholder=" {{ trans('home.total_fat') }} ">

               @if ($errors->has('total_fat'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('total_fat') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('saturated_fat') ? ' has-error' : '' }}">
                <label> {{ trans('home.saturated_fat') }} </label>
                <input type="text" name="saturated_fat" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->saturated_fat ?? null }}"  placeholder=" {{ trans('home.saturated_fat') }} ">
 
                @if ($errors->has('saturated_fat'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('saturated_fat') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('trans_fat') ? ' has-error' : '' }}">
                <label> {{ trans('home.trans_fat') }} </label>
                <input type="text" name="trans_fat" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->trans_fat ?? null }}"  placeholder=" {{ trans('home.trans_fat') }} ">
 
                @if ($errors->has('trans_fat'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('trans_fat') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('cholesterol') ? ' has-error' : '' }}">
                <label> {{ trans('home.cholesterol') }} </label>
                <input type="text" name="cholesterol" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->cholesterol ?? null }}"  placeholder=" {{ trans('home.cholesterol') }} ">
 
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
               <input type="text" name="sodium" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->sodium ?? null }}"  placeholder=" {{ trans('home.sodium') }} ">

               @if ($errors->has('sodium'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('sodium') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates') ? ' has-error' : '' }}">
                <label> {{ trans('home.total_carbohydrates') }} </label>
                <input type="text" name="total_carbohydrates" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_carbohydrates ?? null }}"  placeholder=" {{ trans('home.total_carbohydrates') }} ">
 
                @if ($errors->has('total_carbohydrates'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('total_carbohydrates') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('dietary_fiber') ? ' has-error' : '' }}">
                <label> {{ trans('home.dietary_fiber') }} </label>
                <input type="text" name="dietary_fiber" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->dietary_fiber ?? null }}"  placeholder=" {{ trans('home.dietary_fiber') }} ">
 
                @if ($errors->has('dietary_fiber'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('dietary_fiber') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('total_sugar') ? ' has-error' : '' }}">
                <label> {{ trans('home.total_sugar') }} </label>
                <input type="text" name="total_sugar" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_sugar ?? null }}"  placeholder=" {{ trans('home.total_sugar') }} ">
 
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
                    <option value="true" @if(!empty($nutritionFacts->added_suger) && $nutritionFacts->added_suger == 'true') {{ 'selected' }} @endif> {{ trans('home.true') }} </option>
                </select>

               @if ($errors->has('added_suger'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('added_suger') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('protein') ? ' has-error' : '' }}">
                <label> {{ trans('home.protein') }} </label>
                <input type="text" name="protein" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->protein ?? null }}"  placeholder=" {{ trans('home.protein') }} ">
 
                @if ($errors->has('protein'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('protein') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('iron') ? ' has-error' : '' }}">
                <label> {{ trans('home.iron') }} </label>
                <input type="text" name="iron" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->iron ?? null }}"  placeholder=" {{ trans('home.iron') }} ">
 
                @if ($errors->has('iron'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('iron') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('calcium') ? ' has-error' : '' }}">
                <label> {{ trans('home.calcium') }} </label>
                <input type="text" name="calcium" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->calcium ?? null }}"  placeholder=" {{ trans('home.calcium') }} ">
 
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
                    <option value="more than" @if(!empty($nutritionFacts->total_fat_calories) && $nutritionFacts->total_fat_calories == 'more than') {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                    <option value="equal" @if(!empty($nutritionFacts->total_fat_calories) && $nutritionFacts->total_fat_calories == 'equal') {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                </select>

               @if ($errors->has('total_fat_calories'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('total_fat_calories') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('total_fat_2000') ? ' has-error' : '' }}">
                <label> {{ trans('home.total_fat_2000') }} </label>
                <input type="text" name="total_fat_2000" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_fat_2000 ?? null }}"  placeholder=" {{ trans('home.total_fat_2000') }} ">
 
                @if ($errors->has('total_fat_2000'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('total_fat_2000') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('total_fat_2500') ? ' has-error' : '' }}">
                <label> {{ trans('home.total_fat_2500') }} </label>
                <input type="text" name="total_fat_2500" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_fat_2500 ?? null }}"  placeholder=" {{ trans('home.total_fat_2500') }} ">
 
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
                    <option value="more than" @if(!empty($nutritionFacts->sat_fat_calories) && $nutritionFacts->sat_fat_calories == 'more than') {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                    <option value="equal" @if(!empty($nutritionFacts->sat_fat_calories) && $nutritionFacts->sat_fat_calories == 'equal') {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
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
               <input type="text" name="sat_fat_2000" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->sat_fat_2000 ?? null }}"  placeholder=" {{ trans('home.sat_fat_2000') }} ">

               @if ($errors->has('sat_fat_2000'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('sat_fat_2000') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('sat_fat_2500') ? ' has-error' : '' }}">
                <label> {{ trans('home.sat_fat_2500') }} </label>
                <input type="text" name="sat_fat_2500" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->sat_fat_2500 ?? null }}"  placeholder=" {{ trans('home.sat_fat_2500') }} ">
 
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
                    <option value="more than" @if(!empty($nutritionFacts->cholesterol_calories) && $nutritionFacts->cholesterol_calories == 'more than') {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                    <option value="equal" @if(!empty($nutritionFacts->cholesterol_calories) && $nutritionFacts->cholesterol_calories == 'equal') {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                </select>  
                @if ($errors->has('cholesterol_calories'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('cholesterol_calories') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('cholesterol_2000') ? ' has-error' : '' }}">
                <label> {{ trans('home.cholesterol_2000') }} </label>
                <input type="text" name="cholesterol_2000" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->cholesterol_2000 ?? null }}"  placeholder=" {{ trans('home.cholesterol_2000') }} ">
 
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
               <input type="text" name="cholesterol_2500" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->cholesterol_2500 ?? null }}"  placeholder=" {{ trans('home.cholesterol_2500') }} ">

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
                    <option value="more than" @if(!empty($nutritionFacts->sodium_calories) && $nutritionFacts->sodium_calories == 'more than') {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                    <option value="equal" @if(!empty($nutritionFacts->sodium_calories) && $nutritionFacts->sodium_calories == 'equal') {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
                </select>   
                @if ($errors->has('sodium_calories'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('sodium_calories') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('sodium_2000') ? ' has-error' : '' }}">
                <label> {{ trans('home.sodium_2000') }} </label>
                <input type="text" name="sodium_2000" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->sodium_2000 ?? null }}"  placeholder=" {{ trans('home.sodium_2000') }} ">
 
                @if ($errors->has('sodium_2000'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('sodium_2000') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('sodium_2500') ? ' has-error' : '' }}">
                <label> {{ trans('home.sodium_2500') }} </label>
                <input type="text" name="sodium_2500" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->sodium_2500 ?? null }}"  placeholder=" {{ trans('home.sodium_2500') }} ">
 
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
                <option value="more than" @if(!empty($nutritionFacts->total_carbohydrates_calories) && $nutritionFacts->total_carbohydrates_calories == 'more than') {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                <option value="equal" @if(!empty($nutritionFacts->total_carbohydrates_calories) && $nutritionFacts->total_carbohydrates_calories == 'equal') {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
            </select>   
               @if ($errors->has('total_carbohydrates_calories'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('total_carbohydrates_calories') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates_fat_2000') ? ' has-error' : '' }}">
                <label> {{ trans('home.total_carbohydrates_fat_2000') }} </label>
                <input type="text" name="total_carbohydrates_fat_2000" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_carbohydrates_fat_2000 ?? null }}"  placeholder=" {{ trans('home.total_carbohydrates_fat_2000') }} ">
 
                @if ($errors->has('total_carbohydrates_fat_2000'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('total_carbohydrates_fat_2000') }}</strong>
                    </span>
                @endif
             </div>
             
            <div class="col-lg-3 col-md-3 {{ $errors->has('total_carbohydrates_fat_2500') ? ' has-error' : '' }}">
                <label> {{ trans('home.total_carbohydrates_fat_2500') }} </label>
                <input type="text" name="total_carbohydrates_fat_2500" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->total_carbohydrates_fat_2500 ?? null }}"  placeholder=" {{ trans('home.total_carbohydrates_fat_2500') }} ">
 
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
                    <option value="more than" @if(!empty($nutritionFacts->dietary_fiber_calories) && $nutritionFacts->dietary_fiber_calories == 'more than') {{ 'selected' }} @endif> {{ trans('home.more_than') }} </option>
                    <option value="equal" @if(!empty($nutritionFacts->dietary_fiber_calories) && $nutritionFacts->dietary_fiber_calories == 'equal') {{ 'selected' }} @endif> {{ trans('home.equal') }} </option>
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
               <input type="text" name="dietary_fiber_2000" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->dietary_fiber_2000 ?? null }}"  placeholder=" {{ trans('home.dietary_fiber_2000') }} ">

               @if ($errors->has('dietary_fiber_2000'))
                   <span class="help-block" style="color:red">
                       <strong>{{ $errors->first('dietary_fiber_2000') }}</strong>
                   </span>
               @endif
            </div>
            
            <div class="col-lg-3 col-md-3 {{ $errors->has('dietary_fiber_2500') ? ' has-error' : '' }}">
                <label> {{ trans('home.dietary_fiber_2500') }} </label>
                <input type="text" name="dietary_fiber_2500" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->dietary_fiber_2500 ?? null }}"  placeholder=" {{ trans('home.dietary_fiber_2500') }} ">
 
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
                <textarea name="more_info_en" required class="form-control" rows="10" placeholder=" {{ trans('home.more_info_en') }}  ">{{ $nutritionFacts->more_info_en ?? null }}</textarea>
                @if ($errors->has('more_info_en'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('more_info_en') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('more_info_ar') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.more_info_ar') }}  <span class="text-danger">*</span>  </label>
                <textarea name="more_info_ar" required class="form-control" rows="10" placeholder=" {{ trans('home.more_info_ar') }}   ">{{ $nutritionFacts->more_info_ar ?? null }}</textarea>
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
                    <label class="custom-file-label" for="customFile"> {{ trans('home.allergens_icon_msg') }}   </label>
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
                <input type="text" name="allergens_en_title" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->allergens_en_title ?? null }}"  placeholder=" {{ trans('home.allergens_en_title') }} ">

                @if ($errors->has('allergens_en_title'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('allergens_en_title') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="col-lg-4 col-md-3 {{ $errors->has('allergens_ar_title') ? ' has-error' : '' }}">
                <label> {{ trans('home.allergens_ar_title') }} </label>
                <input type="text" name="allergens_ar_title" required onkeypress="return isNumberKey(event)" class="form-control m-input" value="{{ $nutritionFacts->allergens_ar_title ?? null }}"  placeholder=" {{ trans('home.allergens_ar_title') }} ">

                @if ($errors->has('allergens_ar_title'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('allergens_ar_title') }}</strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('allergens_en_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.allergens_en_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="allergens_en_description" required class="form-control" rows="10" placeholder=" {{ trans('home.allergens_en_description') }}  ">{{ $nutritionFacts->allergens_en_description ?? null }}</textarea>
                @if ($errors->has('allergens_en_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('allergens_en_description') }} </strong>
                    </span>
                @endif
            </div>

            <div class="col-sm-6 {{ $errors->has('allergens_ar_description') ? ' has-error' : '' }}" style="margin-top: 30px">
                <label> {{ trans('home.allergens_ar_description') }}  <span class="text-danger">*</span>  </label>
                <textarea name="allergens_ar_description" required class="form-control" rows="10" placeholder=" {{ trans('home.allergens_ar_description') }}   ">{{ $nutritionFacts->allergens_ar_description ?? null }}</textarea>
                @if ($errors->has('allergens_ar_description'))
                    <span class="help-block" style="color:red">
                        <strong>{{ $errors->first('allergens_ar_description') }} </strong>
                    </span>
                @endif
            </div>

        </div>


        <button type="submit" style="margin-bottom:50px" form="edit" class="btn btn-primary mr-2">{{ trans('home.update') }} </button>


    {!! Form::close() !!}
    <!--end::Form-->











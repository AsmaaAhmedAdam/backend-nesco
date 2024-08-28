<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'en_title'                     => 'required|string',
            'ar_title'                     => 'required|string',
            'en_description'               => 'required|string',
            'en_description'               => 'required|string',
            'category_id'                  => 'required|exists:categories,id',
            'pic'                          => 'required|image|mimes:png,jpg,jpeg',
            'has_nutrition_facts'          => 'required|boolean',
            'serving_size'                 => 'required_if:has_nutrition_facts,==,1', 
            'serving_per_container'        => 'required_if:has_nutrition_facts,==,1', 
            'calories'                     => 'required_if:has_nutrition_facts,==,1', 
            'calories_from_fat'            => 'required_if:has_nutrition_facts,==,1', 
            'total_fat'                    => 'required_if:has_nutrition_facts,==,1',
            'saturated_fat'                => 'required_if:has_nutrition_facts,==,1', 
            'trans_fat'                    => 'required_if:has_nutrition_facts,==,1', 
            'cholesterol'                  => 'required_if:has_nutrition_facts,==,1', 
            'sodium'                       => 'required_if:has_nutrition_facts,==,1', 
            'total_carbohydrates'          => 'required_if:has_nutrition_facts,==,1', 
            'dietary_fiber'                => 'required_if:has_nutrition_facts,==,1', 
            'total_sugar'                  => 'required_if:has_nutrition_facts,==,1', 
            'added_suger'                  => 'required_if:has_nutrition_facts,==,1', 
            'protein'                      => 'required_if:has_nutrition_facts,==,1', 
            'iron'                         => 'required_if:has_nutrition_facts,==,1', 
            'calcium'                      => 'required_if:has_nutrition_facts,==,1', 
            'more_info_ar'                 => 'required_if:has_nutrition_facts,==,1', 
            'more_info_en'                 => 'required_if:has_nutrition_facts,==,1',
            'total_fat_calories'           => 'required_if:has_nutrition_facts,==,1', 
            'total_fat_2000'               => 'required_if:has_nutrition_facts,==,1', 
            'total_fat_2500'               => 'required_if:has_nutrition_facts,==,1', 
            'sat_fat_calories'             => 'required_if:has_nutrition_facts,==,1', 
            'sat_fat_2000'                 => 'required_if:has_nutrition_facts,==,1', 
            'sat_fat_2500'                 => 'required_if:has_nutrition_facts,==,1',
            'cholesterol_calories'         => 'required_if:has_nutrition_facts,==,1', 
            'cholesterol_2000'             => 'required_if:has_nutrition_facts,==,1', 
            'cholesterol_2500'             => 'required_if:has_nutrition_facts,==,1', 
            'sodium_calories'              => 'required_if:has_nutrition_facts,==,1', 
            'sodium_2000'                  => 'required_if:has_nutrition_facts,==,1', 
            'sodium_2500'                  => 'required_if:has_nutrition_facts,==,1', 
            'total_carbohydrates_calories' => 'required_if:has_nutrition_facts,==,1', 
            'total_carbohydrates_fat_2000' => 'required_if:has_nutrition_facts,==,1', 
            'total_carbohydrates_fat_2500' => 'required_if:has_nutrition_facts,==,1', 
            'dietary_fiber_calories'       => 'required_if:has_nutrition_facts,==,1', 
            'dietary_fiber_2000'           => 'required_if:has_nutrition_facts,==,1', 
            'dietary_fiber_2500'           => 'required_if:has_nutrition_facts,==,1',
            'allergens_icon'               => 'required_if:has_nutrition_facts,==,1',
            'allergens_ar_title'           => 'required_if:has_nutrition_facts,==,1', 
            'allergens_en_title'           => 'required_if:has_nutrition_facts,==,1', 
            'allergens_ar_description'     => 'required_if:has_nutrition_facts,==,1',
            'allergens_en_description'     => 'required_if:has_nutrition_facts,==,1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'code' =>403,
            'message' => implode(', ', $validator->errors()->all()),
        ], 500));
    }
}

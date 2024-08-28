<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMenuRequest extends FormRequest
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
            'en_title'            => 'nullable|string',
            'ar_title'            => 'nullable|string',
            'en_description'      => 'nullable|string',
            'en_description'      => 'nullable|string',
            'category_id'         => 'nullable|exists:categories,id',
            'pic'                 => 'nullable|image|mimes:png,jpg,jpeg',
            'has_nutrition_facts' => 'nullable|boolean',
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

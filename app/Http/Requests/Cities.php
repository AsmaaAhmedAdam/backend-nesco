<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Cities extends FormRequest
{
    public function get_lang()
    {
        $lang = session()->get('admin_lang');

        if($lang == 'en' && $lang != null) {
            return $lang;
        } else {
            return 'ar';
        }
    }

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

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'en_name' => 'required|unique:cities',
                    'ar_name' => 'required|unique:cities',
                    'shipping_value' => 'required|numeric|min:0'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'en_name' => 'required|unique:cities,en_name,' . $this->get('id'),
                    'ar_name' => 'required|unique:cities,ar_name,' . $this->get('id'),
                    'shipping_value' => 'required|numeric|min:0'
                ];

            }
            default:break;
        }

    }



    public function messages()
    {

        $lang = $this->get_lang();

        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }

        if($lang == 'ar') {

            // use trans instead on Lang
            return [
                'en_name.required' => ' الأسم باللغة الانجليزية مطلوب ',
                'ar_name.required' => 'الأسم باللغة بالعربية مطلوب',
                'en_name.unique' => 'الأسم باللغة الانجليزية لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'ar_name.unique' => 'الأسم باللغة بالعربية لا يجب اي يحتوي علي قيم موجوده مسبقا',

                'shipping_value.required' =>  'قيمة الشحن مطلوب',
                'shipping_value.numeric' =>  'قيمة الشحن يجب ان يحتوي علي ارقام',
                'shipping_value.min' =>  'قيمة الشحن يجب ان يحتوي علي الاقل 0',
            ];

        } else {

            return [
                'en_name.required' => ' en name required ',
                'ar_name.required' => ' ar name required ',
                'en_name.unique' => 'en name must have unique values',
                'ar_name.unique' => 'ar name must have unique values',

                'shipping_value.required' =>  'shipping value is required',
                'shipping_value.numeric' =>  'shipping value must be numeric',
                'shipping_value.min' =>  'shipping value must be at least zero',

            ];
        }

    }


}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Categories extends FormRequest
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
                    'en_title' => 'required|unique:product|unique:categories',
                    'ar_title' => 'required|unique:product|unique:categories',

                    'pic' => 'required|image|mimes:jpg,png,jpeg|max:1024',

                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'en_title' => 'required|unique:product|unique:categories,en_title,' . $this->get('id'),
                    'ar_title' => 'required|unique:product|unique:categories,ar_title,' . $this->get('id'),

                    'pic' => 'nullable|image|mimes:jpg,png,jpeg|max:1024',

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
                'en_title.required' => ' الأسم باللغة الانجليزية مطلوب ',
                'ar_title.required' => 'الأسم باللغة بالعربية مطلوب',
                'en_title.unique' => 'الأسم باللغة الانجليزية لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'ar_title.unique' => 'الأسم باللغة بالعربية لا يجب اي يحتوي علي قيم موجوده مسبقا',

                'pic.required' => ' الصورة مطلوبة',
                'pic.image' =>  'الصورة يجب ان تكون صورة',
                'pic.mimes' =>  'يجب أن يكون امتداد الصورة jpg و png و jpeg',
                'pic.max' =>  'حجم الصورة لا يجب ان يزيد عن واحد ميجا',

            ];

        } else {

            return [
                'en_title.required' => ' en name required ',
                'ar_title.required' => ' ar name required ',
                'en_title.unique' => 'en name must have unique values',
                'ar_title.unique' => 'ar name must have unique values',

                'pic.required' => '  image required',
                'pic.image' =>  'image must be image file',
                'pic.mimes' =>  'image must have only extensions jpg,png,jpeg',
                'pic.max' =>  'image size must be less than or equal 1 mega',

            ];
        }

    }



}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Product extends FormRequest
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

                    'category_id' => 'required',

                    'discount' => 'required|numeric|min:0',
                    'price_before_discount' => 'required|numeric|min:1',

                    'en_description' => 'required',
                    'ar_description' => 'required',

                    'en_title' => 'required|unique:categories|unique:product',
                    'ar_title' => 'required|unique:categories|unique:product',

                    'pic' => 'required|image|mimes:jpg,png,jpeg|max:1024',

                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [

                    'category_id' => 'required',

                    'discount' => 'required|numeric|min:0',
                    'price_before_discount' => 'required|numeric|min:1',

                    'en_description' => 'required',
                    'ar_description' => 'required',

                    'en_title' => 'required|unique:categories|unique:product,en_title,' . $this->get('id'),
                    'ar_title' => 'required|unique:categories|unique:product,ar_title,' . $this->get('id'),

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

                's_no.unique' => 'رقم التسلسل لا يجب اي يحتوي علي قيم موجوده مسبقا',

                'size_id.required' =>  'مقاس المنتج مطلوبة',

                'price_before_discount.required' =>  'السعر قبل الخصم مطلوب',
                'price_before_discount.numeric' =>  'السعر قبل الخصم يجب ان يحتوي علي ارقام',
                'price_before_discount.min' =>  'السعر قبل الخصم يجب ان يحتوي علي الاقل 1',

                'discount.required' =>  'الخصم  مطلوب',
                'discount.numeric' =>  'الخصم  يجب ان يحتوي علي ارقام',
                'discount.min' =>  'الخصم  يجب ان يحتوي علي الاقل 0',
                'discount.max' =>  'الخصم  يجب ان يحتوي علي الاكثر 100',

                'pic.required' => ' الصورة مطلوبة',
                'pic.image' =>  'الصورة يجب ان تكون صورة',
                'pic.mimes' =>  'يجب أن يكون امتداد الصورة jpg و png و jpeg',
                'pic.max' =>  'حجم الصورة لا يجب ان يزيد عن واحد ميجا',

                'category_id' => 'القسم مطلوب',

                'en_description.required' => ' الوصف باللغة الانجليزية مطلوب ',
                'ar_description.required' => 'الوصف باللغة بالعربية مطلوب',

            ];

        } else {

            return [
                'en_title.required' => ' en name required ',
                'ar_title.required' => ' ar name required ',
                'en_title.unique' => 'en name must have unique values',
                'ar_title.unique' => 'ar name must have unique values',

                's_no.unique' => 'serial number must have unique values',

                'size_id.required' =>  'size is required',

                'price_before_discount.required' =>  'price before discount is required',
                'price_before_discount.numeric' =>  'price before discount must be numeric',
                'price_before_discount.min' =>  'price before discount must be at least one',

                'discount.required' =>  'discount is required',
                'discount.numeric' =>  'discount must be numeric',
                'discount.min' =>  'discount must be at least zero',
                'discount.max' =>  'discount must be at most 100',

                'pic.required' => '  image required',
                'pic.image' =>  'image must be image file',
                'pic.mimes' =>  'image must have only extensions jpg,png,jpeg',
                'pic.max' =>  'image size must be less than or equal 1 mega',

                'category_id' => 'category id is required',

                'en_description.required' => ' en description required ',
                'ar_description.required' => ' ar description required ',

            ];
        }

    }






}

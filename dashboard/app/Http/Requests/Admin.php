<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin extends FormRequest
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
                    'name' => 'required|unique:admin',
                    'mobile' => 'required|numeric|unique:admin',
                    'email' => 'required|email|unique:admin',
                    'password' => 'required|min:6',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:admin,name,' . $this->get('id'),
                    'mobile' => 'required|numeric|unique:admin,mobile,' . $this->get('id'),
                    'email' => 'required|email|unique:admin,email,' . $this->get('id'),
                    'password' => 'nullable',
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

        if ($lang == 'ar') {

            // use trans instead on Lang
            return [
                'name.required' => 'الأسم مطلوب',
                'password.required' => 'كلمة المرور مطلوب',
                'mobile.required' => 'رقم الموبيل مطلوب',
                'mobile.numeric' => 'رقم الموبيل يجب ان يحتوي علي ارقام فقط',
                'email.required' => 'البريد الألكتروني مطلوب',


                'name.unique' => 'الأسم لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'password.min' => 'كلمه المرور لابد ان تحتوي علي الاقل ٦ ارقام',
                'email.email' => 'يجب ان يحتوي البريد الألكتروني علي بريد الكتروني',
                'email.unique' => 'البريد الألكتروني لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'mobile.unique' => 'رقم الموبيل لا يجب اي يحتوي علي قيم موجوده مسبقا',
            ];

        } else {

            // use trans instead on Lang
            return [
                'name.required' => 'name is required',
                'password.required' => 'password is required',
                'mobile.required' => 'mobile is required',
                'mobile.numeric' => 'mobile must be numeric',
                'email.required' => 'email is required',


                'name.unique' => 'name must be unique',
                'password.min' => 'min password is 6 characters',
                'email.email' => 'email must be valid email',
                'email.unique' => 'email must be unique',
                'mobile.unique' => 'mobile must be unique',
            ];

        }
    }



}

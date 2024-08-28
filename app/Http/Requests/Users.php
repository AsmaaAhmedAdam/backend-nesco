<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Users extends FormRequest
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

                    'mobile' => 'required|numeric|unique:admin|unique:users',
                    'email' => 'required|email|unique:admin|unique:users',
                    'name' => 'required',
                    'password' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [

                    'mobile' => 'required|numeric|unique:admin|unique:users,mobile,' . $this->get('id'),
                    'email' => 'required|email|unique:admin|unique:users,email,' . $this->get('id'),
                    'name' => 'required',
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
                'name.unique' => 'الأسم لا يجب اي يحتوي علي قيم موجوده مسبقا',

                'password.required' => 'كلمة المرور مطلوب',
                'password.min' => 'كلمه المرور لابد ان تحتوي علي الاقل ٦ ارقام',

                'mobile.required' => 'رقم الموبيل مطلوب',
                'mobile.numeric' => 'رقم الموبيل يجب ان يحتوي علي ارقام فقط',
                'mobile.unique' => 'رقم الموبيل لا يجب اي يحتوي علي قيم موجوده مسبقا',

                'email.required' => 'البريد الألكتروني مطلوب',
                'email.email' => 'يجب ان يحتوي البريد الألكتروني علي بريد الكتروني',
                'email.unique' => 'البريد الألكتروني لا يجب اي يحتوي علي قيم موجوده مسبقا',
            ];

        } else {

            // use trans instead on Lang
            return [
                'name.required' => 'name is required',
                'name.unique' => 'name must be unique',

                'password.required' => 'password is required',
                'password.min' => 'min password is 6 characters',

                'mobile.required' => 'mobile is required',
                'mobile.numeric' => 'mobile must be numeric',
                'mobile.unique' => 'mobile must be unique',

                'email.required' => 'email is required',
                'email.email' => 'email must be valid email',
                'email.unique' => 'email must be unique',


            ];

        }
    }



}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Faq extends FormRequest
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
                    'en_title' => 'required',
                    'ar_title' => 'required',
                    'en_description' => 'required',
                    'ar_description' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'en_title' => 'required',
                    'ar_title' => 'required',
                    'en_description' => 'required',
                    'ar_description' => 'required',
                ];

            }
            default:break;
        }


    }



}

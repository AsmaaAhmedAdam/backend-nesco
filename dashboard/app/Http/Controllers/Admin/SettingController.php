<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin as modelRequest;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;


class SettingController extends Controller
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

    public function setting()
    {
        $Setting = Setting::first();
        return view('admin.layouts.setting',compact('Setting'));
    }

    public function update_setting(Request $request)
    {
        $lang = $this->get_lang();
        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }
        if ($lang == 'ar') {
            $messages_arr = [
                'website_name.required' => 'اسم التطبيق مطلوب',
                'whatsapp.required' => 'رقم الواتس مطلوب',
                'mobile.required' => 'رقم الموبيل مطلوب',
                'mobile.numeric' => 'رقم الموبيل يجب ان يحتوي علي ارقام فقط',
                'email.required' => 'البريد الألكتروني مطلوب',
                'email.email' => 'يجب ان يحتوي البريد الألكتروني علي بريد الكتروني',
                'email.unique' => 'البريد الألكتروني لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'mobile.unique' => 'رقم الموبيل لا يجب اي يحتوي علي قيم موجوده مسبقا',

                'android_link' => 'رابط تطبيق الاندرويد مطلوب',
                'ios_link' => 'رابط تطبيق ال ios  مطلوب',

            ];
        } else {
            $messages_arr = [
                'website_name.required' => 'application name is required',
                'password.required' => 'password is required',
                'mobile.required' => 'mobile is required',
                'mobile.numeric' => 'mobile must be numeric',
                'email.required' => 'email is required',

                'name.unique' => 'name must be unique',
                'email.email' => 'email must be valid email',
                'email.unique' => 'email must be unique',
                'mobile.unique' => 'mobile must be unique',

                'android_link' => 'android link is required',
                'ios_link' => 'ios link is required',
            ];
        }
        $request->validate([
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'whatsapp' => 'required',
            'android_link' => 'required',
            'ios_link' => 'required',
        ]);
        $Setting = Setting::first();
        $arr = $request->except(['_token']);
        $rows = $Setting->update($arr);
        if($rows) {
            \Cache::forever('site_setting', $Setting);
        }
        return redirect()->back()->with('info',trans('home.update_msg'));
    }



    // policy
    public function policy()
    {
        $Setting = Setting::first();
        return view('admin.layouts.policy',compact('Setting'));
    }


    // update_policy
    public function update_policy(Request $request)
    {
        $request->validate([
            'en_policy' => 'required',
            'ar_policy' => 'required',
        ]);
        $Setting = Setting::first();
        $arr = $request->except(['_token']);
        $rows = $Setting->update($arr);
        if($rows) {
            \Cache::forever('site_setting', $Setting);
        }
        return redirect()->back()->with('info',trans('home.update_msg'));

    }


}

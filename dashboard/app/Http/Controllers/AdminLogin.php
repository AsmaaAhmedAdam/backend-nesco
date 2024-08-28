<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminLogin extends Controller
{


    public function login() {

        $lang = app()->getLocale();

        session()->put('locale',$lang);
        app()->setLocale($lang);
       

        if(Auth::guard('admin')->check()) {

            return redirect('admin_panel');

        } else {            
            return view('admin.layouts.login');
        }
        
       
    }


    public function login_post(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'lang' => 'required'
        ]);
        

        $remember = $request->has('remember') ? true : false;

        if ( Auth::guard('admin')->attempt([ 'email' => request('email') , 'password' => request('password')] , $remember) ) {
           
            $lang = $request->lang;

            if($lang == 'en' || $lang == 'ar') {
                session()->put('admin_lang',$lang);
            } else {
                session()->put('admin_lang','en');
            }
 
            return redirect('admin_panel');

        } else {
            return redirect()->back()->with('error','Please Check Your User Name and Password again');
        }
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        return redirect('admin_panel/login');
    }


}

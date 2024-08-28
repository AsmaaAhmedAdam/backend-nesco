<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends Controller
{

    use GeneralTrait;

    public $user;
    // public $lang;


    public function __construct()
    {
        // if(getallheaders() != null && ! empty(getallheaders()) && array_key_exists('language',getallheaders())) {
        //     $this->lang = getallheaders()['language'];
        // } else {
        //     $this->lang = null;
        // }

    }


    // forget_password
    public function forget_password(Request $request)
    {
        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $validated_arr = [
            'email' => 'required|email|exists:users',
        ];

        $custom_messages =  [
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'يجب ان يحتوي البريد الالكتروني علي بريد الكتروني صالح',
            'email.exists' => ' عفوا هذا البريد الالكتروني غير موجود مسبقا',
        ];

        if($lang == 'en') {
            $validator = Validator::make($request->all(), $validated_arr);
        } else {
            $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
        }

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $code = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create([
            'email' => $request->email,
            'code' => $code
        ]);

        //Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));
        
      //  Mail::to($request->email)->send(new SendCodeResetPassword($code));

        if($lang == 'en') {
            return $this->returnSuccessMessage('We have emailed your password reset code!');
        } else {
            return $this->returnSuccessMessage('لقد قمنا بإرسال رمز إعادة تعيين كلمة المرور عبر البريد الإلكتروني!');
        }


    }




    // reset_password
    public function reset_password(Request $request)
    {
        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $validated_arr = [
            //'token' => 'required',
            'email' => 'required|email|exists:reset_code_passwords',
            'code' => 'required|exists:reset_code_passwords',
            'password' => 'required|min:6',
        ];

        $custom_messages =  [
            'token.required' => 'التوكن مطلوب',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'يجب ان يحتوي البريد الالكتروني علي بريد الكتروني صالح',
            'email.exists' => ' برجاء التحقق من البريد الالكتروني علي رمز الأرسال أو أعد أستعاده كلمة المرور مره أخري',
            'code.required' => 'الكود مطلوب',
            'code.exists' => ' عفوا هذا الكود غير موجود مسبقا',
            'password.required' => 'كلمة المرور مطلوب',
            'password.min' => 'كلمه المرور لابد ان تحتوي علي الاقل 6 ارقام',
        ];

        if($lang == 'en') {
            $validator = Validator::make($request->all(), $validated_arr);
        } else {
            $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
        }

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        // find the code
        $passwordReset = ResetCodePassword::where('code', $request->code)->where('email', $request->email)->first();

        if($passwordReset == null) {
            if($lang == 'en') {
                return $this->returnError('E200','Sorry, please check your code and email again');
            } else {
                return $this->returnError('E200','عفوا برجاء التحقق من الكود والبريد الألكتروني  مرة أخرى');
            }
        }

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            if($lang == 'en') {
                return $this->returnError('E200','Sorry, this code has expired. Please reset your password again');
            } else {
                return $this->returnError('E200','عفوا صلاحية هذا الكود انتهت برجاء اعاده استعاده كلمة المرور مره اخري');
            }
        }

        // find user's email
        $user = User::where('email', $request->email)->first();

        if($user == null) {
            if($lang == 'en') {
                return $this->returnError('E200','Sorry, something went wrong, please try again');
            } else {
                return $this->returnError('E200','عفوا لقد حدث خطأ ما برجاء المحاولة مره اخري');
            }
        }

        //$token = $user->api_token;
        //$token = JWTAuth::fromUser($user);


        // update user password
        $user->update(['password' => bcrypt($request->password)]);

        // delete current code
        $passwordReset->delete();

        if($lang == 'en') {
            return $this->returnSuccessMessage('Your password has been reset!');
        } else {
            return $this->returnSuccessMessage('تم إعادة تعيين كلمة المرور الخاصة بك!');
        }

    }





}

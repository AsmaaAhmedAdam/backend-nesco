<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\ApiResource\UserProfileDetailsResource;
use App\Http\Requests\{AttachmentRequest};
use App\Services\UploadFileService;

class AuthController extends Controller
{

    use GeneralTrait;

    public $user;
    // public $lang;


    public function __construct()
    {
        // $user = JWTAuth::parseToken()->authenticate();
        $auth_user = Auth::guard('user-api')->user();
        if( $auth_user != null) {
            $this->user = User::where('id',$auth_user->id)->first();
        } else {
            $this->user = null;
        }

        // if(getallheaders() != null && ! empty(getallheaders()) && array_key_exists('language',getallheaders())) {
        //     $this->lang = getallheaders()['language'];
        // } else {
        //     $this->lang = null;
        // }

    }
    
       public function storeAttachment(AttachmentRequest $request)
    {
    //  dd('ddd');

        $name = null;
        try {
            if ($request->file) {
                if ($request->attachment_type == 'image') {
                    $name = UploadFileService::uploadImg($request->file, $request->model);
                }
                elseif($request->attachment_type == 'file') {
                    $name =  UploadFileService::uploadFile($request->file, $request->model);
                }elseif($request->attachment_type == 'video'){
                    $name =  UploadFileService::uploadVideo($request->file, $request->model);
                }
            }
            // else{
            //     foreach($request->files)
            // }
            return \response()->json([
                'message' => 'uploaded successfully',
                'status' => 'success',
                'data' => $name,
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['status' => 'fail', 'data' => null, 'messages' => 'something went wrong please try again'], 422);
        }
    }
    
    // public function firebaseadd(Request $request)
    // {
    //     $lang = $request->header("language") ?? 'en';
    //     if(empty($lang)) {
    //         return $this->returnError('E300','language is required');
            
    //     }
    //      try {

    //         $validated_arr = [
    //             'name' => 'required',
    //             'email' => 'required|email|unique:users',
    //             'firebase_id' => 'required|numeric|unique:users',
                
    //         ];

    //         $custom_messages =  [
    //             'name.required' => 'الاسم مطلوب',
    //             'email.required' => 'البريد الالكتروني مطلوب',

    //         ];

    //         if($lang == 'en') {
    //             $validator = Validator::make($request->all(), $validated_arr);
    //         } else {
    //             $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
    //         }

    //         //Send failed response if request is not valid
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code,$validator);
    //         }

    //         if($request->fcm_token != null) {
    //             $fcm_token = $request->fcm_token;
    //         } else {
    //             $fcm_token = null;
    //         }

    //         //Request is valid, create new user
    //         $auth_user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'firebase_id' => $request->mobile,
                
    //         ]);

           

            

    //         $user = User::where('id',$auth_user->id)->select(['id','name', 'email','firebase_id'])->first();

    //         $credentials = $request->only(['email', 'password']);
    //         $token = Auth::guard('user-api')->attempt($credentials);

    //         $user->api_token = $token;

    //         if($lang == 'en') {
    //             return $this->returnData('user',$user,'User created successfully');
    //         } else {
    //             return $this->returnData('user',$user,'تم تسجيل عضوية بنجاح ');
    //         }

    //     } catch(Exception $e) {
    //         //dd($e->getMessage());

    //         return $e->getMessage();

    //         if($lang == 'en') {
    //             return $this->returnError('E200','sorry try again');
    //         } else {
    //             return $this->returnError('E200','عذرا حاول مرة أخرى');
    //         }
    //     }

        
    // }


    // register
    public function register(Request $request)
    {

        $lang = $request->header("language") ?? 'en';
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }



        try {

            $validated_arr = [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'mobile' => 'required|numeric|unique:users',
                'password' => 'required|min:6|max:50|confirmed',
                'password_confirmation' => 'required|min:6|max:50',
            ];

            $custom_messages =  [
                'name.required' => 'الاسم مطلوب',
                'password.required' => 'كلمة المرور مطلوب',
                'password_confirmation.required' => 'تاكيد كلمة المرور مطلوب',
                'mobile.required' => 'رقم الموبيل مطلوب',
                'email.required' => 'البريد الالكتروني مطلوب',


                'name.unique' => 'الاسم لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'password.confirmed' => 'كلمه المرور لابد ان تطابق تاكيد كلمة المرور',
                'password.min' => 'كلمه المرور لابد ان تحتوي علي الاقل 6 ارقام',
                'password.max' => 'كلمه المرور لابد ان تحتوي علي الاكثر 50 ارقام',
                'password_confirmation.min' => 'تاكيد كلمة المرور لابد ان تحتوي علي الاقل 6 ارقام',
                'password_confirmation.max' => 'تاكيد كلمة المرور لابد ان تحتوي علي الاكثر 50 ارقام',

                'mobile.numeric' => 'رقم الموبيل لابد ان يحتوي علي أرقام فقط',
                'email.email' => 'يجب ان يحتوي البريد الالكتروني علي بريد الكتروني',
                'email.unique' => 'البريد الالكتروني لا يجب اي يحتوي علي قيم موجوده مسبقا',
                'mobile.unique' => 'رقم الموبيل لا يجب اي يحتوي علي قيم موجوده مسبقا',

            ];

            if($lang == 'en') {
                $validator = Validator::make($request->all(), $validated_arr);
            } else {
                $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
            }

            //Send failed response if request is not valid
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

            if($request->fcm_token != null) {
                $fcm_token = $request->fcm_token;
            } else {
                $fcm_token = null;
            }

            //Request is valid, create new user
            $auth_user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'device_token' => $fcm_token,
                'password' => bcrypt($request->password),
                'status' => 1,
            ]);

            /////////// send mail
            //Mail::to($auth_user->email)->send(new SendCodeMail($auth_user->code,$auth_user->name));

            

            $user = User::where('id',$auth_user->id)->select(['id','name', 'email','status','mobile','device_token'])->first();

            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('user-api')->attempt($credentials);

            $user->api_token = $token;

            if($lang == 'en') {
                return $this->returnData('user',$user,'User created successfully');
            } else {
                return $this->returnData('user',$user,'تم تسجيل عضوية بنجاح ');
            }

        } catch(Exception $e) {
            //dd($e->getMessage());

            return $e->getMessage();

            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عذرا حاول مرة أخرى');
            }
        }

    }




public function social_login(Request $request)
    {
        $userId = null;
            $user= null;
        if (isset($request->provider_type) && $request->provider_type != null && isset($request->provider_id) && $request->provider_id != null) {
            $user = User::where('provider_type' ,$request->provider_type )
            ->where('provider_id' , $request->provider_id )
                ->first();
                // dd( $request->all());
            $userId = $user ? $user->id : null;
        }
        $lang = $request->header("language") ?? 'en';
        if (empty($lang)) {
            return $this->returnError('E300', 'language is required');
        }

// dd( isset($request->provider_type) && $request->provider_type != null && isset($request->provider_id) && $request->provider_id != null , $user);
        try {
            $validated_arr = [

                'provider_type' => 'required|in:facebook,twitter,google,apple',
                'provider_id' => 'required|unique:users,provider_id,' .  $userId . ',id',
                'name' => 'required|string',
                 'email' => 'required|email|unique:users,email,' . $userId,


            ];
            $validator = Validator::make($request->all(), $validated_arr);
            //Send failed response if request is not valid
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
// dd($user);
            if (!$user) {
                $user = User::create(['name' => $request->name,  'status' => 1 , 'provider_type' => $request->provider_type, 'provider_id' => $request->provider_id ,'email' => $request->email]);
            }

            $user = User::where('id', $user->id)->select(['id', 'name', 'email', 'status', 'mobile', 'device_token', 'provider_type', 'provider_id'])->first();

            $token = \JWTAuth::fromUser($user);
            data_set($user, 'api_token', $token);
            $user->api_token = $token;

            if ($lang == 'en') {
                return $this->returnData('user', $user, 'User signup successfully');
            } else {
                return $this->returnData('user', $user, 'تم تسجيل عضوية بنجاح ');
            }
        } catch (Exception $e) {
            //dd($e->getMessage());

            return $e->getMessage();

            if ($lang == 'en') {
                return $this->returnError('E200', 'sorry try again');
            } else {
                return $this->returnError('E200', 'عذرا حاول مرة أخرى');
            }
        }
    }

    // login
    public function login(Request $request)
    {

        $lang = $request->header("language") ?? 'en';
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $credentials = $request->only('email', 'password');

        $validated_arr = [
            'email' => 'required|exists:users|email',
            'password' => 'required'
        ];

        $custom_messages =  [
            'password.required' => 'كلمة المرور مطلوب',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.exists' => 'عفوا البريد الالكتروني غير موجود مسبقا',
        ];

        if($lang == 'en') {
            $validator = Validator::make($request->all(), $validated_arr);
        } else {
            $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
        }


        //Send failed response if request is not valid
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        //Request is validated

        $token = Auth::guard('user-api')->attempt($credentials);

        //Creat token and return user with token
        try {

            if (! $token) {

                if($lang == 'en') {
                    return $this->returnError('401','please check your email and password again');
                } else {
                    return $this->returnError('401','برجاء التحقق من البريد الالكتروني وكلمة المرور مره اخري');
                }

            } else {

                $auth_user = Auth::guard('user-api')->user();

                $user = User::where('id',$auth_user->id)->select(['id','name', 'email','status','mobile','device_token'])->first();

                if($user->status == 1) {

                    if($request->fcm_token != null) {
                        $user->update([
                            'device_token' => $request->fcm_token
                        ]);
                    }

                    $user = $user->toArray();

                    $user['api_token'] = $token;


                    if($lang == 'en') {
                        return $this->returnData('user',$user,'login successfully');
                    } else {
                        return $this->returnData('user',$user,'تم تسجيل الدخول بنجاح ');
                    }

                } else {

                    JWTAuth::setToken($token)->invalidate();

                    if($lang == 'en') {
                        return $this->returnError('E100','sorry your membership is blocked please contact with owner of this app');
                    } else {
                        return $this->returnError('E100','آسف تم حظر عضويتك ، يرجى الاتصال بمالك هذا التطبيق');
                    }
                }

            }

        } catch (JWTException $e) {

            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عذرا حاول مرة أخرى');
            }
        }

    }
    
    
    
    
  
    
    

    // logout
    public function logout(Request $request)
    {
        
        $lang = app()->getLocale();
        
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $validated_arr = [
            'token' => 'required'
        ];

        $custom_messages =  [
            'token.required' => 'معرف المستخدم مطلوب',
        ];

        if($lang == 'en') {
            $validator = Validator::make($request->all(), $validated_arr);
        } else {
            $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
        }

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

		//Request is validated, do logout
        try {

            //JWTAuth::invalidate($request->token);
            JWTAuth::setToken($request->token)->invalidate();

            if($lang == 'en') {
                return $this->returnSuccessMessage('log out successfully');
            } else {
                return $this->returnSuccessMessage('تم تسجيل الخروج بنجاح');
            }

        } catch (JWTException $exception) {

            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عذرا حاول مرة أخرى');
            }
        }
    }

    /**
     * update user profile.
     *
     * @param  Illuminate\Http\Request $request
     * @return json
     */
    public function update_profile(Request $request)
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        } 
        try {
            if($request->has('old_password') && !Hash::check($request->get('old_password'), $this->user->password)) {
                return $this->returnError('401', trans('api.invalid_password'));
            }
            $validated_arr = [
                'name'     => 'required',
                'email'    => 'required|email|unique:users,email,' . $this->user->id,
                'mobile'   => 'required|numeric|unique:users,mobile,' . $this->user->id,
                'password' => 'nullable|min:6|max:50',
                'address'  => 'nullable',
            ];
            $validator = Validator::make($request->all(), $validated_arr);
            //Send failed response if request is not valid
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
            $arr = [
                'name'    => $request->name,
                'email'   => $request->email,
                'mobile'  => $request->mobile,
                'address' => json_encode($request->address ?? []),
            ];
            if($request->password != null) {
                $arr['password'] = bcrypt($request->password);
            }
            //update user
            $this->user->update($arr);
            $credentials = $request->only([$this->user->email, $this->user->password]);
            $token = Auth::guard('user-api')->attempt($credentials);
            $this->user->api_token = $token;
            return $this->returnData('user', new \App\Http\Resources\APiResource\UserProfileDetailsResource($this->user), trans('api.user_updated_profile_successfully'));
        } catch(Exception $e) {
            dd($e->getMessage().' ' .$e->getFile().'  ' .$e->getLine());
            Log::debug($e->getMessage().' ' .$e->getFile().'  ' .$e->getLine());
            return $this->returnError('E200', trans('api.try_again'));
        }
    }

    /**
     * get user profile.
     *
     * @param  Illuminate\Http\Request $request
     * @return json
     */
    public function get_user(Request $request)
    {
        $validated_arr = [
            'token' => 'required'
        ];
        $validator = Validator::make($request->all(), $validated_arr);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }
        $auth_user = JWTAuth::authenticate($request->token);
        $user = !is_null($auth_user) ? User::where('id',$auth_user->id)->select(['id','name', 'address', 'email','status','mobile','device_token'])->first() : null;
        return $this->returnData('user',new UserProfileDetailsResource($user),'');
    }


    // get-token
    public function get_token(Request $request)
    {
        $lang = app()->getLocale();
        
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }


        $credentials = $request->only('email', 'password');

        $validated_arr = [
            'email' => 'required|exists:users|email',
            'password' => 'required'
        ];

        $custom_messages =  [
            'password.required' => 'كلمة المرور مطلوب',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.exists' => 'عفوا البريد الالكتروني غير موجود مسبقا',
        ];

        if($lang == 'en') {
            $validator = Validator::make($request->all(), $validated_arr);
        } else {
            $validator = Validator::make($request->all(), $validated_arr,$custom_messages);
        }

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        //Request is validated

        $token = Auth::guard('user-api')->attempt($credentials);

        //Creat token and return user with token
        try {

            if (! $token) {

                if($lang == 'en') {
                    return $this->returnError('401','please check your email and password again');
                } else {
                    return $this->returnError('401','برجاء التحقق من البريد الالكتروني وكلمة المرور مره اخري');
                }

            } else {
                return $this->returnData('token',$token,'');
            }

        } catch (JWTException $e) {

            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عذرا حاول مرة أخرى');
            }

        }

    }

    private function unique_code() {

        $uniqueStr = random_int(100000, 999999);

        while(User::where('code', $uniqueStr)->exists()) {

            $uniqueStr = random_int(100000, 999999);
        }

        return $uniqueStr;
    }
}

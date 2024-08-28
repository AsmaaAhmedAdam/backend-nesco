<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\APiResource\Cart_R;
use App\Http\Resources\APiResource\Favorite_R;
use App\Http\Resources\APiResource\Invoice_Details_Data;
use App\Http\Resources\APiResource\Notications_Data_R;
use App\Http\Resources\APiResource\Order_Data;
use App\Models\Invoice;
use App\Models\Invoice_Details;
use App\Models\Invoice_User;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminMail;
use App\Mail\UserMail;
use App\Models\Area;
use App\Models\Cart;
use App\Models\Cities;
use App\Models\Favorite;
use App\Models\Notifications;
use App\Models\Product_Selling;
use App\Models\Reviews;
use App\Models\User;
use App\Traits\GeneralTrait;
use AymanElmalah\MyFatoorah\Facades\MyFatoorah;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;




class ReviewsController extends Controller
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


    // getUserReviews
    public function getUserReviews()
    {
        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        if($this->user == null) {
            if($lang == 'en') {
                return $this->returnError('403','Unauthenticated user');
            }
            return $this->returnError('403','يجب تسجيل الدخول اولا');
        }

        if($lang == 'en') {
            $msg = 'User reviews';
        } else {
            $msg = 'تقييمات المستخدم';
        }
        $value = ($this->user->reviews->isEmpty()) ? [] : $this->user->reviews;
        return $this->returnData('user reviews', $value, $msg);
    }

    // addReview
    public function addReview(Request $request)
    {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        if($this->user == null) {
            if($lang == 'en') {
                return $this->returnError('403','Unauthenticated user');
            } else {
                return $this->returnError('403','يجب تسجيل الدخول اولا');
            }
        }

        $validated_arr = [
            'product_id' => 'required|numeric|exists:product,id',
            'value' => 'required|min:1|max:5',
        ];

        $custom_messages = [
            'product_id.required' => ' المنتج مطلوب',
            'product_id.numeric' => 'المننتج يجب ان يحتوي علي ارقام',
            'product_id.exists' => 'عفوا هذا المننتج غير موجود ',
            'value.required' => ' القيمة مطلوبة',
            'value.min' => ' القيمة يجب ان يحتوي علي الاقل علي قيمة 1',
            'value.max' => ' القيمة يجب ان يحتوي علي الاكثر علي قيمة 5',
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


        $product_id = $request->product_id;
        $value = $request->value;
        $notes = $request->notes;
        $status = 'hold';

        $product = Product::where('id',$product_id)->first();

        if (!$product) {
            if($lang == 'en') {
                return $this->returnError('404','sorry this product not found');
            } else {
                return $this->returnError('404','عفوا هذا المنتج غير موجود');
            }
        }

        $check_review = Reviews::where('user_id',$this->user->id)->where('product_id',$product_id)->first();

        // if cart is empty then this the first product
        if($check_review == null) {

            $review = Reviews::create([
                'user_id' => $this->user->id,
                'product_id' => $product_id,
                'value' => $value,
                'notes' => $notes,
                'status' => $status,
            ]);

            if($lang == 'en') {
                $msg = 'review added successfully and waitting admin for accepting your review';
            } else {
                $msg = ' تم  إضافة تقيم للمنتج بنجاح وفي انتظار موافقة الاداره  ';
            }

            Notifications::create([
                'add_by' => 'user',
                'user_id' => $this->user->id,
                'send_to_type' => 'admin',
                'send_to_id' => '1',
                'en_description' => 'new review is added successfully for product ( '.$product->en_title.' )  ',
                'ar_description' => 'تمت إضافة تقيم جديد للمنتج ( '.$product->ar_title.' )  ',
                'url' => null,
                'seen' => 0,
                'type' => 'review',
                'item_id' => $review->id,
                'en_title' => 'new review',
                'ar_title' => ' تقيم جديد ',
            ]);

            return $this->returnData('review',$review,$msg);


        } else {

            if($lang == 'en') {
                $msg = ' This product already having rate';
            } else {
                $msg = 'عفوا هذا المنتج يحتوي علي تقيم من قبل';
            }

            return $this->returnData('review',null,$msg);

        }


    }


    // UpdateReview
    public function UpdateReview(Request $request)
    {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        if($this->user == null) {
            if($lang == 'en') {
                return $this->returnError('403','Unauthenticated user');
            } else {
                return $this->returnError('403','يجب تسجيل الدخول اولا');
            }
        }

        $validated_arr = [
            'row_id' => 'required|numeric|exists:reviews,id',
            'value' => 'required|min:1|max:5',
        ];

        $custom_messages = [
            'row_id.required' => ' رقم التقيم مطلوب',
            'row_id.numeric' => 'رقم التقيم يجب ان يحتوي علي ارقام',
            'row_id.exists' => 'عفوا رقم التقيم غير موجود ',
            'value.required' => ' القيمة مطلوبة',
            'value.min' => ' القيمة يجب ان يحتوي علي الاقل علي قيمة 1',
            'value.max' => ' القيمة يجب ان يحتوي علي الاكثر علي قيمة 5',
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


        $row_id = $request->row_id;
        $value = $request->value;
        $notes = $request->notes;


        $check_review = Reviews::where('user_id',$this->user->id)->where('id',$row_id)->first();

        // if cart is empty then this the first product
        if($check_review != null) {

            $product = Product::where('id',$check_review->product_id)->first();

            $check_review->update([
                'value' => $value,
                'notes' => $notes,
            ]);

            $product_reviews_sum = Reviews::where('product_id',$check_review->product_id)->where('status','accept')->sum('value');
            $product_reviews_count = Reviews::where('product_id',$check_review->product_id)->where('status','accept')->select('id')->count();

            if($product_reviews_count > 0) {
                $calc = ( round($product_reviews_sum/$product_reviews_count) );
            } else {
                $calc = 0;
            }

            $product->update([
                'reviews' => $calc
            ]);

            if($lang == 'en') {
                $msg = 'review is updated successfully';
            } else {
                $msg = 'تمت تحديث التقيم بنجاح  ';
            }

            return $this->returnData('review',$check_review,$msg);

        } else {

            if($lang == 'en') {
                $msg = ' sorry This product not having rate yet !';
            } else {
                $msg = 'عفوا هذا المنتج لا يحتوي علي تقيم بعد';
            }

            return $this->returnData('review',null,$msg);

        }


    }




    // notifications
    public function notifications($id = null) {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        $auth_user = Auth::guard('user-api')->user();

        if( $auth_user != null) {
            $this->user = User::where('id',$auth_user->id)->first();
        } else {
            $this->user = null;
        }

        if($this->user == null) {
            if($lang == 'en') {
                return $this->returnError('403','Unauthenticated user');
            } else {
                return $this->returnError('403','يجب تسجيل الدخول اولا');
            }
        }

        if($id == null) {

            $notifications = Notifications::where('send_to_type','user')->where('send_to_id',$this->user->id)->orderBy('created_at','desc')->get();

            if($notifications != null && $notifications->count() > 0) {
                $notifications = Notications_Data_R::collection($notifications);
            }

            return $this->returnData('data',$notifications,'');

        } else {

            $notification = Notifications::where('send_to_type','user')->where('send_to_id',$this->user->id)->where('id',$id)->first();

            if($notification != null ) {

                $notification = new Notications_Data_R($notification);

                $notification->update([ 'seen' => 1 ]);

                return $this->returnData('data',$notification,'');

            } else  {

                if($lang == 'en') {
                    return $this->returnError('404','sorry this notification not found');
                } else {
                    return $this->returnError('404','عفوا هذا الأشعار غير موجود');
                }
            }

        }


    }





}

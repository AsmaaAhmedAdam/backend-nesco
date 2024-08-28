<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\APiResource\Cart_R;
use App\Http\Resources\APiResource\Favorite_R;
use App\Http\Resources\APiResource\Invoice_Details_Data;
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
use App\Models\Product_Selling;
use App\Models\User;
use App\Traits\GeneralTrait;
use AymanElmalah\MyFatoorah\Facades\MyFatoorah;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use Essam\TapPayment\Payment;
use Exception;



class CartController extends Controller
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



    // coupon
    public function coupon(Request $request) {

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
            'code' => 'required|max:100',
        ];

        $custom_messages = [
            'code.required' => ' الكود مطلوب',
            'code.max' => 'الكود يجب ان يحتوي علي الاكثر 100 حرف',
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


        $coupon = $request->code;

        $sub_total = Cart::where('user_id',$this->user->id)->sum('total');


        // check coupon
        $check_coupon = Coupon::where("title",$coupon)->where('status',1)->first();

        if($check_coupon != null) {

            $now = Carbon::parse(Carbon::now())->format('Y-m-d');

            if($check_coupon->date == null || ($check_coupon->date != null && $now <= $check_coupon->date) ) {

                if($lang == 'en') {
                    $message = ' The discount has been activated successfully ';
                } else {
                    $message = ' تم تفعيل الخصم بنجاح ';
                }

                if($check_coupon->value_type == 'percentage') {
                    return $this->returnSuccessMessage($message);
                }


                if($check_coupon->value_type == 'value') {

                    if($check_coupon->value > $sub_total && $sub_total != 0) {

                        if($lang == 'en') {
                            $message = 'this coupon is not valid';
                        } else {
                            $message = ' هذه القسيمة غير صالحة ';
                        }

                        return $this->returnSuccessMessage($message);

                    } else {

                        return $this->returnSuccessMessage($message);
                    }

                }


            } else {

                if($lang == 'en') {
                    $message = 'this coupon is not valid';
                } else {
                    $message = ' هذه القسيمة غير صالحة ';
                }

                return $this->returnSuccessMessage($message);
            }


        } else {


            if($lang == 'en') {
                $message = 'this coupon is not valid';
            } else {
                $message = ' هذه القسيمة غير صالحة ';
            }

            return $this->returnSuccessMessage($message);

        }

    }



    // addToCart
    public function addToCart(Request $request)
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
            'quantity' => 'required|min:1',
        ];

        $custom_messages = [
            'product_id.required' => ' المنتج مطلوب',
            'product_id.numeric' => 'المننتج يجب ان يحتوي علي ارقام',
            'product_id.exists' => 'عفوا هذا المننتج غير موجود ',
            'quantity.required' => ' العدد مطلوبة',
            'quantity.min' => ' العدد يجب ان يحتوي علي الاقل علي قيمة 1',
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
        $quantity = $request->quantity;

        $product = Product::where('id',$product_id)->first();

        if (!$product) {
            if($lang == 'en') {
                return $this->returnError('404','sorry this product not found');
            } else {
                return $this->returnError('404','عفوا هذا المنتج غير موجود');
            }
        }

        $check_cart = Cart::where('user_id',$this->user->id)->where('product_id',$product_id)->first();

        $favorite_row = Favorite::where('user_id',$this->user->id)->where('product_id',$product_id)->first();

        // if cart is empty then this the first product
        if($check_cart == null) {

            Cart::create([
                'user_id' => $this->user->id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $product->price * $quantity,
                'favorite' => $favorite_row != null ? true : false
            ]);

            if($lang == 'en') {
                $msg = 'product added successfully to cart';
            } else {
                $msg = 'تمت إضافة المنتج بنجاح الي السلة';
            }

            return $this->returnSuccessMessage($msg);

        } else {

            $check_cart->update([
                'favorite' => $favorite_row != null ? true : false,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $product->price *  $quantity
            ]);

            if($lang == 'en') {
                $msg = ' This product already exists, quantity is updated';
            } else {
                $msg = ' هذا المنتج موجود من قبل , تم تحديث العدد';
            }

            return $this->returnSuccessMessage($msg);

        }


    }



    // UpdateCart
    public function UpdateCart(Request $request)
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
            'row_id' => 'required|numeric|exists:cart,id',
            'key_operation_value' =>  ['required', Rule::in(['+','-']) ]

        ];

        $custom_messages = [
            'row_id.required' => ' رقم العنصر مطلوب',
            'row_id.numeric' => 'رقم العنصر يجب ان يحتوي علي ارقام',
            'row_id.exists' => 'عفوا رقم العنصر غير موجود ',
            'key_operation_value.required' => ' نوع العمية مطلوب',
            'key_operation_value.in' => ' نوع العمية غير صحيح',
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


        $key_operation_value = $request->key_operation_value;

        $cart = Cart::where('id',$request->row_id)->where('user_id',$this->user->id)->first();

        $product = Product::where('id',$cart->product_id)->first();

        if (!$cart || !$product) {
            if($lang == 'en') {
                return $this->returnError('404','sorry this item not found');
            } else {
                return $this->returnError('404','عفوا هذا العنصر غير موجود');
            }
        }


        $favorite_row = Favorite::where('user_id',$this->user->id)->where('product_id',$cart->product_id)->first();


        if($key_operation_value == '+') {

            $cart->update([
                'favorite' => $favorite_row != null ? true : false,
                'quantity' => $cart->quantity + 1,
                'price' => $product->price,
                'total' => $product->price *  ($cart->quantity + 1)
            ]);

            if($lang == 'en') {
                $msg = ' quantity is updated successfully';
            } else {
                $msg = ' تم تحديث العدد بنجاح';
            }

            return $this->returnSuccessMessage($msg);


        } else {

            if($cart->quantity > 1) {

                $cart->update([
                    'favorite' => $favorite_row != null ? true : false,
                    'quantity' => $cart->quantity - 1,
                    'price' => $product->price,
                    'total' => $product->price *  ($cart->quantity - 1)
                ]);

                if($lang == 'en') {
                    $msg = ' quantity is updated successfully';
                } else {
                    $msg = ' تم تحديث العدد بنجاح';
                }

                return $this->returnSuccessMessage($msg);


            } else {

                $cart->delete();

                if($lang == 'en') {
                    $msg = ' this product is deleted successfully from cart';
                } else {
                    $msg = ' تم حذف المنتج من السلة بنجاح';
                }

                return $this->returnSuccessMessage($msg);

            }


        }

    }



    // removeFromCart
    public function removeFromCart($row_id)
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


        $raw = Cart::where('id',$row_id)->where('user_id',$this->user->id)->first();

        if($raw != null) {

            $raw->delete();

            if($lang == 'en') {
                return $this->returnSuccessMessage('this product is deleted from cart successfully');
            } else {
                return $this->returnSuccessMessage('تم حذف المنتج من السلة بنجاح');
            }

        } else {

            if($lang == 'en') {
                return $this->returnError('404','sorry this product not found');
            } else {
                return $this->returnError('404','عفوا هذا المنتج غير موجود');
            }
        }

    }



    // removeCart
    public function removeCart()
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

        Cart::where('user_id',$this->user->id)->delete();

        if($lang == 'en') {
            return $this->returnSuccessMessage('cart is deleted successfully');
        } else {
            return $this->returnSuccessMessage('تم حذف  السلة بنجاح');
        }

    }



    // addToFavorite
    public function addToFavorite($product_id)
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


        $product = Product::where('id',$product_id)->first();

        if($product != null) {

            $raw = Favorite::where('product_id',$product_id)->where('user_id',$this->user->id)->first();

            $check_cart = Cart::where('user_id',$this->user->id)->where('product_id',$product_id)->first();

            if($raw == null) {

                Favorite::create([
                    'user_id' => $this->user->id,
                    'product_id' => $product_id,
                ]);

                if($check_cart != null) {
                    $check_cart->update([ 'favorite' => 'yes' ]);
                }

                if($lang == 'en') {
                    return $this->returnSuccessMessage('this product is add to favorite successfully');
                } else {
                    return $this->returnSuccessMessage('تم أضافه المنتج الي المفضلة بنجاح');
                }

            } else {

                $raw->delete();

                if($check_cart != null) {
                    $check_cart->update([ 'favorite' => 'no' ]);
                }

                if($lang == 'en') {
                    return $this->returnSuccessMessage('this product is deleted from favorite successfully');
                } else {
                    return $this->returnSuccessMessage('تم حذف المنتج من المفضلة بنجاح');
                }

            }

        } else {

            if($lang == 'en') {
                return $this->returnError('404','sorry this product not found');
            } else {
                return $this->returnError('404','عفوا هذا المنتج غير موجود');
            }
        }

    }



    // cart
    public function cart()
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

        $cart = Cart::where('user_id',$this->user->id)->get(['id','favorite','user_id','quantity','total','product_id']);

        if($cart != null && $cart->count() > 0) {

            $cart = Cart_R::collection($cart);

            return $this->returnData('data',$cart,'');

        } else {
            if($lang == 'en') {
                return $this->returnData('data',null,'Sorry, the card is empty');
            } else {
                return $this->returnData('data',null,'عفوا البطاقة فارغة');
            }
        }


    }



    // favorite
    public function favorite()
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

        $favorite = Favorite::where('user_id',$this->user->id)->get(['id','product_id']);

        if($favorite != null && $favorite->count() > 0) {

            $favorite = Favorite_R::collection($favorite);

            return $this->returnData('data',$favorite,'');

        } else {
            if($lang == 'en') {
                return $this->returnData('data',null,'sorry your favorites are empty');
            } else {
                return $this->returnData('data',null,'عفوا المفضلة فارغة');
            }
        }

    }




    // calc operation
    public function checkout(Request $request)
    {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        try {

            if($this->user == null) {
                if($lang == 'en') {
                    return $this->returnError('403','Unauthenticated user');
                } else {
                    return $this->returnError('403','يجب تسجيل الدخول اولا');
                }
            }


            $validated_arr = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'mobile'  => 'required|numeric',
                'city_id' => 'required|numeric|exists:cities,id',
                'address' => 'required|max:255',
            ];


            $custom_messages = [

                'name.required' => ' الاسم مطلوب',
                'name.max' => ' الاسم لابد ان يحتوي علي الاكثر 255 حرف',

                'address.required' => ' العنوان مطلوب',
                'address.max' => ' العنوان لابد ان يحتوي علي الاكثر 255 حرف',

                'email.required' => 'البريد الالكتروني مطلوب',
                'email.email' => 'يجب ان يحتوي البريد الالكتروني علي بريد الكتروني صحيح',
                'email.max' => ' البريد الالكتروني لابد ان يحتوي علي الاكثر 255 حرف',

                'mobile.required' => 'رقم الموبيل مطلوب',
                'mobile.numeric' => 'رقم الموبيل لابد ان يحتوي علي أرقام فقط',

                'city_id.required' => ' المدينة مطلوبة',
                'city_id.numeric' => 'المدينة يجب ان تحتوي علي ارقام',
                'city_id.exists' => 'عفوا  هذه المدينة غير موجودة ',

                'street.required' => ' الشارع مطلوب',
                'street.max' => ' الشارع لابد ان يحتوي علي الاكثر 255 حرف',

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


            $cart = Cart::where('user_id',$this->user->id)->get();

            if($cart != null && $cart->count() > 0) {

                // intilize data
                $coupon = $request->coupon;
                $city_id = $request->city_id;

                $shipping_value = 0;
                $calc_total = 0;

                $coupon_id = 0;
                $coupon_value = 0;

                $city = Cities::where('id',$city_id)->first();

                if($city == null || ($city != null && $city->status == 0) ) {

                    if($lang == 'en') {
                        return $this->returnError('404','sorry this city not found');
                    } else {
                        return $this->returnError('404','عفوا هذه المدينة غير موجودة');
                    }

                    $shipping_value = 0;

                } else {
                    $shipping_value = $city->shipping_value;
                }

                $sub_total = Cart::where('user_id',$this->user->id)->sum('total');

                // check coupon
                if($coupon != null) {

                    $check_coupon = Coupon::where("title",$coupon)->where('status',1)->first();

                    if($check_coupon != null) {

                        $coupon_id = $check_coupon->id;

                        $now = Carbon::parse(Carbon::now())->format('Y-m-d');

                        if($check_coupon->date == null || ($check_coupon->date != null && $now <= $check_coupon->date) ) {


                            if($check_coupon->value_type == 'percentage') {
                                $coupon_value = round($sub_total * ($check_coupon->value/100));
                                $calc_total = $sub_total +  $shipping_value - $coupon_value;
                            }


                            if($check_coupon->value_type == 'value') {

                                if($check_coupon->value > $sub_total) {
                                    $coupon_value = 0;
                                    $calc_total = $sub_total +  $shipping_value - $coupon_value;

                                } else {
                                    $coupon_value = $check_coupon->value;
                                    $calc_total = $sub_total +  $shipping_value - $check_coupon->value;
                                }

                            }


                        } else {
                            $coupon_value = 0;
                            $calc_total = $sub_total +  $shipping_value - $coupon_value;
                        }

                    } else {
                        $coupon_id = 0;
                        $coupon_value = 0;
                        $calc_total = $sub_total +  $shipping_value - $coupon_value;
                    }
                } else {
                    $calc_total = $sub_total +  $shipping_value - $coupon_value;
                }


                $user = $this->user;
                $user_id = $user->id;

                // start delete all old invoices
                $all_user_invoices = Invoice::where('user_id',$user_id)->where('status','hold')->get(['id','serial_number']);

                if( $all_user_invoices != null &&  $all_user_invoices->count() > 0) {

                    foreach ($all_user_invoices as $old_invoice) {

                        Invoice_User::where('user_id',$user_id)->where('serial_number',$old_invoice->serial_number)->delete();
                        Invoice_Details::where('user_id',$user_id)->where('serial_number',$old_invoice->serial_number)->delete();

                        $old_invoice->delete();

                    }
                }
                // end delete all old invoices


                // start invoice
                $Invoice_User = Invoice_User::create([
                    'invoice_row_id' => null,
                    'serial_number' => null,
                    'user_id' => $user_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'city_id' => $request->city_id,
                ]);

                $sn = Invoice::max('serial_number') + 1;

                $invoice_arr = [
                    'user_id' => $user_id,
                    'serial_number' => $sn,
                    'operation_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'count_items' => $cart->count(),
                    'sub_total' => $sub_total,
                    'shipping_value' => $shipping_value,
                    'coupon_id' => $coupon_id,
                    'coupon_value' => $coupon_value,
                    'total' => $calc_total,
                    'invoice_user_id' => $Invoice_User->id,
                    'status' => 'hold',
                    'status2' => 'hold',
                ];

                // create invoice
                $invoice = Invoice::create($invoice_arr);

                $Invoice_User->update([
                    'invoice_row_id' => $invoice->id,
                    'serial_number' => $invoice->serial_number
                ]);

                foreach($cart as $details) {

                    $product = Product::where('id',$details->product_id)->first();

                    if($product != null && $details->price != null && $details->price > 0) {

                        $details_arr = [
                            'user_id' => $user_id,
                            'invoice_row_id' => $invoice->id,
                            'serial_number' => $invoice->serial_number,
                            'invoice_user_id' => $Invoice_User->id,
                            'operation_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                            'product_id'=> $details->product_id,
                            'quantity' => $details->quantity,
                            'price' => $details->price,
                            'total' => $details->total,
                        ];

                        // create invoice details
                        Invoice_Details::create($details_arr);

                        $check_product = Product_Selling::where('product_id',$details->product_id)->first();

                        if($check_product != null) {
                            $check_product->update([
                                'count' => $check_product->count +  $details->quantity
                            ]);
                        } else {
                            Product_Selling::create([
                                'product_id' => $details->product_id,
                                'count' => $details->quantity
                            ]);
                        }

                        //$details->delete();

                    }

                }

                /* ********************************************************************* */

                $details = Invoice_Details::where('serial_number',$invoice->serial_number)->get(['id','quantity','price','total','product_id']);

                $all_data['id'] = $invoice->id;
                $all_data['sub_total'] = $invoice->sub_total;
                $all_data['delivery_value'] = $invoice->shipping_value;
                $all_data['discount_value'] = $invoice->coupon_value;
                $all_data['total'] = $invoice->total;

                $all_data['details'] = Invoice_Details_Data::collection($details);


                return $this->returnData('data',$all_data,'');


            } else {

                if($lang == 'en') {
                    return $this->returnError('E100','the cart is empty');
                } else {
                    return $this->returnError('E100','عفوا البطاقة فارغة');
                }
            }


        } catch(\Exception $e) {
            //dd($e->getMessage(),$e->getLine());
            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عفوا لقد حدث خطا ما برجاء المحاولة');
            }
        }


    }



    // payment
    public function payment(Request $request) {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        try {

            if($this->user == null) {
                if($lang == 'en') {
                    return $this->returnError('403','Unauthenticated user');
                } else {
                    return $this->returnError('403','يجب تسجيل الدخول اولا');
                }
            }

            $user = $this->user;

            $validated_arr = [
                'id' => 'required|numeric|exists:invoice,id',
            ];


            $custom_messages = [
                'id.required' => ' رقم الفاتورة مطلوبة',
                'id.numeric' => 'رقم الفاتورة يجب ان تحتوي علي ارقام',
                'id.exists' => 'عفوا  هذه رقم الفاتورة غير موجود ',
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

            /* ******************* start myfatorah ************************** */

                $invoice = Invoice::where('id',$request->id)->where('user_id',$user->id)->where('status','hold')->orderBy('created_at','desc')->first();

                if($invoice != null) {

                    $user_inv = Invoice_User::where('user_id',$user->id)->where('invoice_row_id',$invoice->id)->first();

                    if($user_inv != null) {

                        $pay= paypage::sendPaymentCode('all')

                        ->sendTransaction('sale')
                        //->sendTransaction('Auth')

                        ->sendCart($invoice->id,$invoice->total,'new payment')

                        ->sendCustomerDetails($user_inv->name, $user_inv->email, $user_inv->mobile, $user_inv->address, $user_inv->address, 'AZ', 'AE', '','')

                        //->sendCustomerDetails($user->name, $user->email, $user->mobile, '', '', '', 'AED', '','')
                        //->sendCustomerDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '01008478014', 'Abu Dhabi', 'Abu Dhabi', 'United Arab Emirates', 'AED', '1234','100.279.20.10')
                        //->sendShippingDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '01008478014', 'Abu Dhabi', 'Abu Dhabi', 'United Arab Emirates', 'AED', '1234','100.279.20.10')
                        //->sendShippingDetails('same as billing')

                        ->sendHideShipping(true)

                        ->sendURLs(asset("api/return_url") , asset("api/paytabs_callback"))

                        ->sendLanguage('en')

                        ->sendFramed(true)
                        ->create_pay_page();


                        if($pay != null) {

                            if($lang == 'en') {
                                // must save // InvoiceId
                                return $this->returnResponseWithLink(true,'200','you will redirect to new page to pay '.$invoice->total . ' AED',$pay);
                            } else {
                                return $this->returnResponseWithLink(true,'200',' ستتم إعادة التوجيه إلى صفحة جديدة لدفع '.$invoice->total . ' درهم أماراتي',$pay);
                            }

                        } else {

                            if($lang == 'en') {
                                return $this->returnError('E200','sorry try again #000');
                            } else {
                                return $this->returnError('E200','عفوا لقد حدث خطا ما برجاء المحاولة #000');
                            }

                            //return 'error';
                            //dd(gettype($pay),$pay);
                        }

                    } else {

                        if($lang == 'en') {
                            return $this->returnError('E100','sorry this invoice not found');
                        } else {
                            return $this->returnError('E100','عفوا هذا الفاتوره غير موجوده.');
                        }

                    }




                } else {

                    if($lang == 'en') {
                        return $this->returnError('E100','sorry this invoice not found');
                    } else {
                        return $this->returnError('E100','عفوا هذا الفاتوره غير موجوده.');
                    }

                }

            /* ******************* end myfatorch **************************** */


        } catch(\Exception $e) {
            //dd($e->getMessage());
            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عفوا لقد حدث خطا ما برجاء المحاولة');
            }
        }

    }



    // orders
    public function orders($id = null)
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

        if($id == null) {

            $orders = Invoice::where('user_id',$this->user->id)->where('status','done')->select(['id','operation_date','serial_number','count_items','coupon_value','shipping_value','sub_total','total','status2','created_at'])->paginate(10);

            if($orders != null && $orders->count() > 0) {
                return $this->returnData('data',$orders,'');
            } else {
                if($lang == 'en') {
                    return $this->returnData('data',null,'Sorry, there is no orders');
                } else {
                    return $this->returnData('data',null,'عفوا لا يوجد طلبات');
                }
            }

        } else {

            $order = Invoice::where('user_id',$this->user->id)->where('id',$id)->first();

            if($order != null && $order->status == 'done') {

                $orders = Invoice_Details::where('serial_number',$order->serial_number)->get();

                if($orders != null && $orders->count() > 0) {

                    $orders = Order_Data::collection($orders);
                    return $this->returnData('data',$orders,'');

                } else {

                    if($lang == 'en') {
                        return $this->returnError('E100','sorry this order not have any items');
                    } else {
                        return $this->returnError('E100','عفوا هذا الطلب لا يحتوي علي اي تفاصيل');
                    }
                }

            } elseif($order != null && $order->status != 'done') {

                if($lang == 'en') {
                    return $this->returnError('E100','sorry this order is hold');
                } else {
                    return $this->returnError('E100','عفوا هذا الطلب معلق');
                }

            } else {

                if($lang == 'en') {
                    return $this->returnError('404','sorry this order is not found');
                } else {
                    return $this->returnError('404','عفوا هذا الطلب غير موجود');
                }
            }

        }



    }




    // coupon
    /*
    public function coupon(Request $request) {

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
            'code' => 'required|max:100',
            'city_id' => 'required|max:100',
        ];

        $custom_messages = [
            'code.required' => ' الكود مطلوب',
            'code.max' => 'الكود يجب ان يحتوي علي الاكثر 100 حرف',
            'city_id.required' => ' المدينة مطلوبة',
            'city_id.max' => 'المدينة يجب ان تحتوي علي الاكثر 100 حرف',
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


        $coupon = $request->code;
        $city_id = $request->city_id;
        $calc_total = 0;
        $shipping_value = 0;

        $city = Cities::where('id',$city_id)->first();

        $sub_total = Cart::where('user_id',$this->user->id)->sum('total');

        if($city != null) {
            $shipping_value = $city->shipping_value;
        } else {
            $shipping_value = 0;
        }



        // check coupon
        $check_coupon = Coupon::where("title",$coupon)->where('status',1)->first();

        if($check_coupon != null) {

            $now = Carbon::parse(Carbon::now())->format('Y-m-d');

            if($check_coupon->date == null || ($check_coupon->date != null && $now <= $check_coupon->date) ) {

                if($lang == 'en') {
                    $message = ' The discount has been activated successfully ';
                } else {
                    $message = ' تم تفعيل الخصم بنجاح ';
                }


                if($check_coupon->value_type == 'percentage') {

                    $coupon_value = round($sub_total * ($check_coupon->value/100));

                    $calc_total = $sub_total +  $shipping_value - $coupon_value;

                    $data['shipping_value'] = $shipping_value;
                    $data['sub_total'] = $sub_total;
                    $data['discount'] = $coupon_value;
                    $data['total'] = $calc_total;

                    return $this->returnData('data',$data,$message);

                }


                if($check_coupon->value_type == 'value') {

                    if($check_coupon->value > $sub_total) {

                        if($lang == 'en') {
                            $message = 'this coupon is not valid';
                        } else {
                            $message = ' هذه القسيمة غير صالحة ';
                        }

                        $calc_total = $sub_total +  $shipping_value - 0;

                        $data['sub_total'] = $sub_total;
                        $data['shipping_value'] = $shipping_value;
                        $data['discount'] = 0;
                        $data['total'] = $calc_total;

                        return $this->returnData('data',$data,$message);


                    } else {

                        $calc_total = $sub_total +  $shipping_value - $check_coupon->value;

                        $data['sub_total'] = $sub_total;
                        $data['shipping_value'] = $shipping_value;
                        $data['discount'] = $check_coupon->value;
                        $data['total'] = $calc_total;

                        return $this->returnData('data',$data,$message);

                    }

                }


            } else {

                if($lang == 'en') {
                    $message = 'this coupon is not valid';
                } else {
                    $message = ' هذه القسيمة غير صالحة ';
                }

                $calc_total = $sub_total +  $shipping_value - 0;

                $data['sub_total'] = $sub_total;
                $data['shipping_value'] = $shipping_value;
                $data['discount'] = 0;
                $data['total'] = $calc_total;

                return $this->returnData('data',$data,$message);
            }


        } else {


            if($lang == 'en') {
                $message = 'this coupon is not valid';
            } else {
                $message = ' هذه القسيمة غير صالحة ';
            }

            $calc_total = $sub_total +  $shipping_value - 0;

            $data['sub_total'] = $sub_total;
            $data['shipping_value'] = $shipping_value;
            $data['discount'] = 0;
            $data['total'] = $calc_total;

            return $this->returnData('data',$data,$message);

        }

    }
    */


    // myfatoorh
    /*

    public function myfatoorh(Request $request) {

        $lang = app()->getLocale();
       
        if(empty($lang)) {
            return $this->returnError('E300','language is required');
        }

        try {

            if($this->user == null) {
                if($lang == 'en') {
                    return $this->returnError('403','Unauthenticated user');
                } else {
                    return $this->returnError('403','يجب تسجيل الدخول اولا');
                }
            }

            $user = $this->user;

            $validated_arr = [
                'id' => 'required|numeric|exists:invoice,id',
            ];


            $custom_messages = [
                'id.required' => ' رقم الفاتورة مطلوبة',
                'id.numeric' => 'رقم الفاتورة يجب ان تحتوي علي ارقام',
                'id.exists' => 'عفوا  هذه رقم الفاتورة غير موجود ',
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

            $invoice = Invoice::where('id',$request->id)->where('user_id',$user->id)->where('status','hold')->first();

            if($invoice != null) {

                $path = 'https://www.ebtasme.com/coffee-tea/';

                $fatoorh_data = [
                    'CustomerName' => $user->name,
                    'NotificationOption' => 'all',
                    'CustomerEmail' => $user->email,
                    'InvoiceValue' => $invoice->total,
                    'DisplayCurrencyIso' => 'AED',
                    'Language' => 'en',
                    'CallBackUrl' => $path.'api/payment/callback',
                    'ErrorUrl' => $path.'api/payment/error',
                    'CustomerMobile' => ltrim($user->mobile,'+971'),
                    'MobileCountryCode' => '+971',
                ];

                // If you want to set the credentials and the mode manually.
                $myfatoorah = MyFatoorah::setAccessToken(env('MYFATOORAH_TOKEN'))->setMode('test')->createInvoice($fatoorh_data);

                if(! $myfatoorah) {

                    if($lang == 'en') {
                        return $this->returnError('E100','Something went wrong, please try again');
                    } else {
                        return $this->returnError('E100','حدث خطأ ما. أعد المحاولة من فضلك');
                    }

                } else  {

                    if($myfatoorah['IsSuccess'] == true) {

                        if(array_key_exists('Data', $myfatoorah) && array_key_exists('InvoiceURL', $myfatoorah['Data'])) {

                            $url = $myfatoorah['Data']['InvoiceURL'];

                            if(array_key_exists('InvoiceId', $myfatoorah['Data'])) {

                                $InvoiceId = $myfatoorah['Data']['InvoiceId'];

                                $invoice->update([ 'invoice_id' => $InvoiceId ]);

                                $url = $myfatoorah['Data']['InvoiceURL'];

                                //dd($myfatoorah);

                                if($lang == 'en') {
                                    // must save // InvoiceId
                                    return $this->returnResponseWithLink(true,'200','you will redirect to new page to pay '.$invoice->total . ' AED',$url);
                                } else {
                                    return $this->returnResponseWithLink(true,'200',' ستتم إعادة التوجيه إلى صفحة جديدة لدفع '.$invoice->total . ' درهم أماراتي',$url);
                                }



                            }
                        }
                    }

                    if($lang == 'en') {
                        return $this->returnError('E100','payemnt is failed . please try again');
                    } else {
                        return $this->returnError('E100','فشل الدفع. حاول مرة اخرى');
                    }
                }

            } else {

                if($lang == 'en') {
                    return $this->returnError('E100','sorry this invoice not found');
                } else {
                    return $this->returnError('E100','عفوا هذا الفاتوره غير موجوده.');
                }

            }


        } catch(\Exception $e) {
            //dd($e->getMessage());
            if($lang == 'en') {
                return $this->returnError('E200','sorry try again');
            } else {
                return $this->returnError('E200','عفوا لقد حدث خطا ما برجاء المحاولة');
            }
        }

    }

    */

}

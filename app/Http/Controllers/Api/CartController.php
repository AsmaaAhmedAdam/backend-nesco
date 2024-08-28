<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\APiResource\Cart_R;
use App\Http\Resources\APiResource\Favorite_R;
use App\Http\Resources\APiResource\Invoice_Details_Data;
use App\Http\Resources\ApiResource\InvoiceResource;
use App\Http\Resources\APiResource\Order_Data;
use App\Models\Invoice;
use App\Models\Invoice_Details;
use App\Models\Invoice_User;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Cities;
use App\Models\Favorite;
use App\Models\Product_Selling;
use App\Models\Setting;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use ReflectionClass;

class CartController extends Controller
{
    use GeneralTrait;
    public $user;
    // public $lang;
    public function __construct()
    {
        // $user = JWTAuth::parseToken()->authenticate();
        $auth_user = Auth::guard('user-api')->user();
        $this->user = !is_null($auth_user ) ? User::where('id',$auth_user->id)->first() : null;
    }

    // coupon
    public function coupon(Request $request) {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        }
        $validated_arr = [
            'code' => 'required|max:100',
        ];
        $custom_messages = [
            'code.required' => ' الكود مطلوب',
            'code.max' => 'الكود يجب ان يحتوي علي الاكثر 100 حرف',
        ];
        $validator = App::isLocale('en') ? Validator::make($request->all(), $validated_arr) : Validator::make($request->all(), $validated_arr, $custom_messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }
        $coupon = $request->code;
        $cart_total = Cart::where('user_id',$this->user->id)->select('id', 'total', 'discount')->first();
        if(!$cart_total) {
            return $this->returnError('404',trans('api.cart_not_found'));
        }
        // check coupon
        $check_coupon = Coupon::where("title",$coupon)->where('status',1)->first();
        if($check_coupon) {
            $now = Carbon::parse(Carbon::now())->format('Y-m-d');
            if($check_coupon->date == null || ($check_coupon->date != null && $now <= $check_coupon->date) ) {
                $message = trans('api.discount_activated_successfully');
                if($check_coupon->value_type == 'percentage') {
                    $cart_total->discount = $check_coupon->value;
                    $cart_total->coupon_id = $check_coupon->id;
                    $cart_total->save();
                    return $this->returnSuccessMessage($message);
                }
                if($check_coupon->value_type == 'value' && $check_coupon->value > $cart_total->total && $cart_total->total != 0) {
                    return $this->returnError('E100',trans('api.coupon_not_valid'));
                } 
                $cart_total->discount = $check_coupon->value;
                $cart_total->save();
                return $this->returnSuccessMessage($message);
            } 
            return $this->returnError('E100',trans('api.coupon_not_valid'));
        } 
        return $this->returnError('404',trans('api.coupon_not_found'));
    }



    /**
     * add product to cart.
     *
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function addToCart(Request $request)
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        }
        $validated_arr = [
            'product_id' => 'required|numeric|exists:product,id',
            'quantity'   => 'required|min:1|max:50',
        ];
        $custom_messages = [
            'product_id.required' => ' المنتج مطلوب',
            'product_id.numeric'  => 'المننتج يجب ان يحتوي علي ارقام',
            'product_id.exists'   => 'عفوا هذا المننتج غير موجود ',
            'quantity.required'   => ' العدد مطلوبة',
            'quantity.min'        => ' العدد يجب ان يحتوي علي الاقل علي قيمة 1',
            'quantity.max'        => ' العدد يجب ان يحتوي علي الاكثر علي قيمة 50',
        ];
        $validator = App::isLocale('en') ? Validator::make($request->all(), $validated_arr) : Validator::make($request->all(), $validated_arr, $custom_messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $product = Product::where('id',$product_id)->first();
        if (!$product) {
            return $this->returnError('404',trans('api.product_not_found') );
        }
        $check_cart = Cart::where('user_id',$this->user->id)->first();
        // if cart is empty then this the first product
        if(!$check_cart) {
            $cart = Cart::create([
                'user_id' => $this->user->id,
                'total'   => $product->price * $quantity,
            ]);
            $cart->cartProducts()->create([
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'price'      => $product->price,
                'total'      => $product->price * $quantity,
            ]);
            return $this->returnData('cart', new Cart_R($cart), trans('api.product_added_to_cart'));
        } 
        $check_cart->cartProducts()->updateOrCreate([
            'product_id' => $product_id
        ],[
            'quantity' => $quantity,
            'price'    => $product->price,
            'total'    => $product->price * $quantity,
        ]);
        $totalCartSum = $check_cart->cartProducts()->sum('total');
        $check_cart->update([
            'total' => $totalCartSum
        ]);
        return $this->returnData('cart', new Cart_R($check_cart), trans('api.quantity_is_updated'));
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

    /**
     * remove product from cart.
     *
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function removeFromCart($row_id)
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        }
        $product = Product::where('id',$row_id)->first();
        if(!$product) {
            return $this->returnError('404', trans('api.product_not_found'));
        }
        $check_cart = Cart::where('user_id',$this->user->id)->first();
        // if cart is empty then this the first product
        if(!$check_cart) {
            return $this->returnError('404',trans('api.cart_not_found'));
        }
        $check_cart->cartProducts()->where('product_id', $row_id)->delete();
        $totalCartSum = $check_cart->cartProducts()->sum('total');
        if($totalCartSum == 0) {
            $check_cart->delete();    
        } else {
            $check_cart->update([
                'total' => $totalCartSum
            ]);
        }
        return $this->returnSuccessMessage(trans('api.product_deleted_from_cart')); 
    }

    /**
     * remove cart.
     *
     * @return json
     */
    public function removeCart()
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        }
        $cart = Cart::where('user_id',$this->user->id)->first();
        if($cart) {
            $cart->delete();
            return $this->returnSuccessMessage(trans('api.cart_deleted_successfully'));
        }
        return $this->returnError('404',trans('api.cart_not_found'));
    }


    /**
     * add&remove favourite.
     *
     * @param  int $product_id
     * @return json
     */
    public function addToFavorite($product_id)
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        } 
        $product = Product::where('id',$product_id)->first();
        if(!is_null($product)) {
            $raw = Favorite::where('product_id',$product_id)->where('user_id',$this->user->id)->first();
            if(is_null($raw)) {
                Favorite::create([
                    'user_id' => $this->user->id,
                    'product_id' => $product_id,
                ]);
                return $this->returnSuccessMessage(trans('api.product_added_to_favorite'));
            } 
            $raw->delete();
            return $this->returnSuccessMessage(trans('api.product_deleted_from_favorite'));
        }
        return $this->returnError('404',trans('api.product_not_found') );
    }



    /**
     * return all authenticated user cart products.
     *
     * 
     * @return json
     */
    public function cart()
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        }
        $cart = Cart::where('user_id',$this->user->id)->select('id', 'user_id', 'shipping_value', 'discount', 'coupon_id', 'city_id', 'total', 'address')->with([
            'cartProducts:id,cart_id,product_id,quantity,price,total',
            'cartProducts.products:id,'.app()->getLocale().'_title,pic',
            'cartProducts.products.favourite:id,product_id',
            'city:id,'.app()->getLocale().'_name',
            ])->first();

        if(!is_null($cart)) {
            return $this->returnData('data',new Cart_R($cart),'');
        } 
        return $this->returnData('data',null,trans('api.cart_empty')); 
    }

    /**
     * user favourites.
     *
     * @return json
     */
    public function favorite()
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        } 
        $favorite = Favorite::where('user_id',$this->user->id)->get(['id','product_id']);
        if($favorite != null && $favorite->count() > 0) {
            $favorite = Favorite_R::collection($favorite);
            return $this->returnData('data',$favorite,'');
        } 
        return $this->returnData('data',null,trans('api.favourite_empty'));
    }

    
    /**
    * user add address for checkout.
    * @param Illuminate\Http\Request $request
    * @return json
     */
    public function addAdress(Request $request)
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        } 
        // dd($request);
        $validated_arr = [
            'city_id' => 'required|numeric|exists:cities,id',
            'address.building' => 'required|integer',
            'address.floor'    => 'required|integer',
            'address.flat'     => 'required|integer',
            'address.street'      => 'required|string',
            'address.state'       => 'required|string',
            'address.postal'      => 'required|integer',
            'address.phone'       => 'required|numeric',
        ];
        $custom_messages = [
            'city_id.required' => ' المدينة مطلوبة',
            'city_id.numeric'  => 'المدينة يجب ان تحتوي علي ارقام',
            'city_id.exists'   => 'عفوا  هذه المدينة غير موجودة ',
            'address.building.required' => 'رقم المبنى مطلوب',
            'address.building.integer'  => 'رقم المبنى يجب ان يكون رقم فقط',
            'address.floor.required'    => 'رقم الدور مطلوب',
            'address.floor.integer'     => 'رقم الدور يجب ان يكون رقم فقط',
            'address.flat.required'     => 'رقم الشقة مطلوب',
            'address.flat.integer'      => 'رقم الشقة يجب ان يكون رقم فقط',
            'address.state.required'       => 'المقاطعة مطلوبة',
            'address.state.string'         => 'المقاطعة يحب ان تتكون من حروف',
            'address.phone.required'       => 'رقم الموبيل مطلوب',
            'address.phone.numeric'        => 'رقم الموبيل لابد ان يحتوي علي أرقام فقط',
            'address.street.required'      => ' الشارع مطلوب',
            'address.street.string'        => 'الشارع لابد ان يحتوي علي حروف,ارقام',
        ];
        $validator = App::isLocale('en') ? Validator::make($request->all(), $validated_arr) : Validator::make($request->all(), $validated_arr, $custom_messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }
        $city = Cities::where('status', 1)->where('id', $request->city_id)->select('shipping_value')->first();
        if(!$city) {
            return $this->returnError('404', trans('api.service_not_available'));
        }
        $cart = Cart::where('user_id', $this->user->id)->first();
        if(!$cart) {
            return $this->returnError('E100',trans('api.cart_empty'));
        }
        $cart->city_id = $request->city_id;
        $cart->shipping_value = $city->shipping_value;
        $cart->address = json_encode($request->address ?? []);
        $cart->save();
        return $this->returnSuccessMessage(trans('api.address_added_successfully'));
    }

    /**
     * calc checkout.
     *
     * @param  Illuminate\Http\Request $request
     * @return json
     */
    public function checkout(Request $request)
    {
        try {
            if($this->user == null) {
                return $this->returnError('403',trans('api.unauthenticated_user'));
            }
            $cart = Cart::where('user_id',$this->user->id)->first();
            $cartProducts = $cart->cartProducts;
            if(!$cart || $cartProducts->isEmpty()) {
                return $this->returnError('E100',trans('api.cart_empty'));
            }
            // intilize data
            $calc_total = 0;
            $setting = Setting::value('tax');
            $shipping_value = $cart->shipping_value ?? 0;
            $sub_total = $cart->total;
            $tax = ($setting / 100) * $sub_total;
            $calc_total = $sub_total + $tax;
            /* ********************************************************************* */
            $all_data['sub_total'] = $sub_total;
            $all_data['tax'] = $setting;
            $all_data['taxed'] = $tax;
            $all_data['delivery_value'] = $shipping_value;
            $all_data['discount_value'] = $cart->discount ?? 0;
            $all_data['total'] = $calc_total;
            $all_data['details'] = Invoice_Details_Data::collection($cartProducts);
            return $this->returnData('data',$all_data,'');
        } catch(\Exception $e) {
            Log::debug($e->getMessage().' ' .$e->getFile().'  ' .$e->getLine());
            return $this->returnError('E200',trans('api.try_again'));
        }
    }

    /**
     * make payment and return url.
     *
     * @return json
     */
    public function payment() 
    {
        try {
            if($this->user == null) {
                return $this->returnError('403',trans('api.unauthenticated_user'));
            } 
            /* ******************* start tap paymeny ************************** */
            $cart = Cart::where('user_id', $this->user->id)->first();
            if(!$cart) {
                return $this->returnError('E100',trans('api.cart_empty'));
            }
            $percent = !empty($cart->coupon->value_type) ? ($cart->coupon->value_type == 'percentage' ? true : false) : null;
            $sub_total = $cart->total;
            $discount = (($percent))  ? round((float)$cart->discount/100, 2) : -$cart->discount;
            $shipping_value = $cart->shipping_value ?? 0;
            $applyDiscount = ($percent) ? ($sub_total - ($discount * $sub_total)) : $discount + $sub_total;
            $taxvalue = Setting::value('tax') / 100;
            $tax = ceil($taxvalue * $applyDiscount);
            $calc_total = ceil($tax + $applyDiscount + $shipping_value);
            $city_name = $cart->city->{app()->getLocale().'_name'} ?? '';
            //make payment and return url
            $pay= paypage::sendPaymentCode('all')
                ->sendTransaction('sale', 'ecom')
                ->sendCart($cart->id,$calc_total,'new payment')
                ->sendCustomerDetails($this->user->name, $this->user->email, json_decode($cart->address)->phone,'flat:'. json_decode($cart->address)->flat.','.'floor:'.json_decode($cart->address)->floor.','.'building:'.json_decode($cart->address)->building,
                json_decode($cart->address)->street, $city_name, 'SA', 'SAR', $cart->created_at)
                ->shipping_same_billing()
                ->sendHideShipping(true)
                ->sendURLs(url("api/payment-callback") , asset("api/paytabs_callback"))
                ->sendLanguage(app()->getLocale())
                ->sendFramed(true)
                ->create_pay_page();
            if($pay != null) {
                return $this->returnResponseWithLink(true, '200', trans('api.payment_link'), $pay);
            }
            return $this->returnError('E200','sorry try again #000');
            /* ******************* end myfatorch **************************** */
        } catch(\Exception $e) {
            Log::debug($e->getMessage().' ' .$e->getFile().'  ' .$e->getLine());
            return $this->returnError('E200',trans('api.try_again'));
        }

    }

    /**
     * handle payment callback.
     *
     * @param  Illuminate\Http\Request $request
     * @return json
     */
    public function paymentCallback(Request $request) {
        try {
            $arr = $request->all();
            if(!empty($arr) && array_key_exists('tranRef',$arr) && array_key_exists('respMessage',$arr) && array_key_exists('respStatus',$arr)) {
                $obj =  Paypage::queryTransaction($arr['tranRef']);
                if($obj != null && $arr['respMessage'] == 'Authorised' && $arr['respStatus'] == 'A') {
                    if( property_exists($obj, 'message')  && property_exists($obj, 'success') &&
                    property_exists($obj, 'payment_result') && property_exists($obj, 'customer_details')  && property_exists($obj, 'cart_amount') &&
                    property_exists($obj, 'cart_currency') && property_exists($obj, 'cart_id')  && property_exists($obj, 'tran_type') &&
                    property_exists($obj->payment_result, 'response_status') ) {
                        $cart = Cart::where('id', $obj->cart_id)->first();
                        if( $cart != null) {
                            $percent = !empty($cart->coupon->value_type) ? ($cart->coupon->value_type == 'percentage' ? true : false) : null;
                            $sub_total = $cart->total;
                            $discount = (($percent))  ? round((float)$cart->discount/100, 2) : -$cart->discount;
                            $shipping_value = $cart->shipping_value ?? 0;
                            $applyDiscount = ($percent) ? ($sub_total - ($discount * $sub_total)) : $discount + $sub_total;
                            $taxvalue = Setting::value('tax') / 100;
                            $tax = ceil($taxvalue * $applyDiscount);
                            $calc_total = ceil($tax + $applyDiscount + $shipping_value);
                            $invoice = Invoice::create([
                                'user_id'        => $cart->user_id, 
                                'operation_date' => Carbon::now()->format('Y-m-d'), 
                                'serial_number'  => (Invoice::max('serial_number') + 1), 
                                'coupon_id'      => $cart->coupon_id, 
                                'shipping_value' => $cart->shipping_value, 
                                'tax'            => Setting::value('tax'), 
                                'total'          => $calc_total,
                                'is_paid'        => 1, 
                                'status'         => Invoice::NEW,
                                'address'        => $cart->address,
                            ]);
                            $products = new Product();
                            $cartProducts = $cart->cartProducts ?? null;
                            $invoiceDetails = [];
                            if($cartProducts->isNotEmpty()) {
                                foreach($cartProducts as $cartProduct) { 
                                    $product = $products->where('id', $cartProduct->product_id)->first();
                                    if(!empty($product->bestSelling)) {
                                        $best = $product->bestSelling;
                                        $best->count += $cartProduct->quantity; 
                                        $best->save();
                                    } else {
                                        $product->bestSelling()->create([
                                            'count' => $cartProduct->quantity,
                                        ]);
                                    }
                                    $product->stock -= $cartProduct->quantity;
                                    $product->save();
                                    $invoice_details = [
                                        'invoice_id' => $invoice->id,
                                        'product_id' => $cartProduct->product_id,
                                        'price'      => $cartProduct->price,
                                        'quantity'   => $cartProduct->quantity,
                                        'total'      => $cartProduct->total,
                                    ];
                                    $invoiceDetails[] = $invoice_details;
                                }
                                $invoice->invoiceDetails()->insert($invoiceDetails);
                                $cart->delete();
                                return redirect('https://filterr.net/user/invoice?language=ar&operation=success&invoice_id='.$invoice->id);
                            }
                        }
                    }
                }
            }
            return redirect('https://filterr.net/user/invoice?operation=fail');
        } catch(Exception $e) {
            Log::debug($e->getMessage().' ' .$e->getFile().'  ' .$e->getLine());
            return redirect('https://filterr.net/user/invoice?operation=fail');
        }
    }

    /**
    * handle payment callback.
    * @param  Illuminate\Http\Request $request
    * @return json
    */
    public function get_invoice(Request $request)
    {
        if($request->has('operation') && $request->operation == 'fail') {
            return $this->returnError('E007', trans('api.payment_error'));
        }
        if($request->has('invoice_id')) {
            $invoice = Invoice::where('id', $request->invoice_id)->with(['invoiceDetails', 'user', 'coupon', 'invoiceDetails.product'])->first();
            if(!$invoice) {
                return $this->returnError('E007', trans('api.payment_error'));
            }
            return $this->returnData('invoice', $this->mapInvoice($invoice), trans('api.order_data'));
        }
        return $this->returnError('E007', trans('api.payment_error'));
    }

    /**
    * handle payment callback.
    * @param  object $invoice
    * @return array
    */
    private function mapInvoice($invoice)
    {
        $invoiceData = $invoiceDetails = [];
        $lang      = app()->getLocale() ?? 'ar';
        $class     = new ReflectionClass(Invoice::class);
        $constants = array_flip($class->getConstants()); 
        $invoiceData['id']             = $invoice->id;
        $invoiceData['user_id']        = $invoice->user_id;
        $invoiceData['user_name']      = $invoice->user->name ?? '';
        $invoiceData['serial_number']  = $invoice->serial_number;
        $invoiceData['tax']            = $invoice->tax;
        $invoiceData['shipping_value'] = $invoice->shipping_value;
        $invoiceData['discount']       = !empty($invoice->coupon->value_type) ? ($invoice->coupon->value_type == 'percentage' ? $invoice->discount.'%' : $invoice->discount) : null;
        $invoiceData['total']          = $invoice->total;
        $invoiceData['status']         = $constants[$invoice->status ?? 1];
        $invoiceData['is_paid']        = $invoice->is_paid ? true : false;
        $invoiceData['address']        = json_decode($invoice->address ?? '');
        $invoiceData['created_at']     = Carbon::parse($invoice->created_at)->toFormattedDateString();
        $details = $invoice->invoiceDetails;
        foreach($details as $d_detail) {
            if(empty($d_detail)) {
                continue;
            }
            $detail['product_id'] = $d_detail->product_id;
            $detail['product']   = $d_detail->product->{$lang.'_title'};
            $detail['quantity']  = $d_detail->quantity;
            $detail['price']     = $d_detail->price;
            $detail['total']     = $d_detail->total;
            $invoiceDetails[] = $detail;
        }
        $invoiceData['invoice_details'] = $invoiceDetails;
        return $invoiceData;
    }

    /**
    * handle payment callback.
    * @param  int $id
    * @return json
    */
    public function my_invoices($id = null)
    {
        if($this->user == null) {
            return $this->returnError('403',trans('api.unauthenticated_user'));
        } 
        if($id == null) {
            $invoices = Invoice::where('user_id',$this->user->id)->with(['invoiceDetails', 'user', 'coupon', 'invoiceDetails.product'])->get();
            if($invoices != null && $invoices->count() > 0) {
                $data = [];
                foreach($invoices as $invoice) {
                    $data[] = $this->mapInvoice($invoice);
                }
                return $this->returnData('invoice', $data, trans('api.invoices_list'));
            } 
            return $this->returnData('data',[], trans('api.invoices_list'));
        } 
        $invoice = Invoice::where('user_id',$this->user->id)->where('id',$id)->first();
        if($invoice != null) {
            return $this->returnData('invoice', $this->mapInvoice($invoice), trans('api.order_data'));
        }
        return $this->returnError('404', trans('api.invoice_not_found'));
    }

    /**
    * handle payment callback.
    * @param  Illuminate\Http\Request $request
    * @return json
    */
    public function track_order(Request $request)
    {
        $validated_arr = [
            'serial_number' => 'required|integer|exists:invoice,serial_number',
        ];
        $custom_messages = [
            'serial_number.required' => 'الرقم التسلسلى مطلوبة',
            'serial_number.integer'  => 'الرقم التسلسلى يجب ان يحتوي علي ارقام',
            'serial_number.exists'   => 'عفوا الرقم التسلسلى غير موجود ',
        ];
        $validator = App::isLocale('en') ? Validator::make($request->all(), $validated_arr) : Validator::make($request->all(), $validated_arr, $custom_messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }
        $invoice = Invoice::where('serial_number', $request->serial_number)->select('status')->first();
        if($invoice) {
            $class     = new ReflectionClass(Invoice::class);
            $constants = array_flip($class->getConstants()); 
            $status = $constants[$invoice->status ?? 1];
            return $this->returnData('invoice_status', $status, trans('api.track_order'));
        }
        return $this->returnError('404', trans('api.invoice_not_found'));
    }

    public function getPaymetUrl($arr)
	{
        return isset( $arr['transaction'] ) && property_exists($arr['transaction'], 'url')  ? $arr['transaction']->url : null;
	}

    public function getInvoiceID($arr)
	{
        return isset( $arr['id'] ) ? $arr['id'] : 0;
	}

}

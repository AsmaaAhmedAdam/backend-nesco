<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Mail\UserMail;
use App\Models\Invoice;
use App\Models\Invoice_Details;
use App\Models\Notifications;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use AymanElmalah\MyFatoorah\Facades\MyFatoorah;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Invoice_User;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use Essam\TapPayment\Payment;


class Tab_APiController extends Controller
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


    //////////////////////////////////////////////////////////////////////////////////////////



    // post function
    public function post_function(Request $request) {

        try {

            $arr = $request->all();

            if(! empty($arr)) {

                //$Secret_API_Key = "sk_test_yh1t87xrOjEDMiGauYevUV6X";
                $Secret_API_Key = "sk_live_nlNrwLVuIZdE7qGsWj8cmty2";

                $TapPay = new Payment(['secret_api_Key'=> $Secret_API_Key]);

                $tap_id = $this->getId($arr);

                if($tap_id) {

                    $invoice = Invoice::where('invoice_id',$tap_id)->first();

                    $Charge =  $TapPay->getCharge($tap_id);

                    if($Charge != null && $invoice != null) {

                        $invoice->update([
                            'log' =>  'log-v1'
                        ]);

                        if($this->isSuccess((array)$Charge)) {

                            $invoice->update([
                                'status' => 'done',
                                'status2' => 'in progress',
                                'log' =>  'success-v1'
                            ]);

                        } else {

                            if(isset( $Charge['status'] )) {

                                $invoice->update([
                                    'status' => $Charge['status'],
                                    'log' =>  'log-v2'
                                ]);

                            } else {

                                $invoice->update([
                                    'log' =>  'log-v3'
                                ]);

                            }

                        }

                    } else {

                        $invoice->update([
                            'log' =>  'log-v5'
                        ]);
                    }

                    return response()->json(['success' => true], 200);

                } else {
                    return response()->json(['success' => true], 200);
                    //return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#002');
                }

            } else {
                return response()->json(['success' => true], 200);
                //return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#001');
            }

        } catch(Exception $e) {
            //dd($e->getMessage());
            return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#000');
        }

    }


    public function redirect_function(Request $request) {

        try {

            $arr = $request->all();

            if(! empty($arr) && array_key_exists('tap_id',$arr)) {

                //$Secret_API_Key = "sk_test_yh1t87xrOjEDMiGauYevUV6X";
                $Secret_API_Key = "sk_live_nlNrwLVuIZdE7qGsWj8cmty2";

                $TapPay = new Payment(['secret_api_Key'=> $Secret_API_Key]);

                $tap_id = $arr['tap_id'];

                $charge =  $TapPay->getCharge($tap_id);

                $order_id = $this->getOrderID($charge);

                $invoice = Invoice::where('invoice_id',$tap_id)->where('id',$order_id)->first();

                if($invoice != null && $charge != null) {

                    $user = User::where('id',$invoice->user_id)->first();

                    if($user != null) {

                        if($invoice->status == 'done') {

                            //////////////////////////////////// start success

                                Cart::where('user_id',$invoice->user_id)->delete();

                                Notifications::create([
                                    'add_by' => 'user',
                                    'user_id' => $invoice->user_id,
                                    'send_to_type' => 'admin',
                                    'send_to_id' => '1',
                                    'en_description' => 'new invoice is created with serial number  ( '.$invoice->serial_number.' )  ',
                                    'ar_description' => 'تم أضافة فاتوره جديدة برقم تسلسلي  ( '.$invoice->serial_number.' )  ',
                                    'url' => null,
                                    'seen' => 0,
                                    'type' => 'order',
                                    'item_id' => $invoice->id,
                                    'en_title' => 'new order is created',
                                    'ar_title' => '  تم أضافة طلب جديد ',
                                ]);

                                $admin = Setting::first();
                                $inv_details = Invoice_Details::where('invoice_row_id',$invoice->id)->get();

                                
                                if($admin->email != null) {
                                    Mail::to($admin->email)->send(new AdminMail($user,$invoice,$inv_details));
                                }
                                if($user != null) {
                                    Mail::to($user->email)->send(new UserMail($user,$invoice,$inv_details));
                                }
                                

                                return redirect('api/payment_operation?operation=success&order_id='.$invoice->id.'&message=Payment completed successfully please check your wallet');

                            //////////////////////////////////// end success

                        } else {

                            //dd($charge);

                            if($this->isSuccess((array)$charge)) {

                                //////////////////////////////////// start success

                                    $invoice->update([
                                        'status' => 'done',
                                        'status2' => 'in progress',
                                        'log' =>  'success-v2'
                                    ]);


                                    Cart::where('user_id',$invoice->user_id)->delete();

                                    Notifications::create([
                                        'add_by' => 'user',
                                        'user_id' => $invoice->user_id,
                                        'send_to_type' => 'admin',
                                        'send_to_id' => '1',
                                        'en_description' => 'new invoice is created with serial number  ( '.$invoice->serial_number.' )  ',
                                        'ar_description' => 'تم أضافة فاتوره جديدة برقم تسلسلي  ( '.$invoice->serial_number.' )  ',
                                        'url' => null,
                                        'seen' => 0,
                                        'type' => 'order',
                                        'item_id' => $invoice->id,
                                        'en_title' => 'new order is created',
                                        'ar_title' => '  تم أضافة طلب جديد ',
                                    ]);

                                    $admin = Setting::first();
                                    $inv_details = Invoice_Details::where('invoice_row_id',$invoice->id)->get();

                                    /*
                                    if($admin->email != null) {
                                        Mail::to($admin->email)->send(new AdminMail($user,$invoice,$inv_details));
                                    }
                                    if($user != null) {
                                        Mail::to($user->email)->send(new UserMail($user,$invoice,$inv_details));
                                    }
                                    */

                                    return redirect('api/payment_operation?operation=success&order_id='.$invoice->id.'&message=Payment completed successfully please check your wallet');

                                //////////////////////////////////// end success

                            } else {
                                return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#007');
                            }
                        }

                    } else {
                        return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#006');
                    }


                } else {
                    return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#005');
                }


            } else {
                return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#004');
            }

        } catch(Exception $e) {
            //return $e->getMessage() .'------'.$e->getLine();
            //dd($e->getMessage(),$e->getLine());
            return redirect('api/payment_operation?operation=fail&order_id=0&message=Something-went-wrong,please-try-again#003');
        }

    }



    public function isSuccess($arr)
	{
		return isset( $arr['status'] ) && strtolower( $arr['status'] ) == 'captured';
	}


	public function isInitiated($arr)
	{
		return isset( $arr['status'] ) && strtolower( $arr['status'] ) == 'initiated';
	}


	public function getPaymetUrl($arr)
	{
        return isset( $arr['transaction'] ) && property_exists($arr['transaction'], 'url')  ? $arr['transaction']->url : null;
	}


	public function getId($arr)
	{
		return $arr['id'] ?? null;
	}


    public function getOrderID($object)
	{
        return property_exists($object, 'reference') && property_exists($object->reference, 'order')  ? $object->reference->order : 0;

	}


    // find_transaction
    public function find_transaction($tap_id) {

        try {

            //$Secret_API_Key = "sk_test_yh1t87xrOjEDMiGauYevUV6X";
            $Secret_API_Key = "sk_live_nlNrwLVuIZdE7qGsWj8cmty2";

            $TapPay = new Payment(['secret_api_Key'=> $Secret_API_Key]);

            $Charge =  $TapPay->getCharge($tap_id);

            dd($Charge);

        } catch(Exception $e) {

            dd($e->getMessage());

        }

    }



    public function payment_operation(Request $request) {
      return $request->all;
    }




}

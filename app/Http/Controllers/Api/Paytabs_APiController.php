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


class Paytabs_APiController extends Controller
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


    public function return_url(Request $request) {

      try {

        $arr = $request->all();

        if(! empty($arr) && array_key_exists('tranRef',$arr) && array_key_exists('respMessage',$arr) && array_key_exists('respStatus',$arr)) {

            $obj =  Paypage::queryTransaction($arr['tranRef']);

            if($obj != null && $arr['respMessage'] == 'Authorised' && $arr['respStatus'] == 'A') {

                if(property_exists($obj, 'transaction_id') && property_exists($obj, 'message')  && property_exists($obj, 'success') &&
                   property_exists($obj, 'payment_result') && property_exists($obj, 'customer_details')  && property_exists($obj, 'cart_amount') &&
                   property_exists($obj, 'cart_currency') && property_exists($obj, 'cart_id')  && property_exists($obj, 'tran_type') &&
                   property_exists($obj->payment_result, 'response_status') ) {

                    $invoice = Invoice::where('id',$obj->cart_id)->where('status','hold')->first();
                    $user_inv = Invoice_User::where('invoice_row_id',$obj->cart_id)->first();
                    $user = User::where('id',$invoice->user_id)->first();

                    if($invoice != null && $user_inv != null && $user != null &&
                       $obj->success == true && $obj->message == 'Authorised' &&
                       $obj->payment_result->response_status == 'A') {

                        if(property_exists($obj->customer_details, 'name') && property_exists($obj->customer_details, 'email') ) {

                            if($obj->tran_type == 'Sale' && $obj->cart_amount == $invoice->total) {

                                ////////////////////////////// start success

                                Cart::where('user_id',$invoice->user_id)->delete();

                                $invoice->update([
                                    'status' => 'done',
                                    'status2' => 'in progress',
                                    'transaction_id' => $obj->transaction_id,
                                    'log' => $obj->message
                                ]);

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


                                ////////////////////////////// end sucess

                            } else {
                                return "fail #5";
                                return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
                            }

                        } else {
                            return "fail #4";
                            return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
                        }

                    } else {
                        return "fail #3";
                        return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
                    }


                } else {
                    return "fail #2";
                    return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
                }


            } else {
                return "fail #1";
                return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
            }

        } else {
            return "fail #0";
            return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
        }



      } catch(Exception $e) {
         return"fail #000  " .  $e->getMessage();
        return "fail #000";
        return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
      }


    }




    public function payment_operation(Request $request) {
      return $request->all;
    }




}

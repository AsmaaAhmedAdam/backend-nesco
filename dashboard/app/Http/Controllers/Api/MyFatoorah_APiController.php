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


class MyFatoorah_APiController extends Controller
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


    public function callback(Request $request) {

      $myfatoorah = MyFatoorah::payment($request->paymentId);

      try {

        if(! $myfatoorah) {
            return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
        } else  {

          $check = $myfatoorah->isSuccess();

          if($check) {

              $payment_request_arr = $request->all();
              $payment_details_arr = $myfatoorah->get();

              if($payment_request_arr != null && ! empty($payment_request_arr) && $payment_details_arr != null && ! empty($payment_details_arr)) {

                if(
                    array_key_exists('IsSuccess',$payment_details_arr) && $payment_details_arr['IsSuccess'] == true &&
                    array_key_exists('ValidationErrors',$payment_details_arr) && $payment_details_arr['ValidationErrors'] == null &&
                    array_key_exists('paymentId',$payment_request_arr) && $payment_request_arr['paymentId'] != null &&
                    array_key_exists('Data',$payment_details_arr) && $payment_details_arr['Data'] != null && ! empty($payment_details_arr['Data']) &&
                    array_key_exists('InvoiceStatus',$payment_details_arr['Data']) && $payment_details_arr['Data']['InvoiceStatus'] == "Paid"
                  )  {


                    //dd($payment_details_arr['Data']['InvoiceId']);

                    $invoice = Invoice::where('invoice_id',$payment_details_arr['Data']['InvoiceId'])->where('status','hold')->first();

                    if($invoice != null) {

                      $user = User::where('id',$invoice->user_id)->first();

                      if($user != null) {

                        if(array_key_exists('CustomerEmail',$payment_details_arr['Data']) && $payment_details_arr['Data']['CustomerEmail'] == $user->email) {

                            Cart::where('user_id',$invoice->user_id)->delete();

                            $invoice->update([
                                'status' => 'done',
                                'status2' => 'in progress',
                                'paymentId' => $payment_request_arr['paymentId']
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

                        }

                      }

                      return redirect('api/payment_operation?operation=fail&order_id=0&message=sorry this user not found');

                    } else {
                      return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
                  }

                }

                return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');

              } else {
                return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
              }

          }

          return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');

        }

      } catch(Exception $e) {
        return redirect('api/payment_operation?operation=fail&order_id=0&message=Something went wrong, please try again');
      }

      // It will check that payment is success or not
      // return response()->json($myfatoorah->isSuccess());

      // It will return payment response with all data
      // return response()->json($myfatoorah->get());

    }



    public function error(Request $request) {

      return redirect('api/payment_operation?operation=fail&order_id=0&message=payment is failed, please try again');
      //return $this->returnError('E600','payment is failed, please try again');
      // session()->put('fail_payemnt',$request->all());
      // return redirect('fail-payemnt');
    }


    public function payment_operation(Request $request) {
      return $request->all;
    }




}

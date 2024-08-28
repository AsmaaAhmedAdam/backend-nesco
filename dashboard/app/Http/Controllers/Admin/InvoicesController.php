<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice_Details;
use App\Models\Invoice;
use App\Models\Invoice_User;
use App\Models\Notifications;
use App\Models\User;

class InvoicesController extends Controller
{
    public function get_lang()
    {
        $lang = session()->get('admin_lang');
        if($lang == 'en' && $lang != null) {
            return $lang;
        } 
        return 'ar';
    }

    public function all_invoices()
    {
        $Item = Invoice::with(['coupon', 'user'])->orderBy('status', 'ASC')->get(['id','serial_number','user_id', 'shipping_value', 'created_at','total','coupon_id','total','status','tax']);
        return view('admin.invoices.index',compact('Item'));
    }

    public function invoice_details($invoice_row_id)
    {
        $invoice = Invoice::where('id',$invoice_row_id)->with(['coupon'])->firstOrFail();
        $Item = Invoice_Details::where('invoice_id',$invoice_row_id)->get();
        return view('admin.invoices.invoice_details',compact('Item','invoice'))->with('address', json_decode($invoice->address, true));
    }

    public function delete_invoice($id) 
    {
        $invoice = Invoice::findOrFail($id);
        Invoice_Details::where('invoice_id',$id)->delete();
        $invoice->delete();
        return redirect()->back()->with('error','this order deleted successfully');
    }

    public function print($invoice_id)
    {
        $invoice = Invoice::where('id',$invoice_id)->firstOrFail();
        $details = Invoice_Details::where('invoice_id',$invoice->id)->get();
        return view('admin.invoices.print',compact('invoice','details'));
    }

    public function update_invoice_status(Request $request) 
    {
        $lang = $this->get_lang();
        if($lang == null) {
            $lang = 'ar';app()->setLocale('ar');session()->put('admin_lang','ar');
        }
        $invoice_id = $request->invoice_id;
        $status = $request->invoice_status;
        $invoice = Invoice::where('id',$invoice_id)->firstOrFail();
        if(!$invoice) {
            abort(404);
        } 
        $status2  = '';
        if($status  == 'in progress') {
            $invoice->update(['status' => Invoice::PREPARED_FOR_SHIPPING]);
            $status2  = 'في تقدم';
        } elseif ($status  == 'delivered') {

            $invoice->update(['status' => Invoice::DELIVERD]);
            $status2  = 'تم التوصيل';

        } elseif ($status  == 'cancelled') {

            $invoice->update(['status' => Invoice::CANCELLED]);
            $status2  = 'ألغيت';

        } else {
            abort(404);
        }
        Notifications::create([
            'add_by'         => 'admin',
            'user_id'        => 1,
            'send_to_type'   => 'user',
            'send_to_id'     => $invoice->user_id,
            'en_description' => 'your order #'.$invoice->serial_number .' status is changed to '.$status.' successfully',
            'ar_description' => 'حالة الفاتوره الخاصة بك #'.$invoice->serial_number .' تم تغيرها الي '.$status2.' بنجاح',
            'url'            => null,
            'seen'           => 0,
            'type'           => 'order',
            'item_id'        => $invoice->id,
            'en_title'       => 'change order status',
            'ar_title'       => ' تغير حالة الفاتوره ',
        ]);
        $user = User::where('id',$invoice->user_id)->first();
        if ($user != null && $user->device_token != null) {
            $users_device_token = [$user->device_token];
            $title = 'تغير حالة الفاتوره';
            $body = 'حالة الفاتوره الخاصة بك #'.$invoice->serial_number .' تم تغيرها الي '.$status2.' بنجاح';
            Push_Notification($users_device_token, $title, $body);
        }
        $Item = Invoice::get(['id','serial_number','user_id','created_at','coupon_id','total','status']);
        $html = view('admin.invoices.datatable',['Item' => $Item])->render();
        if($lang == 'en') {
            return response()->json(['msg' => 'Invoice updated successfully', 'data' => $html ]);
        }
        return response()->json(['msg' => 'تم تحديث الفاتورة بنجاح', 'data' => $html ]);
    }
}

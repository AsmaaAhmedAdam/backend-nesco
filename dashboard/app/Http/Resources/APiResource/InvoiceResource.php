<?php

namespace App\Http\Resources\ApiResource;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $invoice = $invoiceDetails = [];
        $lang      = app()->getLocale() ?? 'ar';
        $class     = new ReflectionClass(Invoice::class);
        $constants = array_flip($class->getConstants()); 
        $invoice['id']             = $this->id;
        $invoice['user_id']        = $this->user_id;
        $invoice['user_name']      = $this->user->name;
        $invoice['serial_number']  = $this->serial_number;
        $invoice['tax']            = $this->tax;
        $invoice['shipping_value'] = $this->shipping_value;
        $invoice['discount']       = !empty($this->coupon->value_type) ? ($this->coupon->value_type == 'percentage' ? $this->discount.'%' : $this->discount) : null;
        $invoice['total']          = $this->total;
        $invoice['status']         = $constants[$this->status ?? 1];
        $invoice['is_paid']        = $this->is_paid ? true : false;
        $invoice['address']        = json_decode($this->address ?? '');
        $invoice['created_at']     = Carbon::parse($this->created_at)->toFormattedDateString();
        foreach($this->invoiceDetails as $details) {
            if(empty($details)) {
                continue;
            }
            $detail['detail_id'] = $this->id;
            $detail['product']   = $this->product->{$lang.'_name'};
            $detail['quantity']  = $this->quantity;
            $detail['price']     = $this->price;
            $detail['total']     = $this->total;
            $invoiceDetails[] = $detail;
        }
        $invoice['invoice_details'] = $invoiceDetails;
        return $invoice;
    }
}

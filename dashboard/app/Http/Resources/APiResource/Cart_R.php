<?php

namespace App\Http\Resources\APiResource;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
class Cart_R extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = app()->getLocale();
        $percent = !empty($this->coupon->value_type) ? ($this->coupon->value_type == 'percentage' ? true : false) : null;
        $data['id']    = $this->id;
        $data['sub_total'] = $this->total;
        $data['discount'] = (($percent))  ? round((float)$this->discount/100, 2) : -$this->discount;
        $applyDiscount = ($percent) ? ($this->total - ($data['discount'] * $this->total)) : ($data['discount']) + $this->total;
        $data['discount'] = (($percent)) ? $data['discount'].'%' : $data['discount'];
        $data['shipping_value'] = $this->shipping_value ?? null;
        $data['tax'] = Setting::value('tax').'%';
        $taxvalue = Setting::value('tax') / 100;
        $tax = $taxvalue * $applyDiscount;
        $total = $tax + $applyDiscount + $data['shipping_value'];
        $data['tax_value'] = ceil($tax);
        $data['total'] = ceil($total); 
        $data['address'] = json_decode($this->address ?? '');
        $data['city']  = (!empty($this->city)) ? ['id' => $this->city->id, 'city_name' => $this->city->{$lang.'_name'}] : null; 
        $cartProducts  = $this->cartProducts; 
        foreach($cartProducts as $product) {
            $arr['product_id'] = $product->product_id;
            $arr['quantity']   = $product->quantity;
            $arr['title']      = $product->products->{$lang.'_title'} ?? '';
            $arr['price']      = $product->price;
            $arr['total']      = $product->total;
            $arr['favourite']  = !is_null($product->products->favourite) ? true : false;
            $arr['image']      = $product->products->pic ?? '';
            $data['product_'.$product->product_id] = $arr;
        }
        return $data;
    }
}
<?php

namespace App\Http\Resources\APiResource;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
class Invoice_Details_Data extends JsonResource
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
        $product = Product::where('id',$this->product_id)->first();
        return [
            'id'       => $this->cart_id,
            'title'    => $product != null ? $product->{$lang.'_title'} : '',
            'quantity' => $this->quantity,
            'price'    => $this->price,
            'total'    => $this->total,
            'image'    => $product != null ? $product->pic : '',
        ];
    }
}

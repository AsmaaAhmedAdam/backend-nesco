<?php

namespace App\Http\Resources\APiResource;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
class Order_Data extends JsonResource
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
            'id'         => $this->id,
            'title'      => $product != null ? $product->{$lang.'_title'} : '',
            'quantity'   => $this->quantity,
            'price'      => $this->price,
            'total'      => $this->total,
            'created_at' => $this->created_at
        ];
    }
}

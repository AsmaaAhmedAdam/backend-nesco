<?php

namespace App\Http\Resources\APiResource;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class Favorite_R extends JsonResource
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
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'title'                 => $product != null ? $product->{$lang.'_title'} : '',
            'description'           => $product != null ? $product->{$lang.'_description'} : '',
            'price_before_discount' => $product != null ? $product->price_before_discount : '',
            'discount'              => $product != null ? $product->discount : '',
            'price'                 => $product != null ? $product->price : '',
            'reviews'               => $product != null ? $product->reviews : '',
            'image'                 => $product != null ? $product->pic : '',
        ];
    }
}

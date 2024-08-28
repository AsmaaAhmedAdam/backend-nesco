<?php

namespace App\Http\Resources\ApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class AllProductCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'cart_id'  => $this->cart_id,
            'quantity' => $this->quantity,
        ];
    }
}

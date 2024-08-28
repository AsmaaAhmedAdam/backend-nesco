<?php

namespace App\Http\Resources\APiResource;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Resources\Json\JsonResource;


class Auth_Product_Details extends JsonResource
{

    private $user_id;

    public function __construct($resource,$user_id) {
        // Ensure we call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->user_id = $user_id; // $user_id param passed

    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = app()->getLocale();
        $productReviews = $this->productReviews;
        $category = $this->category;
        $check_cart = null;
        if($this->user_id) {
            $check_cart = Cart::where('user_id',$this->user_id)->first();
            if($check_cart) {
                $cartProduct = CartProduct::where('cart_id', $check_cart->id)->where('product_id', $this->id)->select(['cart_id','quantity'])->first();
            }
        }
        $arr['product_details'] = [
            'id'                    => $this->id,
            'title'                 => $this->{$lang.'_title'},
            'category'              => $category != null ? $category->{$lang.'_title'} : '',
            'price_before_discount' => $this->price_before_discount,
            'discount'              => $this->discount,
            'price'                 => $this->price,
            'stock'                 => $this->stock,
            'description'           => $this->{$lang.'_description'},
            'image'                 => $this->pic,
            'favorite'              => $this->favourite != null ? true : false,
            'having_review'         => ($productReviews->isNotEmpty() && !empty($productReviews->where('user_id', $this->user_id))) ? true : false,
            'reviews'               => ($productReviews->isNotEmpty()) ? AllReviewsResource::collection($productReviews) : null,
            'rate'                  => ($productReviews->isNotEmpty()) ? $this->getRate($productReviews) : 0,
            'product_in_cart'       => (!is_null($check_cart)) ? new AllProductCartResource($cartProduct) : null,
        ];
        return $arr;

    }

     /**
     * Transform the resource into an array.
     *
     * @param  collection $authReviews
     * @return double
     */
    private function getRate($authReviews) : float 
    {
        $sum = 0;
        $count = $authReviews->count();
        foreach($authReviews as $review) {
            if(empty($review)) {
                continue;
            }
            $sum += (float)$review->value;
        }
        return ($count != 0) ? round(($sum / $count), 3) : 0.000;
    }
}

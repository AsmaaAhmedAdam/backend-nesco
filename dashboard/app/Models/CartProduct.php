<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CartProduct extends Model
{
    protected $table = 'carts_products';
    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'price', 'total'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function authCart()
    {
        return $this->belongsTo(Cart::class, 'cart_id')->where('user_id', Auth::guard('user-api')->user()->id ?? null);
    }
}

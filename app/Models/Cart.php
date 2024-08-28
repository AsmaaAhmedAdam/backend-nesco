<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Cart extends Model
{
    protected  $table = 'cart';
    public $timestamp = true;
    protected $fillable = [
        'user_id', 'city_id', 'discount', 'shipping_value', 'coupon_id', 'total', 'address'
    ];

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }
    
    public function city() 
    {
        return $this->belongsTo(Cities::class, 'city_id');    
    }
    
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function($model)
        {
            $model->cartProducts()->delete();
        });       
    }
}

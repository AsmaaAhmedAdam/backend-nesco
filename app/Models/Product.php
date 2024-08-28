<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    const NEW_ARRIVAL_PRODUCT  = 1;
    const BEST_SELLING_PRODUCT = 2;
    protected  $table = 'product';
    public $timestamps = true;
    protected $fillable = [

       'en_title' , 'ar_title' , 'category_id' ,
       'price_before_discount','discount','price' ,
       'en_description' , 'ar_description', 'pic',
       'en_url','ar_url','popularity','reviews'

    ];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }


    public function getPicAttribute($value)
    {
        return Custom_Image_Path('images',$value);
    }

    public function getImageAttribute($value)
    {
        return Custom_Image_Path('images',$value);
    }

    ############## relations ##############
    public function productReviews()
    {
        return $this->hasMany(Reviews::class, 'product_id');
    }

    public function favourite()
    {
        return $this->hasOne(Favorite::class, 'product_id')->where('user_id', Auth::guard('user-api')->user()->id ?? null);
    }

    public function cartProduct()
    {
        return $this->hasMany(CartProduct::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Categories','category_id');
    }

    public function bestSelling()
    {
        return $this->hasOne(Product_Selling::class, 'product_id');
    }
}

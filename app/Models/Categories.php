<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;



class Categories extends Model
{
    const TYPE = [
        'products' => 1,
        'menu'     => 2,
    ];
    protected  $table = 'categories';

    public $timestamp = true;

    protected $fillable = [
       'en_title' , 'ar_title' , 'en_url'  , 'ar_url', 'type', 'popularity' , 'pic'
    ];


    public function products() {
        return $this->hasMany('App\Models\Product','category_id');
    }


    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }


    public function getPicAttribute($value)
    {
        return Custom_Image_Path('images',$value);
    }

    public function getImageAttribute($value)
    {
        return Custom_Image_Path('images',$value);
    }




}

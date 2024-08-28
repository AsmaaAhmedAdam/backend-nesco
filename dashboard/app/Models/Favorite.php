<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Favorite extends Model
{

    protected  $table = 'favorite';

    public $timestamp = true;

    protected $fillable = [
        'user_id', 'product_id'
     ];


     public function toArray()
    {

        if(getallheaders() != null && ! empty(getallheaders()) && array_key_exists('language',getallheaders())) {
            $lang = getallheaders()['language'];
        } else {
            $lang = null;
        }

        if($lang == null || ($lang != 'ar' && $lang != 'en')) {
            $lang = 'ar';
        }

        $array = parent::toArray();

        $product = Product::where('id',$this->product_id)->select(['id',$lang.'_title as title','price_before_discount','price','discount','pic as image'])->first();

        if ( ! array_key_exists('product', $array)) {

            if($product != null) {
                $array['product'] = $product;
            } else {
                $array['product'] = null;
            }
        }

        return $array;


    }


}

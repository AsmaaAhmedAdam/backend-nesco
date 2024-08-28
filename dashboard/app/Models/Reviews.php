<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Reviews extends Model
{

    public $timestamps = true;

    protected  $table = 'reviews';

    protected $fillable = [
        'product_id', 'user_id', 'value', 'notes', 'status'
    ];

    public function product() {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }



}

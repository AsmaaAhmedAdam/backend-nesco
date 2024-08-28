<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Coupon extends Model
{

    protected  $table = 'coupon';

    protected $fillable = [
        'title', 'value_type', 'value', 'date_type', 'date', 'status'
    ];


    public $timestamps = true;

    public function orders() {
        return $this->hasMany('App\Models\Invoice','coupon_id');
    }




}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Invoice_User extends Model
{

    protected  $table = 'user_invoice_details';

    protected $fillable = [
        'invoice_row_id', 'serial_number', 'user_id', 'name', 'email', 'mobile',
        'address', 'city_id',
    ];


    public function city() {
        return $this->belongsTo('App\Models\Cities','city_id');
    }


    public $timestamps = true;




}

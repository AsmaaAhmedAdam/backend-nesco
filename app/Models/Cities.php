<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Cities extends Model
{

    protected  $table = 'cities';

    public $timestamp = true;

    protected $fillable = [
        'en_name','ar_name','status','shipping_value'
    ];


}

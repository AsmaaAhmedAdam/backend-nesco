<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Product_Selling extends Model
{

    protected  $table = 'product_selling';

    public $timestamp = true;

    protected $fillable = [
         'product_id', 'count'
     ];




}

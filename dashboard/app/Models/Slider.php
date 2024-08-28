<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Slider extends Model
{

    protected  $table = 'slider';

    public $timestamps = true;

    protected $fillable = [
        'pic'
    ];


    public function getPicAttribute($value)
    {
        return Image_Path($value);
    }

    public function getImageAttribute($value)
    {
        return Image_Path($value);
    }






}

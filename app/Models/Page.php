<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'en_title', 'ar_title', 'en_description', 'ar_description', 'pic'
    ];

    // public function getPicAttribute($value)
    // {
    //     return Custom_Image_Path('images',$value);
    // }
}

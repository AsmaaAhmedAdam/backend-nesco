<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Setting extends Model
{

    protected  $table = 'setting';

    public $timestamp = true;

    protected $fillable = [
        'email', 'mobile', 'website_name','facebook_link', 'instgram_link',
        'twitter_link','en_address','ar_address',
        'whatsapp','android_link','ios_link','en_policy','ar_policy'
    ];



}

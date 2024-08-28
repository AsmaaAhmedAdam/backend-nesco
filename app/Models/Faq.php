<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;


class Faq extends Model
{

    protected  $table = 'faq';

    public $timestamps = true;

    protected $fillable = [
        'en_title','ar_title',
        'en_description','ar_description'
    ];


    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }




}

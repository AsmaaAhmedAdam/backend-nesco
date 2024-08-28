<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Invoice_Details extends Model
{
    protected  $table = 'invoice_details';
    public $timestamps = true;
    protected $fillable = [
        'invoice_id', 'product_id', 'quantity', 'price', 'total'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }
}

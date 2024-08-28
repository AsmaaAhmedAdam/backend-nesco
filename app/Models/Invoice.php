<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Invoice extends Model
{

    const NEW                   = 1;
    const PREPARED_FOR_SHIPPING = 2;
    const DELIVERD              = 3;
    const REFUSED               = 4;
    const CANCELLED             = 5;
    protected  $table = 'invoice';
    public $timestamps = true;
    protected $fillable = [
        'user_id', 'operation_date', 'serial_number', 'coupon_id', 'discount', 'shipping_value', 'tax', 
        'total', 'is_paid', 'status', 'address'
    ];

    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon','coupon_id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(Invoice_Details::class, 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }
}

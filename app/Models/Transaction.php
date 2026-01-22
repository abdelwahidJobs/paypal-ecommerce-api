<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'order_id',
        'user_id',
        'cart_id',
        'paypal_order_processed_date',
        'order_processed_at',
        'provider_type',
        'provider_data',
    ];

    public function user(): BelongsTo
    {
       return  $this->belongsTo(User::class);
    }
    public function cart(): BelongsTo
    {
        return  $this->belongsTo(Cart::class);
    }
}

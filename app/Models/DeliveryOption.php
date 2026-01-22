<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{

    protected $fillable = [
        'deliveryDays',
        'price_cents'
    ];

}

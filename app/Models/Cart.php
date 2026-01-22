<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cart extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }


    protected $fillable = [
        'status',
        'user_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getPaymentSummary() : array
    {
        $items = $this->items;

        $totalItems = 0;
        $productCostCents = 0;
        $shippingCostCents = 0;

        foreach ($items as $item) {
            $totalItems += $item->quantity;
            // Snapshot prices only
            $productCostCents += $item->product->price_cents * $item->quantity;
            // Each item has its own shipping cost
            $shippingCostCents += $item->deliveryOption->price_cents;
        }

        $totalCostBeforeTaxCents = $productCostCents + $shippingCostCents;
        // Tax rate as integer (10%)
        $taxRatePercent = 10;
        // Integer-only tax calculation
        $taxCents = intdiv(
            $totalCostBeforeTaxCents * $taxRatePercent,
            100
        );

        $totalCostCents = $totalCostBeforeTaxCents + $taxCents;

        return [
            'totalItems' => $totalItems,
            'productCostCents' => $productCostCents,
            'shippingCostCents' => $shippingCostCents,
            'totalCostBeforeTaxCents' => $totalCostBeforeTaxCents,
            'taxCents' => $taxCents,
            'totalCostCents' => $totalCostCents,
        ];
    }
}

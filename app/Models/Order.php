<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getDisplayNumberAttribute(): string
    {
        return $this->order_no ?: 'ORDER-' . $this->id;
    }

    public function getDisplayTotalAttribute(): float
    {
        return (float) ($this->total_amount ?? $this->total ?? 0);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePayment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'stripe_payment_intent_id',
        'stripe_payment_method_id',
        'customer_name',
        'customer_email',
        'amount',
        'currency',
        'status',
        'failure_message',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount / 100, 2);
    }
}

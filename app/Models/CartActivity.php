<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'session_id',
        'quantity',
        'product_price',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'product_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

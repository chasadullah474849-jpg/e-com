<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variety extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'status'
    ];

    // Variety belongs to a Product
   public function product() {
    return $this->belongsTo(Product::class);
}
}

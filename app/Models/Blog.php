<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
    'uuid',
    'name',
    'title',
    'description',
    'details',
    'image',
    'status',
];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Blog $blog) {
            if (empty($blog->uuid)) {
                $blog->uuid = (string) Str::uuid();
            }
        });
    }
}

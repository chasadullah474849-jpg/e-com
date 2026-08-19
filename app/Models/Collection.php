<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Collection extends Model
{
   use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'category_id',
        'status',
        'image', // Make sure this is here for your image uploads!
    ];
    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
  protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->uuid)) {
                $collection->uuid = (string) Str::uuid();
            }
        });
    }
}


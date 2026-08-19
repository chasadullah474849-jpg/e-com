<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'uuid',
        'category_id',
        'name',
        'slug',
        'description',
        'status',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subcategory) {

            if (empty($subcategory->uuid)) {
                $subcategory->uuid =
                    (string) Str::uuid();
            }

            if (empty($subcategory->slug)) {
                $subcategory->slug =
                    Str::slug($subcategory->name);
            }
        });
    }


    /**
     * Subcategory belongs to Category
     */
    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }


    /**
     * Subcategory has many products
     */
    public function products()
    {
        return $this->hasMany(
            Product::class,
            'subcategory_id'
        );
    }
}

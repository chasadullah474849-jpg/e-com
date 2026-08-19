<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Product extends Model
{
    use HasFactory;

     protected $fillable = [
        'uuid',
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'subcategory_id',
        'status',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->uuid)) {
                $product->uuid = (string) Str::uuid();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
{
    return $this->belongsTo(SubCategory::class, 'subcategory_id');
}
  public function images()
{
    return $this->hasMany(ProductImage::class, 'product_id');

}



}

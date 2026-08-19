<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use Illuminate\Support\Str;



class Category extends Model
{
     use HasFactory;

protected $fillable = [
    'uuid',
    'name',
    'description',
    'status',
    'image',
];
      protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->uuid)) {
                $category->uuid = (string) Str::uuid();
            }
        });
    }

    public function subcategories()
{
    return $this->hasMany(SubCategory::class, 'category_id');
}
    public function blogs()
{
    return $this->hasMany(Blog::class);
}
public function collections()
{
    return $this->hasMany(Collection::class);
}
public function getStatusLabelAttribute()
    {
        return ((int)$this->status === 1) ? 'Active' : 'Inactive';
    }
    public function getRouteKeyName()
{
    return 'uuid';
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Feature extends Model
{
    use HasFactory;

    // Force Laravel to treat 'id' as an auto-incrementing integer
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Allow these fields to be mass-assigned
    protected $fillable = [
        'uuid',
        'icon',
        'title',
        'description',
        'status',
    ];
}

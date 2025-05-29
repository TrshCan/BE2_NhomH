<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'slug', 'logo_url'];
    public $timestamps = true;

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
    public function scopeLatestPaginated($query, $perPage = 10)
    {
        return $query->latest()->paginate($perPage);
    }
}

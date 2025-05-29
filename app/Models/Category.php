<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id'; // nếu không phải 'id' thì cần khai báo
    public $timestamps = true;
    protected $fillable = [
        'category_name',
        'description',


    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function scopeLatestPaginated($query, $perPage = 10)
    {
        return $query->latest()->paginate($perPage);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    // Xác định bảng tương ứng
    protected $table = 'blogs';

    // Các cột có thể gán đại trà (mass assignment)
    protected $fillable = ['title', 'slug', 'content'];
}

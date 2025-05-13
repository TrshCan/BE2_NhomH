<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id'; // nếu không phải 'id' thì cần khai báo

    protected $fillable = [
        'category_name',
        'description',


    ];
}

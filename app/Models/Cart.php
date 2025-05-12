<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['user_id'];

    public function items()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

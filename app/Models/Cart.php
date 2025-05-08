<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products in the cart.  This uses BelongsToMany because of the pivot table.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_product') // Specify the pivot table name
                    ->withTimestamps(); // If your pivot table has created_at and updated_at
    }
}


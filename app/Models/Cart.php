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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // 🟢 NEW: Find item by product ID
    public function getItemByProductId($productId)
    {
        return $this->items()->where('product_id', $productId)->first();
    }

    // 🟢 NEW: Add or increase product quantity
    public function addProduct($productId, $quantity = 1)
    {
        $item = $this->getItemByProductId($productId);

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $this->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }

    // 🟢 NEW: Update quantity
    public function updateQuantity(Request $request, $product_id)
    {
        if ($request->qty < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Số lượng không hợp lệ.'
            ]);
        }

        $cart = Auth::user()->cart;

        $item = $cart?->items()->where('product_id', $product_id)->first();

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
            ]);
        }

        if ($item->updated_at != $request->updated_at) {
            return response()->json([
                'status' => 'conflict',
                'message' => 'Số lượng sản phẩm đã bị thay đổi. Vui lòng tải lại trang.'
            ]);
        }

        $item->update(['quantity' => $request->qty]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật số lượng thành công.'
        ]);
    }


    // 🟢 NEW: Remove or decrement product
    // In Cart.php
    public function removeProduct($product_id)
    {
        $item = $this->items()->where('product_id', $product_id)->first();

        if (!$item) {
            return 'error';
        }

        if ($item->quantity > 1) {
            $item->decrement('quantity');
            return 'decremented';
        }

        $item->delete();
        return 'deleted';
    }

    public static function getOrCreateForUser($user)
    {
        return $user->cart ?? $user->cart()->create();
    }


    public function loadAndCleanItems()
    {
        try {
            $this->load('items.product');
            $removedItems = 0;

            // Remove items with missing products
            $this->items()->each(function ($item) use (&$removedItems) {
                if (!$item->product) {
                    $item->delete();
                    $removedItems++;
                }
            });

            // Refresh the cart to ensure updated items
            $this->load('items.product');

            return [
                'cart' => $this->items->isEmpty() ? null : $this,
                'removedItems' => $removedItems
            ];
        } catch (\Exception $e) {
            \Log::error('Error loading cart: ' . $e->getMessage());
            return ['cart' => null, 'removedItems' => 0];
        }
    }

    // In Cart.php
    public function updateProductQuantityIfUnchanged($product_id, $quantity, $updated_at)
    {
        $item = $this->items()->where('product_id', $product_id)->first();

        if (!$item) {
            return ['status' => 'error', 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'];
        }

        if ($item->updated_at->toDateTimeString() !== $updated_at) {
            return [
                'status' => 'error',
                'message' => 'Số lượng sản phẩm đã bị thay đổi bởi phiên làm việc khác. Vui lòng tải lại trang.',
                'latest_updated_at' => $item->updated_at->toDateTimeString()
            ];
        }

        $item->update(['quantity' => $quantity, 'updated_at' => now()]);

        return ['status' => 'success', 'message' => 'Cập nhật số lượng thành công.'];
    }
}

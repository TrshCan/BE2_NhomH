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
    public function removeProduct($productId)
    {
        $item = $this->getItemByProductId($productId);

        if ($item) {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
                return 'decremented';
            } else {
                $item->delete();
                return 'deleted';
            }
        }

        return 'not_found';
    }

    public function updateProductQuantityIfUnchanged($productId, $quantity, $originalUpdatedAt)
    {
        $item = $this->getItemByProductId($productId);

        if (!$item) {
            \Log::info('Cart item not found', ['product_id' => $productId]);
            return 'not_found';
        }

        if ($item->updated_at->toDateTimeString() !== $originalUpdatedAt) {
            \Log::info('Conflict detected', [
                'product_id' => $productId,
                'database_updated_at' => $item->updated_at->toDateTimeString(),
                'request_updated_at' => $originalUpdatedAt
            ]);
            return 'conflict';
        }

        $item->update(['quantity' => $quantity]);
        \Log::info('Quantity updated', ['product_id' => $productId, 'quantity' => $quantity]);
        return 'success';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('login')->with('error_admin', 'Bạn cần đăng nhập và có quyền admin để truy cập.');
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('login')->with('error_admin', 'Bạn không có quyền truy cập trang quản trị. Đã đăng xuất.');
        }

        $orders = Order::with('user')
            ->filter($request->only('search'))
            ->latest()
            ->paginate(10);

        return view('admin.order-management', compact('orders'));
    }


    public function show($id)
    {
        $order = Order::with(['user', 'details.product'])->findOrFail($id);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|max:50',
            'shipping_address' => 'nullable|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,product_id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'order_date' => now(),
            'status' => $validated['status'],
            'shipping_address' => $validated['shipping_address'],
            'total_amount' => collect($validated['details'])->sum(fn($d) => $d['price'] * $d['quantity']),
        ]);

        foreach ($validated['details'] as $detail) {
            $order->details()->create($detail);
        }

        return redirect()->back()->with('success', 'Order created successfully.');
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|max:50',
            'shipping_address' => 'nullable|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,product_id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        $order->update([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'shipping_address' => $validated['shipping_address'],
            'total_amount' => collect($validated['details'])->sum(fn($d) => $d['price'] * $d['quantity']),
        ]);

        // Delete existing details and create new ones
        $order->details()->delete();
        foreach ($validated['details'] as $detail) {
            $order->details()->create($detail);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->details()->delete();
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
    }
}

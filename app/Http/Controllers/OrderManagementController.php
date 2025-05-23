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
        $order = Order::with(['user', 'details.product'])->find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404); // HTTP 404 Not Found
        }

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
        $order = Order::with('user', 'details.product')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate the request, including the updated_at timestamp
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|max:50',
            'shipping_address' => 'nullable|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,product_id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.price' => 'required|numeric|min:0',
            'updated_at' => 'required|date', // Expect updated_at in the request
        ]);

        // Check for concurrent modification
        if ($order->updated_at->toDateTimeString() !== $validated['updated_at']) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng đã được thay đổi bởi admin khác, vui lòng tải lại trang.',
                'updated_at_server' => $order->updated_at->toDateTimeString(), // Original timestamp
                'updated_at_request' => $validated['updated_at'], // Incoming timestamp
            ], 409); // HTTP 409 Conflict
        }


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

        return response()->json(['success' => true, 'message' => 'Cập nhật đơn hàng thành công.']);
    }

    public function destroy(Request $request, $id)
    {
        $request = request();
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại hoặc đã bị xóa.',
            ], 404); // HTTP 404 Not Found
        }

        // Validate the updated_at timestamp
        $validated = $request->validate([
            'updated_at' => 'required|date', // Expect updated_at in the request
        ]);

        // Delete order details and order itself
        $order->details()->delete();
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Xóa đơn hàng thành công.']);
    }

}

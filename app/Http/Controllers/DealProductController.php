<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DealProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class DealProductController extends Controller
{


    public function index()
    {
        $deals = DealProduct::with('product')->paginate(10);
        $products = Product::all();
        return view('admin.quanlydeal', compact('deals', 'products'));
    }

    public function show($id)
    {
        $deal = DealProduct::with('product')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $deal]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,product_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'product_id.required' => 'Product ID là bắt buộc!',
            'product_id.exists' => 'Product ID không tồn tại!',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc!',
            'start_date.date' => 'Ngày bắt đầu phải là định dạng ngày hợp lệ!',
            'end_date.required' => 'Ngày kết thúc là bắt buộc!',
            'end_date.date' => 'Ngày kết thúc phải là định dạng ngày hợp lệ!',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu!',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $deal = DealProduct::findOrFail($id);
            $deal->update($request->only(['product_id', 'start_date', 'end_date']));
            return response()->json(['success' => true, 'message' => 'Cập nhật deal thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật deal!'], 500);
        }
    }
}

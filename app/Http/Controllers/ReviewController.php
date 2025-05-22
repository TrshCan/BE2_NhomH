<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(string $id)
    {
        $product = Product::findOrFail($id); // Fetch the product by ID
        return view('clients.pages.reviews', compact('product')); // Pass product to the view
    }
    public function create($product_id)
    {
        $product = Product::with(['category', 'brand'])->find($product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
        }

        return view('reviews.create', compact('product'));
    }

    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:8|max:255',
            'image' => 'required|image|max:2048', // Max 2MB
        ]);

        // Xử lý upload ảnh
        $image_path = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Đường dẫn đến thư mục lưu trữ
            $destinationPath = public_path('assets/images');

            // Di chuyển file vào thư mục public/assets/images
            $image->move($destinationPath, $filename);

            // Tạo đường dẫn để lưu trong cơ sở dữ liệu
            $image_path = '/assets/images/' . $filename;
        }


        // Lưu đánh giá
        $review = Review::create([
            'user_id' => Auth::id(), //Auth::id()
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'image' => $image_path,
        ]);

        if ($review) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Lỗi khi lưu đánh giá.']);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the images.
     */
    public function index()
    {
        $images = Image::paginate(10);
        $product = product::all();
        return view('admin.quanlyanh', compact('images', 'product'));
    }

    /**
     * Show the form for creating a new image.
     */
    public function create()
    {
        return view('admin.quanlyanh');
    }

    /**
     * Store a newly created image in public/assets/images.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'product_id' => 'required|integer',
        ]);

        $file = $request->file('image');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        // Tạo thư mục nếu chưa tồn tại
        $destination = public_path('assets/images');
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);

        $image = new Image();
        $image->image_url = $filename;
        $image->product_id = $request->product_id;
        $image->save();

        return response()->json(['success' => true, 'message' => 'Ảnh đã được thêm thành công!']);
    }

    /**
     * Display the specified image.
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return response()->json(['success' => true, 'data' => $image]);
    }

    /**
     * Show the form for editing the specified image.
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return response()->json(['success' => true, 'data' => $image]);
    }

    /**
     * Update the specified image in public/assets/images.
     */
    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        $request->validate([
            'image' => 'image|max:2048',
            'product_id' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $oldPath = public_path('assets/images/' . $image->image_url);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('assets/images');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $image->image_url = $filename;
        }

        $image->product_id = $request->product_id;
        $image->save();

        return response()->json(['success' => true, 'message' => 'Ảnh đã được cập nhật thành công!']);
    }

    /**
     * Remove the specified image from public/assets/images.
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        $path = public_path('assets/images/' . $image->image_url);
        if (file_exists($path)) {
            unlink($path);
        }

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Ảnh đã được xóa thành công!']);
    }
}

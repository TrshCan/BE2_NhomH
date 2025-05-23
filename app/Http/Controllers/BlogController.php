<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs for the admin interface.
     */
    public function index()
    {
        try {
            $blogs = Blog::latest()->paginate(10);
            return view('admin.quanlyblog', compact('blogs'));
        } catch (QueryException $e) {
            Log::error('Error fetching blogs: ' . $e->getMessage());
            return view('admin.quanlyblog', ['blogs' => collect([]), 'error' => 'Không thể tải danh sách bài viết.']);
        } catch (\Exception $e) {
            Log::error('Unexpected error in index: ' . $e->getMessage());
            return view('admin.quanlyblog', ['blogs' => collect([]), 'error' => 'Đã xảy ra lỗi không xác định.']);
        }
    }

    /**
     * Display a listing of published blogs for the client interface.
     */
    public function clientIndex()
    {
        try {
            $posts = Blog::published()->latest()->paginate(10);
            return view('clients.pages.blog', compact('posts'));
        } catch (QueryException $e) {
            Log::error('Error fetching published blogs: ' . $e->getMessage());
            return view('clients.pages.blog', ['posts' => collect([]), 'error' => 'Không thể tải danh sách bài viết.']);
        } catch (\Exception $e) {
            Log::error('Unexpected error in clientIndex: ' . $e->getMessage());
            return view('clients.pages.blog', ['posts' => collect([]), 'error' => 'Đã xảy ra lỗi không xác định.']);
        }
    }

    /**
     * Display the specified blog for the client interface.
     */
    public function clientShow($id)
    {
        try {
            $item = Blog::publishedById($id)->firstOrFail();
            return view('clients.pages.chitietblogs', compact('item'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Blog not found: ' . $e->getMessage(), ['id' => $id]);
            abort(404, 'Không tìm thấy bài viết.');
        } catch (QueryException $e) {
            Log::error('Database error in clientShow: ' . $e->getMessage(), ['id' => $id]);
            abort(500, 'Lỗi truy vấn cơ sở dữ liệu.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in clientShow: ' . $e->getMessage(), ['id' => $id]);
            abort(500, 'Đã xảy ra lỗi không xác định.');
        }
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:blogs,slug',
                'content' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Image is required
                'author' => 'required|string|max:255',
                'published_at' => 'nullable|date',
                'image_url' => 'nullable|string|url',
            ], [
                'image.required' => 'Phải chọn ảnh', // Custom error message
            ]);

            $data = $validated;
            $destinationPath = public_path('assets/images');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Check directory existence and permissions
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                if (!File::isWritable($destinationPath)) {
                    Log::error('Directory not writable: ' . $destinationPath);
                    throw new \Exception('Không thể ghi vào thư mục public/assets/images');
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $imageName);
                Log::info('Image saved: public/assets/images/' . $imageName);
                $data['image_url'] = $imageName;
            } elseif (!empty($request->image_url)) {
                $data['image_url'] = $request->image_url;
            } else {
                $data['image_url'] = null;
            }

            $blog = Blog::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Bài viết đã được tạo thành công!',
                'data' => $blog
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            Log::error('Database error creating blog post: ' . $e->getMessage(), $request->all());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cơ sở dữ liệu khi tạo bài viết'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error creating blog post: ' . $e->getMessage(), $request->all());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified blog.
     */
    public function show($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $blog
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Blog not found: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết!'
            ], 404);
        } catch (QueryException $e) {
            Log::error('Database error in show: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi truy vấn cơ sở dữ liệu'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error in show: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi không xác định'
            ], 500);
        }
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Image is optional
                'author' => 'required|string|max:255',
                'published_at' => 'nullable|date',
                'image_url' => 'nullable|string',
            ]);

            $data = $validated;
            $destinationPath = public_path('assets/images');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Check directory existence and permissions
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                if (!File::isWritable($destinationPath)) {
                    Log::error('Directory not writable: ' . $destinationPath);
                    throw new \Exception('Không thể ghi vào thư mục public/assets/images');
                }

                // Delete old image if exists
                if ($blog->image_url && File::exists(public_path('assets/images/' . $blog->image_url)) && !Str::startsWith($blog->image_url, ['http://', 'https://'])) {
                    File::delete(public_path('assets/images/' . $blog->image_url));
                    Log::info('Old image deleted: public/assets/images/' . $blog->image_url);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $imageName);
                Log::info('Image saved: public/assets/images/' . $imageName);
                $data['image_url'] = $imageName;
            } elseif ($request->has('image_url') && !empty($request->image_url)) {
                // Use provided image_url if it is a valid URL
                if (filter_var($request->image_url, FILTER_VALIDATE_URL)) {
                    $data['image_url'] = $request->image_url;
                } else {
                    $data['image_url'] = $blog->image_url; // Keep existing if not a valid URL
                }
            } else {
                // Keep existing image_url if no new image or image_url is provided
                $data['image_url'] = $blog->image_url;
            }

            $blog->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Bài viết đã được cập nhật thành công!',
                'data' => $blog
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Blog not found: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết!'
            ], 404);
        } catch (QueryException $e) {
            Log::error('Database error updating blog post: ' . $e->getMessage(), array_merge($request->all(), ['id' => $id]));
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cơ sở dữ liệu khi cập nhật bài viết'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error updating blog post: ' . $e->getMessage(), array_merge($request->all(), ['id' => $id]));
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            // Delete image if exists and is not a URL
            if ($blog->image_url && File::exists(public_path('assets/images/' . $blog->image_url)) && !Str::startsWith($blog->image_url, ['http://', 'https://'])) {
                File::delete(public_path('assets/images/' . $blog->image_url));
                Log::info('Image deleted: public/assets/images/' . $blog->image_url);
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bài viết đã được xóa thành công!'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Blog not found: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết!'
            ], 404);
        } catch (QueryException $e) {
            Log::error('Database error deleting blog post: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cơ sở dữ liệu khi xóa bài viết'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error deleting blog post: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}

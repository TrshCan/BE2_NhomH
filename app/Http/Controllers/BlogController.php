<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs for the admin interface.
     */
    public function index(Request $request)
    {
        try {
            $perPage = 2;
            $totalBlogs = Blog::count();
            $maxPages = ceil($totalBlogs / $perPage);
            $currentPage = $request->input('page', 1);

            // Kiểm tra page không hợp lệ
            if (!is_numeric($currentPage) || $currentPage < 1) {
                Log::warning('Invalid page parameter', ['page' => $currentPage]);
                return redirect()->route('admin.blogs.index', ['page' => 1])
                    ->with('error', 'Tham số trang không hợp lệ. Đã chuyển về trang đầu tiên.');
            }

            // Kiểm tra page vượt quá tối đa
            if ($currentPage > $maxPages && $totalBlogs > 0) {
                return redirect()->route('admin.blogs.index', ['page' => $maxPages])
                    ->with('error', 'Trang yêu cầu không tồn tại. Đã chuyển về trang cuối cùng.');
            }

            $blogs = Blog::latest()->paginate($perPage);
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
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'author' => 'required|string|max:255',
                'published_at' => 'nullable|date',
                'image_url' => 'nullable|string|url',
            ], [
                'image.required' => 'Phải chọn ảnh',
            ]);

            $data = $validated;
            $destinationPath = public_path('assets/images');

            // Handle image upload
            if ($request->hasFile('image')) {
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
            // Normalize updated_at format
            $blogData = $blog->toArray();
            $blogData['updated_at'] = $blog->updated_at->toDateTimeString(); // Y-m-d H:i:s
            return response()->json([
                'success' => true,
                'data' => $blogData
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

            // Kiểm tra xung đột chỉnh sửa
            $clientUpdatedAt = $request->input('updated_at');
            if ($clientUpdatedAt) {
                try {
                    $clientUpdatedAtCarbon = Carbon::parse($clientUpdatedAt);
                    $serverUpdatedAtCarbon = $blog->updated_at;
                    if (!$clientUpdatedAtCarbon->equalTo($serverUpdatedAtCarbon)) {
                        Log::warning('Edit conflict detected', [
                            'id' => $id,
                            'client_updated_at' => $clientUpdatedAt,
                            'server_updated_at' => $serverUpdatedAtCarbon->toDateTimeString()
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Bài viết đã được chỉnh sửa bởi người dùng khác. Vui lòng tải lại trang để cập nhật dữ liệu mới nhất.'
                        ], 409); // HTTP 409 Conflict
                    }
                } catch (\Exception $e) {
                    Log::error('Invalid client updated_at format', [
                        'id' => $id,
                        'client_updated_at' => $clientUpdatedAt,
                        'error' => $e->getMessage()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Định dạng thời gian không hợp lệ.'
                    ], 422);
                }
            } else {
                Log::warning('Missing client updated_at', ['id' => $id]);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:blogs,slug,' . $id,
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'author' => 'required|string|max:255',
                'published_at' => 'nullable|date',
                'image_url' => 'nullable|string',
            ]);

            $data = $validated;
            $destinationPath = public_path('assets/images');

            // Handle image upload
            if ($request->hasFile('image')) {
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                if (!File::isWritable($destinationPath)) {
                    Log::error('Directory not writable: ' . $destinationPath);
                    throw new \Exception('Không thể ghi vào thư mục public/assets/images');
                }

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
                if (filter_var($request->image_url, FILTER_VALIDATE_URL)) {
                    $data['image_url'] = $request->image_url;
                } else {
                    $data['image_url'] = $blog->image_url;
                }
            } else {
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

@extends('layouts.admin')

@section('content')
    <!-- Meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý Blog</h2>
            <button id="openAddModal"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center"
                aria-label="Thêm bài viết mới">
                <i class="fas fa-plus mr-2"></i> Thêm bài viết
            </button>
        </div>

        <!-- Blogs Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tác giả
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nội dung
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($blogs as $blog)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $blog->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $blog->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                @if ($blog->image_url)
                                    <img src="{{ Str::startsWith($blog->image_url, 'http') ? $blog->image_url : asset('assets/images/' . $blog->image_url) }}"
                                        alt="{{ $blog->title }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <span class="text-gray-500">No image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $blog->author }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ Str::limit($blog->content, 50, '...') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ $blog->published_at ? $blog->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $blog->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 mr-4 edit-blog"
                                    data-id="{{ $blog->id }}" aria-label="Chỉnh sửa bài viết">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-600 delete-blog" data-id="{{ $blog->id }}"
                                    aria-label="Xóa bài viết">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Không có bài viết nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $blogs->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal for Add/Edit Blog -->
    <div id="blogModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm bài viết</h3>
            <form id="blogForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="blog_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                    <input type="text" name="title" id="titleInput"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slugInput"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nội dung</label>
                    <textarea name="content" id="contentInput"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        rows="5" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                    <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <input type="hidden" name="image_url" id="image_url">
                    <div id="imagePreview" class="mt-2 hidden">
                        <img id="previewImage" src="" alt="Image Preview" class="h-20 w-20 rounded object-cover">
                        <button type="button" id="removeImage" class="text-red-500 hover:text-red-600 mt-2 text-sm"
                            aria-label="Xóa hình ảnh">Xóa hình ảnh</button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tác giả</label>
                    <input type="text" name="author" id="authorInput"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Ngày đăng</label>
                    <input type="datetime-local" name="published_at" id="publishedAtInput"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
                <div id="errorMessages" class="text-red-600 text-sm mb-4 hidden bg-red-100 p-3 rounded"></div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                        aria-label="Hủy">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200"
                        aria-label="Lưu bài viết">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-60">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="notificationTitle" class="text-lg font-semibold text-gray-800 mb-4">Thông báo</h3>
            <p id="notificationMessage" class="text-sm text-gray-600 mb-4"></p>
            <div class="flex justify-end">
                <button id="closeNotificationModal"
                    class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200"
                    aria-label="Đóng thông báo">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div id="confirmDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận xóa</h3>
            <p class="text-sm text-gray-600 mb-4">Bạn có chắc chắn muốn xóa bài viết này không?</p>
            <div class="flex justify-end space-x-2">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                    aria-label="Hủy xóa">Hủy</button>
                <button id="confirmDelete"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200"
                    aria-label="Xác nhận xóa">Xóa</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openAddModal = document.getElementById('openAddModal');
            const blogModal = document.getElementById('blogModal');
            const closeModal = document.getElementById('closeModal');
            const modalTitle = document.getElementById('modalTitle');
            const blogForm = document.getElementById('blogForm');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const removeImage = document.getElementById('removeImage');
            const errorMessages = document.getElementById('errorMessages');
            let currentBlogId = null;
            let shouldReload = false;

            // Close all modals
            function closeAllModals() {
                blogModal.classList.add('hidden');
                notificationModal.classList.add('hidden');
                confirmDeleteModal.classList.add('hidden');
                errorMessages.classList.add('hidden');
                errorMessages.innerHTML = '';
            }

            // Show notification
            function showAlert(message, type = 'success', reloadOnClose = false) {
                closeAllModals();
                notificationTitle.textContent = type === 'success' ? 'Thành công' : 'Lỗi';
                notificationMessage.textContent = message;
                notificationMessage.className =
                    `text-sm mb-4 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
                shouldReload = reloadOnClose;
                notificationModal.classList.remove('hidden');
            }

            // Show validation errors
            function showValidationErrors(errors) {
                errorMessages.classList.remove('hidden');
                errorMessages.innerHTML = Object.values(errors).flat().map(err => `<p>${err}</p>`).join('');
            }

            // Close notification and reload if needed
            function closeNotificationAndReload() {
                closeAllModals();
                if (shouldReload) {
                    location.reload();
                }
                shouldReload = false;
            }

            // Image preview
            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImage.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                    previewImage.src = '';
                }
            });

            // Remove image
            removeImage.addEventListener('click', () => {
                imageInput.value = '';
                document.getElementById('image_url').value = '';
                imagePreview.classList.add('hidden');
                previewImage.src = '';
            });

            // Open add modal
            openAddModal.addEventListener('click', () => {
                closeAllModals();
                modalTitle.textContent = 'Thêm bài viết';
                blogForm.reset();
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('image_url').value = '';
                imagePreview.classList.add('hidden');
                previewImage.src = '';
                currentBlogId = null;
                blogModal.classList.remove('hidden');
            });

            // Close add/edit modal
            closeModal.addEventListener('click', closeAllModals);
            blogModal.addEventListener('click', (e) => {
                if (e.target === blogModal) closeAllModals();
            });

            // Close notification modal
            closeNotificationModal.addEventListener('click', closeNotificationAndReload);
            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) closeNotificationAndReload();
            });

            // Handle form submission (add/update)
            blogForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitButton = blogForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Đang lưu...';

                const formData = new FormData(blogForm);
                const url = currentBlogId ?
                    `{{ route('admin.blogs.update', ['id' => ':id']) }}`.replace(':id',
                    currentBlogId) :
                    `{{ route('admin.blogs.store') }}`;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        blogForm.reset();
                        imagePreview.classList.add('hidden');
                        previewImage.src = '';
                        showAlert(result.message, 'success', true);
                    } else {
                        if (response.status === 422 && result.errors) {
                            showValidationErrors(result.errors);
                        } else {
                            showAlert(result.message || 'Có lỗi không xác định!', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Submit error:', error);
                    showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                }
            });

            // Handle edit buttons
            document.querySelector('tbody').addEventListener('click', async (e) => {
                const editButton = e.target.closest('.edit-blog');
                if (editButton) {
                    closeAllModals();
                    const blogId = editButton.dataset.id;
                    currentBlogId = blogId;
                    modalTitle.textContent = 'Chỉnh sửa bài viết';

                    try {
                        const response = await fetch(
                            `{{ route('admin.blogs.show', ['id' => ':id']) }}`.replace(':id',
                                blogId), {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            }
                        );

                        const result = await response.json();

                        if (response.ok && result.success) {
                            const form = blogForm;
                            form.querySelector('[name="id"]').value = result.data.id || '';
                            form.querySelector('[name="title"]').value = result.data.title || '';
                            form.querySelector('[name="slug"]').value = result.data.slug || '';
                            form.querySelector('[name="content"]').value = result.data.content || '';
                            form.querySelector('[name="author"]').value = result.data.author || '';
                            form.querySelector('[name="published_at"]').value = result.data
                                .published_at ?
                                new Date(result.data.published_at).toISOString().slice(0, 16) : '';
                            const imageUrl = result.data.image_url || '';
                            form.querySelector('[name="image_url"]').value = imageUrl;
                            if (imageUrl) {
                                previewImage.src = imageUrl.startsWith('http') ?
                                    imageUrl : `{{ asset('assets/images/') }}/${imageUrl}`;
                                imagePreview.classList.remove('hidden');
                            } else {
                                imagePreview.classList.add('hidden');
                                previewImage.src = '';
                            }
                            document.getElementById('formMethod').value = 'PUT';
                            blogModal.classList.remove('hidden');
                        } else {
                            showAlert(result.message || 'Không thể tải thông tin bài viết!', 'error');
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                }
            });

            // Handle delete buttons
            document.querySelector('tbody').addEventListener('click', (e) => {
                const deleteButton = e.target.closest('.delete-blog');
                if (deleteButton) {
                    closeAllModals();
                    currentBlogId = deleteButton.dataset.id;
                    confirmDeleteModal.classList.remove('hidden');
                }
            });

            // Cancel delete
            cancelDelete.addEventListener('click', closeAllModals);
            confirmDeleteModal.addEventListener('click', (e) => {
                if (e.target === confirmDeleteModal) closeAllModals();
            });

            // Confirm delete
            confirmDelete.addEventListener('click', async () => {
                const blogId = currentBlogId;
                const url = `{{ route('admin.blogs.destroy', ['id' => ':id']) }}`.replace(':id',
                    blogId);

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        showAlert(result.message, 'success', true);
                    } else {
                        showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                }
            });
        });
    </script>
@endsection

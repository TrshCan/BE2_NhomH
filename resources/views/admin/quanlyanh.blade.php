@extends('layouts.admin')

@section('content')
    <!-- Meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý Ảnh</h2>
            <button id="openAddModal"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center"
                aria-label="Thêm ảnh mới">
                <i class="fas fa-plus mr-2"></i> Thêm ảnh
            </button>
        </div>
        @if (session('error'))
            <div class="alert alert-danger mt-3" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Images Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày cập
                            nhật</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($images as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <img src="{{ asset('assets/images/' . $item->image_url) }}" alt="Image"
                                    class="h-10 w-10 rounded object-cover"
                                    onerror="this.src='{{ asset('assets/images/camera.png') }}'; this.alt='Hình ảnh không khả dụng';">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ $item->product->product_name ?? $item->product_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->created_at }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->updated_at }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 mr-4 edit-image"
                                    data-id="{{ $item->id }}" aria-label="Chỉnh sửa ảnh">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-600 delete-image" data-id="{{ $item->id }}"
                                    aria-label="Xóa ảnh">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Không có ảnh nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $images->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal for Add/Edit Image -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm ảnh</h3>
            <form id="imageForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="image_id">
                <input type="hidden" name="updated_at" id="image_updated_at">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Sản phẩm</label>
                    <select name="product_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach ($products as $item)
                            <option value="{{ $item->product_id }}">{{ $item->product_id }} - {{ $item->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                    <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/jpg"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <input type="hidden" name="image_url" id="image_url">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                        aria-label="Hủy">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200"
                        aria-label="Lưu ảnh">Lưu</button>
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
    <div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận xóa</h3>
            <p class="text-sm text-gray-600 mb-4">Bạn có chắc chắn muốn xóa danh mục này không?</p>
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
            const imageModal = document.getElementById('imageModal');
            const closeModal = document.getElementById('closeModal');
            const modalTitle = document.getElementById('modalTitle');
            const imageForm = document.getElementById('imageForm');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let currentImageId = null;
            let shouldReload = false;
            let isSubmitting = false; // Biến để kiểm soát trạng thái gửi form
            let abortController = null; // Để hủy yêu cầu AJAX trước đó

            // Close all modals
            function closeAllModals() {
                imageModal.classList.add('hidden');
                notificationModal.classList.add('hidden');
                confirmDeleteModal.classList.add('hidden');
            }

            // Show notification with proper modal management
            function showAlert(message, type = 'success', reloadOnClose = false) {
                closeAllModals();
                notificationTitle.textContent = type === 'success' ? 'Thành công' : 'Lỗi';
                notificationMessage.textContent = message;
                notificationMessage.className =
                    `text-sm mb-4 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
                shouldReload = reloadOnClose;
                notificationModal.classList.remove('hidden');
            }

            // Close notification and reload if needed
            function closeNotificationAndReload() {
                closeAllModals();
                if (shouldReload) {
                    location.reload();
                }
                shouldReload = false;
            }

            // Hàm để cập nhật trạng thái nút Lưu
            function updateSubmitButtonState(disable = true) {
                const submitButton = imageForm.querySelector('button[type="submit"]');
                if (disable) {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...';
                } else {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = 'Lưu';
                }
            }

            // Open add modal
            openAddModal.addEventListener('click', () => {
                closeAllModals();
                modalTitle.textContent = 'Thêm ảnh';
                imageForm.reset();
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('image_url').value = '';
                document.getElementById('image_updated_at').value = '';
                currentImageId = null;
                imageModal.classList.remove('hidden');
            });

            // Close add/edit modal
            closeModal.addEventListener('click', () => {
                closeAllModals();
                updateSubmitButtonState(false); // Reset trạng thái nút khi đóng modal
            });
            imageModal.addEventListener('click', (e) => {
                if (e.target === imageModal) {
                    closeAllModals();
                    updateSubmitButtonState(false); // Reset trạng thái nút khi đóng modal
                }
            });

            // Close notification modal
            closeNotificationModal.addEventListener('click', closeNotificationAndReload);
            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) closeNotificationAndReload();
            });

            // Handle form submission (add/update)
            imageForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Nếu đang gửi yêu cầu, không cho phép gửi thêm
                if (isSubmitting) {
                    return;
                }

                const formData = new FormData(imageForm);
                const url = currentImageId ?
                    `{{ route('admin.images.update', ['id' => ':id']) }}`.replace(':id',
                        currentImageId) :
                    `{{ route('admin.images.store') }}`;

                // Vô hiệu hóa nút Lưu và hiển thị trạng thái xử lý
                isSubmitting = true;
                updateSubmitButtonState(true);

                // Hủy yêu cầu trước đó nếu có
                if (abortController) {
                    abortController.abort();
                }
                abortController = new AbortController();

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: formData,
                        signal: abortController.signal
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        showAlert(result.message, 'success', true);
                    } else {
                        if (response.status === 409) {
                            showAlert(result.message ||
                                'Hình ảnh đã được chỉnh sửa bởi người khác. Vui lòng tải lại trang.',
                                'error');
                        } else if (response.status === 422 && result.errors) {
                            const errorMessages = Object.values(result.errors).flat().join('\n');
                            showAlert(errorMessages, 'error');
                        } else {
                            showAlert(result.message || 'Có lỗi không xác định!', 'error');
                        }
                    }
                } catch (error) {
                    if (error.name === 'AbortError') {
                        console.log('Yêu cầu trước đó đã bị hủy');
                    } else {
                        console.error('Submit error:', error);
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                } finally {
                    // Kích hoạt lại nút Lưu và reset trạng thái
                    isSubmitting = false;
                    updateSubmitButtonState(false);
                    abortController = null;
                }
            });

            // Handle edit buttons with event delegation
            document.querySelector('tbody').addEventListener('click', async (e) => {
                const editButton = e.target.closest('.edit-image');
                if (editButton) {
                    closeAllModals();
                    const imageId = editButton.dataset.id;
                    currentImageId = imageId;
                    modalTitle.textContent = 'Chỉnh sửa ảnh';

                    try {
                        const response = await fetch(
                            `{{ route('admin.images.show', ['id' => ':id']) }}`.replace(':id',
                                imageId), {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            const form = imageForm;
                            form.querySelector('[name="id"]').value = result.data.id || '';
                            form.querySelector('[name="product_id"]').value = result.data.product_id ||
                                '';
                            form.querySelector('[name="image_url"]').value = result.data.image_url ||
                                '';
                            form.querySelector('[name="updated_at"]').value = result.data.updated_at ||
                                '';
                            document.getElementById('formMethod').value = 'PUT';
                            imageModal.classList.remove('hidden');
                        } else {
                            showAlert(result.message || 'Không thể tải thông tin ảnh!', 'error');
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                }
            });

            // Handle delete buttons with event delegation
            document.querySelector('tbody').addEventListener('click', (e) => {
                const deleteButton = e.target.closest('.delete-image');
                if (deleteButton) {
                    closeAllModals();
                    currentImageId = deleteButton.dataset.id;
                    confirmDeleteModal.classList.remove('hidden');
                }
            });

            // Cancel delete
            cancelDelete.addEventListener('click', () => {
                closeAllModals();
            });
            confirmDeleteModal.addEventListener('click', (e) => {
                if (e.target === confirmDeleteModal) {
                    closeAllModals();
                }
            });

            // Confirm delete
            confirmDelete.addEventListener('click', async () => {
                const imageId = currentImageId;
                const url = `{{ route('admin.images.destroy', ['id' => ':id']) }}`.replace(':id',
                    imageId);

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

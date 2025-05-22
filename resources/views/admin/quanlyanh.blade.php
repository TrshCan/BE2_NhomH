@extends('layouts.admin')

@section('content')
    <!-- CSRF Token Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý ảnh</h2>
            <button id="openAddModal"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm ảnh
            </button>
        </div>

        <!-- Images Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image URL
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                            ID</th>
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
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->product_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->created_at }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->updated_at }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 mr-4 edit-image"
                                    data-id="{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-600 delete-image" data-id="{{ $item->id }}">
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

    <!-- Add/Edit Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm ảnh</h3>
            <form id="imageForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input type="file" name="image" accept="image/*"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <input type="hidden" name="image_url" id="image_url">
                </div>
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

                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition">Lưu</button>
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
                    class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-70">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận xóa</h3>
            <p class="text-sm text-gray-600 mb-4">Bạn có chắc chắn muốn xóa ảnh này?</p>
            <div class="flex justify-end space-x-2">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Hủy</button>
                <button id="confirmDelete"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Xóa</button>
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
            const editButtons = document.querySelectorAll('.edit-image');
            const deleteButtons = document.querySelectorAll('.delete-image');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let currentImageId = null;
            let shouldReload = false;

            // Close all modals
            function closeAllModals() {
                imageModal.classList.add('hidden');
                notificationModal.classList.add('hidden');
                confirmDeleteModal.classList.add('hidden');
            }

            // Show Notification
            function showAlert(message, type = 'success') {
                closeAllModals(); // Close other modals before showing notification
                notificationTitle.textContent = type === 'success' ? 'Thành công' : 'Lỗi';
                notificationMessage.textContent = message;
                notificationMessage.className =
                    `text-sm mb-4 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
                notificationModal.classList.remove('hidden');
            }

            // Close Notification and Reload if Needed
            function closeNotificationAndReload() {
                closeAllModals();
                if (shouldReload) location.reload();
            }

            // Open Add Modal
            openAddModal.addEventListener('click', () => {
                closeAllModals();
                modalTitle.textContent = 'Thêm ảnh';
                imageForm.reset();
                currentImageId = null;
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('image_url').value = '';
                imageModal.classList.remove('hidden');
            });

            // Close Modal
            closeModal.addEventListener('click', closeAllModals);
            imageModal.addEventListener('click', (e) => {
                if (e.target === imageModal) closeAllModals();
            });

            // Close Notification
            closeNotificationModal.addEventListener('click', closeNotificationAndReload);
            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) closeNotificationAndReload();
            });

            // Handle Form Submission
            imageForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!imageForm.checkValidity()) {
                    showAlert('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
                    imageForm.reportValidity();
                    return;
                }

                const submitButton = imageForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Đang lưu...';

                const formData = new FormData(imageForm);
                const url = currentImageId ?
                    `{{ route('admin.images.update', ['id' => ':id']) }}`.replace(':id',
                        currentImageId) :
                    `{{ route('admin.images.store') }}`;

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

                    const text = await response.text();
                    const result = JSON.parse(text);

                    if (response.ok && result.success) {
                        shouldReload = true;
                        showAlert(result.message, 'success');
                        imageModal.classList.add('hidden');
                    } else if (response.status === 422 && result.errors) {
                        showAlert(Object.values(result.errors).flat().join('\n'), 'error');
                    } else {
                        showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                    }
                } catch (error) {
                    showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                }
            });

            // Handle Edit Button
            editButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    currentImageId = button.dataset.id;
                    if (!currentImageId || isNaN(currentImageId)) {
                        showAlert('ID ảnh không hợp lệ!', 'error');
                        return;
                    }
                    closeAllModals();
                    modalTitle.textContent = 'Chỉnh sửa ảnh';

                    try {
                        const response = await fetch(
                            `{{ route('admin.images.show', ['id' => ':id']) }}`.replace(
                                ':id', currentImageId), {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                        const text = await response.text();
                        const image = JSON.parse(text);

                        if (response.ok && image.success) {
                            if (!image.data || !image.data.product_id) {
                                showAlert(
                                    'Dữ liệu ảnh không đầy đủ, vui lòng kiểm tra database!',
                                    'error');
                                return;
                            }

                            const form = imageForm;
                            form.querySelector('[name="product_id"]').value = image.data
                                .product_id || '';
                            form.querySelector('[name="image_url"]').value = image.data
                                .image_url || '';
                            document.getElementById('formMethod').value = 'PUT';
                            imageModal.classList.remove('hidden');
                        } else {
                            showAlert(image.message || 'Không thể tải thông tin ảnh!', 'error');
                        }
                    } catch (error) {
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                });
            });

            // Handle Delete Button
            deleteButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    currentImageId = button.dataset.id;
                    if (!currentImageId || isNaN(currentImageId)) {
                        showAlert('ID ảnh không hợp lệ!', 'error');
                        return;
                    }

                    // Check if image exists before showing delete modal
                    try {
                        const response = await fetch(
                            `{{ route('admin.images.show', ['id' => ':id']) }}`.replace(
                                ':id', currentImageId), {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                        const text = await response.text();
                        const result = JSON.parse(text);

                        if (response.ok && result.success) {
                            closeAllModals();
                            confirmDeleteModal.classList.remove('hidden');
                        } else {
                            showAlert(result.message ||
                                'Ảnh không còn tồn tại, vui lòng làm mới trang!', 'error');
                            shouldReload = true;
                        }
                    } catch (error) {
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                });
            });

            // Close Delete Modal
            cancelDelete.addEventListener('click', closeAllModals);
            confirmDeleteModal.addEventListener('click', (e) => {
                if (e.target === confirmDeleteModal) closeAllModals();
            });

            // Confirm Delete
            confirmDelete.addEventListener('click', async () => {
                const deleteButton = confirmDelete;
                deleteButton.disabled = true;
                deleteButton.textContent = 'Đang xóa...';

                try {
                    const response = await fetch(`{{ route('admin.images.destroy', ['id' => ':id']) }}`
                        .replace(':id', currentImageId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            }
                        });

                    const text = await response.text();
                    const result = JSON.parse(text);

                    if (response.ok && result.success) {
                        showAlert(result.message, 'success');
                        closeAllModals();
                        setTimeout(() => location.reload(), 1000); // Reload after showing alert
                    } else {
                        showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                    }
                } catch (error) {
                    showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                } finally {
                    deleteButton.disabled = false;
                    deleteButton.textContent = 'Xóa';
                }
            });
        });
    </script>
@endsection

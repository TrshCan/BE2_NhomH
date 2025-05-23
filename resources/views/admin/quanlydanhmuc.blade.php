@extends('layouts.admin')

@section('content')
    <!-- Meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý danh mục</h2>
            <button id="openAddModal"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center"
                aria-label="Thêm danh mục mới">
                <i class="fas fa-plus mr-2"></i> Thêm danh mục
            </button>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh
                            mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả
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
                    @forelse ($categories as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->category_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate"
                                title="{{ $item->category_name }}">{{ $item->category_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->description }}">
                                {{ $item->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->created_at }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->updated_at }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 mr-4 edit-category"
                                    data-id="{{ $item->category_id }}" aria-label="Chỉnh sửa danh mục">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-600 delete-category"
                                    data-id="{{ $item->category_id }}" aria-label="Xóa danh mục">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Không có danh mục nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal for Add/Edit Category -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm danh mục</h3>
            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="category_name">Tên danh mục</label>
                    <input type="text" name="category_name" id="category_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required maxlength="255">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="description">Mô tả</label>
                    <textarea name="description" id="description"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        rows="4"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                        aria-label="Hủy">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200"
                        aria-label="Lưu danh mục">Lưu</button>
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
            <p class="text-sm text-gray-600 mb-4">Bạn có chắc chắn muốn xóa danh mục này?</p>
            <div class="flex justify-end space-x-2">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400"
                    aria-label="Hủy xóa">Hủy</button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                    aria-label="Xác nhận xóa">Xóa</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openAddModal = document.getElementById('openAddModal');
            const categoryModal = document.getElementById('categoryModal');
            const closeModal = document.getElementById('closeModal');
            const modalTitle = document.getElementById('modalTitle');
            const categoryForm = document.getElementById('categoryForm');
            const categoryNameInput = categoryForm.querySelector('[name="category_name"]');
            const descriptionInput = categoryForm.querySelector('[name="description"]');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let currentCategoryId = null;
            let shouldReload = false;

            // Close all modals
            function closeAllModals() {
                categoryModal.classList.add('hidden');
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
                console.log('showAlert called:', {
                    message,
                    type,
                    shouldReload
                });
                notificationModal.classList.remove('hidden');
            }

            // Refresh CSRF token
            async function refreshCsrfToken() {
                try {
                    const response = await fetch('/refresh-csrf-token', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        signal: AbortSignal.timeout(5000)
                    });
                    const result = await response.json();
                    if (result.csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').content = result.csrf_token;
                        return true;
                    }
                    showAlert('Không thể làm mới CSRF token!', 'error');
                    return false;
                } catch (error) {
                    console.error('Error refreshing CSRF token:', error);
                    showAlert('Lỗi làm mới CSRF token: ' + error.message, 'error');
                    return false;
                }
            }

            // Close notification and reload if needed
            function closeNotificationAndReload() {
                closeAllModals();
                console.log('closeNotificationAndReload called, shouldReload:', shouldReload);
                if (shouldReload) {
                    location.reload();
                }
                shouldReload = false; // Reset to prevent unintended reloads
            }

            // Validate inputs client-side to match server-side rules
            categoryNameInput.addEventListener('input', () => {
                if (categoryNameInput.value.length > 255) {
                    categoryNameInput.setCustomValidity('Tên danh mục không được vượt quá 255 ký tự.');
                } else {
                    categoryNameInput.setCustomValidity('');
                }
            });

            descriptionInput.addEventListener('input', () => {
                if (descriptionInput.value.length > 255) {
                    descriptionInput.setCustomValidity('Mô tả không được vượt quá 255 ký tự.');
                } else {
                    descriptionInput.setCustomValidity('');
                }
            });

            // Open add modal
            openAddModal.addEventListener('click', () => {
                closeAllModals();
                modalTitle.textContent = 'Thêm danh mục';
                categoryForm.reset();
                categoryNameInput.setCustomValidity('');
                descriptionInput.setCustomValidity('');
                currentCategoryId = null;
                shouldReload = false;
                document.getElementById('formMethod').value = 'POST';
                categoryModal.classList.remove('hidden');
            });

            // Close add/edit modal
            closeModal.addEventListener('click', () => {
                closeAllModals();
                shouldReload = false;
            });
            categoryModal.addEventListener('click', (e) => {
                if (e.target === categoryModal) {
                    closeAllModals();
                    shouldReload = false;
                }
            });

            // Close notification modal
            closeNotificationModal.addEventListener('click', closeNotificationAndReload);
            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) closeNotificationAndReload();
            });

            // Handle form submission (add/update)
            categoryForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!categoryForm.checkValidity()) {
                    showAlert('Vui lòng điền đầy đủ các trường hợp lệ!', 'error');
                    categoryForm.reportValidity();
                    return;
                }

                const submitButton = categoryForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Đang lưu...';
                shouldReload = false;

                const formData = new FormData(categoryForm);
                if (currentCategoryId) {
                    formData.append('_method', 'PUT');
                }

                const url = currentCategoryId ?
                    `{{ route('admin.categories.update', ['id' => ':id']) }}`.replace(':id',
                        currentCategoryId) :
                    `{{ route('admin.categories.store') }}`;

                if (!url || url.includes(':id')) {
                    console.error('Invalid route configuration for:', currentCategoryId ? 'update' :
                        'store');
                    showAlert('Lỗi cấu hình hệ thống, vui lòng liên hệ quản trị viên!', 'error');
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                    return;
                }

                async function attemptRequest(retry = true) {
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData,
                            signal: AbortSignal.timeout(10000)
                        });

                        if (response.status === 419 && retry) {
                            const refreshed = await refreshCsrfToken();
                            if (refreshed) {
                                showAlert('Phiên làm việc hết hạn, đang thử lại...', 'error');
                                return attemptRequest(false);
                            }
                            submitButton.disabled = false;
                            submitButton.textContent = 'Lưu';
                            return;
                        }

                        const text = await response.text();
                        let result;
                        try {
                            result = JSON.parse(text);
                        } catch (parseError) {
                            console.error('Failed to parse JSON:', text);
                            showAlert('Phản hồi từ server không hợp lệ!', 'error');
                            submitButton.disabled = false;
                            submitButton.textContent = 'Lưu';
                            return;
                        }

                        if (response.ok && result.success) {
                            showAlert(result.message, 'success', true);
                        } else {
                            if (response.status === 422 && result.errors) {
                                const errorMessages = Object.values(result.errors).flat().join(
                                '\n');
                                showAlert(errorMessages, 'error');
                            } else {
                                showAlert(result.message || 'Có lỗi không xác định!', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Submit error:', error);
                        if (error.name === 'AbortError') {
                            showAlert('Yêu cầu hết thời gian, vui lòng thử lại!', 'error');
                        } else if (error.message.includes('NetworkError') || error.message.includes(
                                'Failed to fetch')) {
                            showAlert(
                                'Không thể kết nối đến server, vui lòng kiểm tra kết nối mạng!',
                                'error');
                        } else {
                            showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                        }
                    } finally {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Lưu';
                    }
                }

                await attemptRequest();
            });

            // Handle edit buttons with event delegation
            document.querySelector('tbody').addEventListener('click', async (e) => {
                const editButton = e.target.closest('.edit-category');
                if (editButton) {
                    closeAllModals();
                    const categoryId = editButton.dataset.id;
                    currentCategoryId = categoryId;
                    modalTitle.textContent = 'Chỉnh sửa danh mục';
                    shouldReload = false;

                    const url = `{{ route('admin.categories.show', ['id' => ':id']) }}`.replace(':id',
                        categoryId);
                    if (!url || url.includes(':id')) {
                        console.error('Invalid route configuration for show:', categoryId);
                        showAlert('Lỗi cấu hình hệ thống, vui lòng liên hệ quản trị viên!', 'error');
                        return;
                    }

                    async function attemptFetch(retry = true) {
                        try {
                            const response = await fetch(url, {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                signal: AbortSignal.timeout(10000)
                            });

                            if (response.status === 419 && retry) {
                                const refreshed = await refreshCsrfToken();
                                if (refreshed) {
                                    showAlert('Phiên làm việc hết hạn, đang thử lại...', 'error');
                                    return attemptFetch(false);
                                }
                                return;
                            }

                            const text = await response.text();
                            let category;
                            try {
                                category = JSON.parse(text);
                            } catch (parseError) {
                                console.error('Failed to parse JSON:', text);
                                showAlert('Phản hồi từ server không hợp lệ!', 'error');
                                return;
                            }

                            if (response.ok && category.success) {
                                if (!category.data || !category.data.category_name) {
                                    showAlert(
                                        'Dữ liệu danh mục không đầy đủ, vui lòng kiểm tra database!',
                                        'error');
                                    return;
                                }
                                categoryForm.querySelector('[name="category_name"]').value =
                                    category.data.category_name || '';
                                categoryForm.querySelector('[name="description"]').value = category
                                    .data.description || '';
                                document.getElementById('formMethod').value = 'PUT';
                                categoryModal.classList.remove('hidden');
                            } else {
                                showAlert(category.message || 'Không thể tải thông tin danh mục!',
                                    'error');
                            }
                        } catch (error) {
                            console.error('Fetch error:', error);
                            if (error.name === 'AbortError') {
                                showAlert('Yêu cầu hết thời gian, vui lòng thử lại!', 'error');
                            } else if (error.message.includes('NetworkError') || error.message
                                .includes('Failed to fetch')) {
                                showAlert(
                                    'Không thể kết nối đến server, vui lòng kiểm tra kết nối mạng!',
                                    'error');
                            } else {
                                showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                            }
                        }
                    }

                    await attemptFetch();
                }
            });

            // Handle delete buttons with event delegation
            document.querySelector('tbody').addEventListener('click', (e) => {
                const deleteButton = e.target.closest('.delete-category');
                if (deleteButton) {
                    closeAllModals();
                    currentCategoryId = deleteButton.dataset.id;
                    shouldReload = false;
                    confirmDeleteModal.classList.remove('hidden');
                }
            });

            // Cancel delete
            cancelDelete.addEventListener('click', () => {
                closeAllModals();
                shouldReload = false;
            });
            confirmDeleteModal.addEventListener('click', (e) => {
                if (e.target === confirmDeleteModal) {
                    closeAllModals();
                    shouldReload = false;
                }
            });

            // Confirm delete
            confirmDelete.addEventListener('click', async () => {
                const categoryId = currentCategoryId;
                const url = `{{ route('admin.categories.destroy', ['id' => ':id']) }}`.replace(':id',
                    categoryId);
                if (!url || url.includes(':id')) {
                    console.error('Invalid route configuration for destroy:', categoryId);
                    showAlert('Lỗi cấu hình hệ thống, vui lòng liên hệ quản trị viên!', 'error');
                    return;
                }
                shouldReload = false;

                async function attemptDelete(retry = true) {
                    try {
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            signal: AbortSignal.timeout(10000)
                        });

                        if (response.status === 419 && retry) {
                            const refreshed = await refreshCsrfToken();
                            if (refreshed) {
                                showAlert('Phiên làm việc hết hạn, đang thử lại...', 'error');
                                return attemptDelete(false);
                            }
                            return;
                        }

                        const text = await response.text();
                        let result;
                        try {
                            result = JSON.parse(text);
                        } catch (parseError) {
                            console.error('Failed to parse JSON:', text);
                            showAlert('Phản hồi từ server không hợp lệ!', 'error');
                            return;
                        }

                        if (response.ok && result.success) {
                            showAlert(result.message, 'success', true);
                        } else {
                            showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        if (error.name === 'AbortError') {
                            showAlert('Yêu cầu hết thời gian, vui lòng thử lại!', 'error');
                        } else if (error.message.includes('NetworkError') || error.message.includes(
                                'Failed to fetch')) {
                            showAlert(
                                'Không thể kết nối đến server, vui lòng kiểm tra kết nối mạng!',
                                'error');
                        } else {
                            showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                        }
                    }
                }

                await attemptDelete();
            });
        });
    </script>
@endsection

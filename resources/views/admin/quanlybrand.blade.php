@extends('layouts.admin')

@section('content')
    <!-- Meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý thương hiệu</h2>
            <button id="openAddModal"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm thương hiệu
            </button>
        </div>
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <!-- Brands Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên
                            thương hiệu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày cập
                            nhật</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($brands as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->name }}">
                                {{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->slug }}">
                                {{ $item->slug }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <img src="{{ asset('assets/images/' . $item->logo_url) }}" alt="{{ $item->name }}"
                                    class="h-10 w-10 rounded object-cover">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->created_at }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->updated_at }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 mr-4 edit-brand"
                                    data-id="{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-600 delete-brand" data-id="{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Hiện tại không có Brand
                                nào trong hệ thống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $brands->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal for Add/Edit Brand -->
    <div id="brandModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm thương hiệu</h3>
            <form id="brandForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="updated_at" id="updated_at">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tên thương hiệu</label>
                    <input type="text" name="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <input type="hidden" name="logo_url" id="logo_url">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="notificationTitle" class="text-lg font-semibold text-gray-800 mb-4">Thông báo</h3>
            <p id="notificationMessage" class="text-sm text-gray-600 mb-4"></p>
            <div class="flex justify-end">
                <button id="closeNotificationModal"
                    class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Đóng</button>
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
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                <button id="confirmDelete"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">Xóa</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openAddModal = document.getElementById('openAddModal');
            const brandModal = document.getElementById('brandModal');
            const closeModal = document.getElementById('closeModal');
            const modalTitle = document.getElementById('modalTitle');
            const brandForm = document.getElementById('brandForm');
            const editButtons = document.querySelectorAll('.edit-brand');
            const deleteButtons = document.querySelectorAll('.delete-brand');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            let currentBrandId = null;
            let shouldReload = false;
            let isSubmitting = false; // Biến cờ để kiểm soát trạng thái gửi form

            // Kiểm tra CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                showAlert('Không tìm thấy CSRF token, vui lòng tải lại trang!', 'error');
                return;
            }

            function showAlert(message, type = 'success', closeConfirmModal = false) {
                notificationTitle.textContent = type === 'success' ? 'Thành công' : 'Lỗi';
                notificationMessage.textContent = message;
                notificationMessage.className =
                    `text-sm mb-4 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
                notificationModal.classList.remove('hidden');
                if (closeConfirmModal) {
                    confirmDeleteModal.classList.add('hidden');
                }
            }

            function closeNotificationAndReload() {
                notificationModal.classList.add('hidden');
                if (shouldReload) {
                    location.reload();
                }
            }

            openAddModal.addEventListener('click', () => {
                modalTitle.textContent = 'Thêm thương hiệu';
                brandForm.reset();
                currentBrandId = null;
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('logo_url').value = '';
                document.getElementById('updated_at').value = '';
                brandModal.classList.remove('hidden');
            });

            closeModal.addEventListener('click', () => {
                brandModal.classList.add('hidden');
            });

            brandModal.addEventListener('click', (e) => {
                if (e.target === brandModal) {
                    brandModal.classList.add('hidden');
                }
            });

            closeNotificationModal.addEventListener('click', closeNotificationAndReload);

            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) {
                    closeNotificationAndReload();
                }
            });

            brandForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Ngăn gửi form nếu đang xử lý yêu cầu trước đó
                if (isSubmitting) {
                    return;
                }

                isSubmitting = true; // Đặt cờ là đang gửi
                const submitButton = brandForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Đang lưu...';

                const nameInput = brandForm.querySelector('[name="name"]').value.trim();
                const logoInput = brandForm.querySelector('[name="logo"]');
                const isEditMode = currentBrandId !== null;

                // Kiểm tra tên thương hiệu
                if (!nameInput) {
                    showAlert('Không được để trống tên thương hiệu!', 'error');
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                    return;
                }
                if (nameInput.length < 2) {
                    showAlert('Tên thương hiệu phải ít nhất 2 ký tự!', 'error');
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                    return;
                }
                if (nameInput.length > 255) {
                    showAlert('Tên thương hiệu không được vượt quá 255 ký tự!', 'error');
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                    return;
                }
                if (!/^[a-zA-Z0-9\s]+$/.test(nameInput)) {
                    showAlert('Tên thương hiệu không được chứa ký tự đặc biệt!', 'error');
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                    return;
                }

                // Kiểm tra logo
                if (!isEditMode && logoInput.files.length === 0) {
                    showAlert('Vui lòng chọn ảnh cho danh mục!', 'error');
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                    return;
                }
                if (logoInput.files.length > 0) {
                    const file = logoInput.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!validTypes.includes(file.type)) {
                        showAlert('Hình ảnh phải có định dạng .jpg, .png, .jpeg!', 'error');
                        isSubmitting = false;
                        submitButton.disabled = false;
                        submitButton.textContent = 'Lưu';
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        showAlert('Hình ảnh không được vượt quá 2MB!', 'error');
                        isSubmitting = false;
                        submitButton.disabled = false;
                        submitButton.textContent = 'Lưu';
                        return;
                    }
                }

                const formData = new FormData(brandForm);
                const url = currentBrandId ?
                    `{{ route('admin.brands.update', ['id' => ':id']) }}`.replace(':id',
                        currentBrandId) :
                    `{{ route('admin.brands.store') }}`;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        if (response.status === 0) {
                            throw new Error(
                                'Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.');
                        } else if (response.status === 403) {
                            throw new Error('Bạn không có quyền thực hiện hành động này!');
                        } else if (response.status === 404) {
                            throw new Error('Không tìm thấy thương hiệu!');
                        } else if (response.status === 409) {
                            const result = await response.json();
                            throw new Error(result.message ||
                                'Thương hiệu đã được chỉnh sửa bởi người khác!');
                        } else if (response.status === 422) {
                            const result = await response.json();
                            const errorMessage = result.errors?.name?.[0] || Object.values(result
                                .errors).flat().join('\n');
                            throw new Error(errorMessage);
                        } else if (response.status === 500) {
                            throw new Error('Lỗi server, vui lòng thử lại sau!');
                        } else {
                            throw new Error('Có lỗi xảy ra, mã trạng thái: ' + response.status);
                        }
                    }

                    const result = await response.json();
                    if (result.success) {
                        shouldReload = true;
                        showAlert(result.message, 'success');
                        brandModal.classList.add('hidden');
                    } else {
                        throw new Error(result.message || 'Có lỗi xảy ra!');
                    }
                } catch (error) {
                    console.error('Submit error:', error);
                    showAlert(error.message || 'Đã xảy ra lỗi không xác định!', 'error');
                } finally {
                    isSubmitting = false; // Đặt lại cờ sau khi hoàn tất
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                }
            });

            editButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const brandId = button.dataset.id;
                    currentBrandId = brandId;
                    modalTitle.textContent = 'Chỉnh sửa thương hiệu';

                    try {
                        const response = await fetch(
                            `{{ route('admin.brands.show', ['id' => ':id']) }}`.replace(
                                ':id', brandId), {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            });

                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error('Không tìm thấy thương hiệu!');
                            } else if (response.status === 403) {
                                throw new Error('Bạn không có quyền truy cập!');
                            } else {
                                throw new Error('Lỗi server, mã trạng thái: ' + response
                                .status);
                            }
                        }

                        const result = await response.json();
                        if (result.success && result.data) {
                            const form = brandForm;
                            form.querySelector('[name="name"]').value = result.data.name || '';
                            form.querySelector('[name="slug"]').value = result.data.slug || '';
                            form.querySelector('[name="logo_url"]').value = result.data
                                .logo_url || '';
                            form.querySelector('[name="updated_at"]').value = result
                                .updated_at || '';
                            document.getElementById('formMethod').value = 'PUT';
                            brandModal.classList.remove('hidden');
                        } else {
                            throw new Error(result.message ||
                                'Không thể tải thông tin thương hiệu!');
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        showAlert(error.message ||
                            'Đã xảy ra lỗi khi lấy thông tin thương hiệu!', 'error');
                    }
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    currentBrandId = button.dataset.id;
                    confirmDeleteModal.classList.remove('hidden');
                });
            });

            cancelDelete.addEventListener('click', () => {
                confirmDeleteModal.classList.add('hidden');
            });

            confirmDeleteModal.addEventListener('click', (e) => {
                if (e.target === confirmDeleteModal) {
                    confirmDeleteModal.classList.add('hidden');
                }
            });

            confirmDelete.addEventListener('click', async () => {
                const brandId = currentBrandId;
                const deleteButton = confirmDeleteModal.querySelector('#confirmDelete');
                deleteButton.disabled = true;

                try {
                    const response = await fetch(
                        `{{ route('admin.brands.destroy', ['id' => ':id']) }}`.replace(':id',
                            brandId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                    if (!response.ok) {
                        if (response.status === 404) {
                            throw new Error('Thương hiệu đã bị xóa');
                        } else if (response.status === 403) {
                            throw new Error('Bạn không có quyền xóa thương hiệu này!');
                        } else {
                            throw new Error('Lỗi server, mã trạng thái: ' + response.status);
                        }
                    }

                    const result = await response.json();
                    if (result.success) {
                        shouldReload = true;
                        showAlert(result.message, 'success', true);
                    } else {
                        throw new Error(result.message || 'Có lỗi xảy ra khi xóa!');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    showAlert(error.message || 'Đã xảy ra lỗi khi xóa thương hiệu!', 'error', true);
                } finally {
                    deleteButton.disabled = false;
                }
            });
        });
    </script>
@endsection

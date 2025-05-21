@extends('layouts.admin')

@section('content')
<!-- Meta tag for CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Quản lý danh mục</h2>
        <button id="openAddModal" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Thêm danh mục
        </button>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày cập nhật</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($categories as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->category_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->category_name }}">{{ $item->category_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->description }}">{{ $item->description }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->created_at }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->updated_at }}</td>
                        <td class="px-6 py-4 text-sm">
                            <button class="text-teal-500 hover:text-teal-600 mr-4 edit-category" data-id="{{ $item->category_id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-600 delete-category" data-id="{{ $item->category_id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Không có danh mục nào.</td>
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
                <label class="block text-sm font-medium text-gray-700">Tên danh mục</label>
                <input type="text" name="category_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" rows="4"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Lưu</button>
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
            <button id="closeNotificationModal" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Đóng</button>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận xóa</h3>
        <p class="text-sm text-gray-600 mb-4">Bạn có chắc chắn muốn xóa danh mục này?</p>
        <div class="flex justify-end space-x-2">
            <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Hủy</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Xóa</button>
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
    const editButtons = document.querySelectorAll('.edit-category');
    const deleteButtons = document.querySelectorAll('.delete-category');
    const notificationModal = document.getElementById('notificationModal');
    const closeNotificationModal = document.getElementById('closeNotificationModal');
    const notificationTitle = document.getElementById('notificationTitle');
    const notificationMessage = document.getElementById('notificationMessage');
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    let currentCategoryId = null;
    let shouldReload = false;

    function showAlert(message, type = 'success') {
        notificationTitle.textContent = type === 'success' ? 'Thành công' : 'Lỗi';
        notificationMessage.textContent = message;
        notificationMessage.className = `text-sm mb-4 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
        notificationModal.classList.remove('hidden');
    }

    function closeNotificationAndReload() {
        notificationModal.classList.add('hidden');
        if (shouldReload) {
            location.reload();
        }
    }

    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Thêm danh mục';
        categoryForm.reset();
        currentCategoryId = null;
        document.getElementById('formMethod').value = 'POST';
        categoryModal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => {
        categoryModal.classList.add('hidden');
    });

    categoryModal.addEventListener('click', (e) => {
        if (e.target === categoryModal) {
            categoryModal.classList.add('hidden');
        }
    });

    closeNotificationModal.addEventListener('click', closeNotificationAndReload);

    notificationModal.addEventListener('click', (e) => {
        if (e.target === notificationModal) {
            closeNotificationAndReload();
        }
    });

    categoryForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!categoryForm.checkValidity()) {
            showAlert('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
            categoryForm.reportValidity();
            return;
        }

        const submitButton = categoryForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Đang lưu...';

        const formData = new FormData(categoryForm);
        const data = new URLSearchParams();
        data.append('category_name', formData.get('category_name').trim());
        data.append('description', formData.get('description').trim());
        if (currentCategoryId) {
            data.append('_method', 'PUT');
        }
        console.log('Form data:', Object.fromEntries(data)); // Debug dữ liệu gửi đi

        if (!formData.get('category_name').trim()) {
            showAlert('Tên danh mục không được để trống!', 'error');
            submitButton.disabled = false;
            submitButton.textContent = 'Lưu';
            return;
        }

        const url = currentCategoryId
            ? `{{ route('admin.categories.update', ['id' => ':id']) }}`.replace(':id', currentCategoryId)
            : `{{ route('admin.categories.store') }}`;
        console.log('Request URL:', url); // Debug URL

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: data
            });

            const text = await response.text();
            console.log('Response:', text); // Debug phản hồi từ server
            let result;
            try {
                result = JSON.parse(text);
                console.log('Parsed result:', result); // Debug kết quả
            } catch (parseError) {
                console.error('Failed to parse JSON:', text);
                throw new Error('Phản hồi từ server không hợp lệ');
            }

            if (response.ok && result.success) {
                shouldReload = true;
                showAlert(result.message, 'success');
                categoryModal.classList.add('hidden');
            } else {
                if (response.status === 422 && result.errors) {
                    const errorMessage = Object.values(result.errors).flat().join('\n');
                    showAlert(errorMessage, 'error');
                } else {
                    showAlert(result.message || 'Có lỗi xảy ra!', 'error');
                }
            }
        } catch (error) {
            console.error('Submit error:', error); // Debug lỗi submit
            showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Lưu';
        }
    });

    editButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const categoryId = button.dataset.id;
            currentCategoryId = categoryId;
            modalTitle.textContent = 'Chỉnh sửa danh mục';
            console.log('Fetching category with ID:', categoryId); // Debug ID danh mục

            try {
                const response = await fetch(`{{ route('admin.categories.show', ['id' => ':id']) }}`.replace(':id', categoryId), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const text = await response.text();
                console.log('Show response:', text); // Debug phản hồi từ server
                let category;
                try {
                    category = JSON.parse(text);
                    console.log('Category data:', category); // Debug dữ liệu danh mục
                } catch (parseError) {
                    console.error('Failed to parse JSON:', text);
                    showAlert('Dữ liệu từ server không hợp lệ: ' + parseError.message, 'error');
                    return;
                }

                if (response.ok && category.success) {
                    if (!category.data || !category.data.category_name) {
                        showAlert('Dữ liệu danh mục không đầy đủ, vui lòng kiểm tra database!', 'error');
                        return;
                    }

                    const form = categoryForm;
                    form.querySelector('[name="category_name"]').value = category.data.category_name || '';
                    form.querySelector('[name="description"]').value = category.data.description || '';
                    document.getElementById('formMethod').value = 'PUT';
                    categoryModal.classList.remove('hidden');
                } else {
                    showAlert(category.message || 'Không thể tải thông tin danh mục!', 'error');
                }
            } catch (error) {
                console.error('Fetch error:', error); // Debug lỗi fetch
                showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
            }
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentCategoryId = button.dataset.id;
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
        const categoryId = currentCategoryId;
        console.log('Deleting category with ID:', categoryId); // Debug ID danh mục

        try {
            const response = await fetch(`{{ route('admin.categories.destroy', ['id' => ':id']) }}`.replace(':id', categoryId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const text = await response.text();
            console.log('Delete response:', text); // Debug phản hồi từ server
            let result;
            try {
                result = JSON.parse(text);
            } catch (parseError) {
                console.error('Failed to parse JSON:', text);
                throw new Error('Phản hồi từ server không hợp lệ');
            }

            if (response.ok && result.success) {
                shouldReload = true;
                showAlert(result.message, 'success');
                confirmDeleteModal.classList.add('hidden');
            } else {
                showAlert(result.message || 'Có lỗi xảy ra!', 'error');
            }
        } catch (error) {
            console.error('Delete error:', error); // Debug lỗi xóa
            showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
        }
    });
});
</script>
@endsection
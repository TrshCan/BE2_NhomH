
@extends('layouts.admin')

@section('content')
<!-- Meta tag for CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Quản lý thương hiệu</h2>
        <button id="openAddModal" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Thêm thương hiệu
        </button>
    </div>

    <!-- Brands Table -->
    <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên thương hiệu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày cập nhật</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($brands as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->name }}">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->slug }}">{{ $item->slug }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            <img src="{{ asset('assets/images/' . $item->logo_url) }}" alt="{{ $item->name }}" class="h-10 w-10 rounded object-cover">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->created_at }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->updated_at }}</td>
                        <td class="px-6 py-4 text-sm">
                            <button class="text-teal-500 hover:text-teal-600 mr-4 edit-brand" data-id="{{ $item->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-600 delete-brand" data-id="{{ $item->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Không có thương hiệu nào.</td>
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
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tên thương hiệu</label>
                <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Logo</label>
                <input type="file" name="logo" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                <input type="hidden" name="logo_url" id="logo_url">
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
        <p class="text-sm text-gray-600 mb-4">Bạn có chắc chắn muốn xóa thương hiệu này?</p>
        <div class="flex justify-end space-x-2">
            <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">Xóa</button>
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
        modalTitle.textContent = 'Thêm thương hiệu';
        brandForm.reset();
        currentBrandId = null;
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('logo_url').value = '';
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

        if (!brandForm.checkValidity()) {
            showAlert('Vui lòng điền đầy đủ các trường bắt buộc!', 'error');
            brandForm.reportValidity();
            return;
        }

        const submitButton = brandForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Đang lưu...';

        const formData = new FormData(brandForm);
        const url = currentBrandId
            ? `{{ route('admin.brands.update', ['id' => ':id']) }}`.replace(':id', currentBrandId)
            : `{{ route('admin.brands.store') }}`;
        console.log('Request URL:', url); // Debug URL

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
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
                brandModal.classList.add('hidden');
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
            const brandId = button.dataset.id;
            currentBrandId = brandId;
            modalTitle.textContent = 'Chỉnh sửa thương hiệu';
            console.log('Fetching brand with ID:', brandId); // Debug ID thương hiệu

            try {
                const response = await fetch(`{{ route('admin.brands.show', ['id' => ':id']) }}`.replace(':id', brandId), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const text = await response.text();
                console.log('Show response:', text); // Debug phản hồi từ server
                let brand;
                try {
                    brand = JSON.parse(text);
                    console.log('Brand data:', brand); // Debug dữ liệu thương hiệu
                } catch (parseError) {
                    console.error('Failed to parse JSON:', text);
                    showAlert('Dữ liệu từ server không hợp lệ: ' + parseError.message, 'error');
                    return;
                }

                if (response.ok && brand.success) {
                    if (!brand.data || !brand.data.name) {
                        showAlert('Dữ liệu thương hiệu không đầy đủ, vui lòng kiểm tra database!', 'error');
                        return;
                    }

                    const form = brandForm;
                    form.querySelector('[name="name"]').value = brand.data.name || '';
                    form.querySelector('[name="slug"]').value = brand.data.slug || '';
                    form.querySelector('[name="logo_url"]').value = brand.data.logo_url || '';
                    document.getElementById('formMethod').value = 'PUT';
                    brandModal.classList.remove('hidden');
                } else {
                    showAlert(brand.message || 'Không thể tải thông tin thương hiệu!', 'error');
                }
            } catch (error) {
                console.error('Fetch error:', error); // Debug lỗi fetch
                showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
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
        console.log('Deleting brand with ID:', brandId); // Debug ID thương hiệu

        try {
            const response = await fetch(`{{ route('admin.brands.destroy', ['id' => ':id']) }}`.replace(':id', brandId), {
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

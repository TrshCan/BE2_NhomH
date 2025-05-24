@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Quản lý mã giảm giá</h2>
        <div class="flex items-center space-x-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã giảm giá" class="px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                <button type="submit" class="ml-2 bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <button id="openAddModal" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm mã giảm giá
            </button>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã giảm giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($coupons as $coupon)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $coupon->code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $coupon->type === 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $coupon->type === 'percent' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . ' VND' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $coupon->is_active ? 'Hoạt động' : 'Không hoạt động' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-teal-500 hover:text-teal-600 mr-4 view-coupon" data-id="{{ $coupon->id }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-500 hover:text-blue-600 mr-4 edit-coupon" data-id="{{ $coupon->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-600 delete-coupon" data-id="{{ $coupon->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Không có mã giảm giá nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</div>

<!-- Modal for Add/Edit Coupon -->
<div id="couponModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm mã giảm giá</h3>
        <form id="couponForm">
            <input type="hidden" name="id">
            <input type="hidden" name="updated_at">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mã giảm giá</label>
                <input type="text" name="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Loại</label>
                <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    <option value="percent">Phần trăm</option>
                    <option value="fixed">Cố định</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Giá trị</label>
                <input type="number" name="value" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" class="rounded border-gray-300 text-teal-500 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <span class="ml-2 text-sm text-gray-700">Hoạt động</span>
                </label>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for View Coupon Details -->
<div id="viewCouponModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Chi tiết mã giảm giá</h3>
        <div id="couponDetailsContent" class="space-y-4">
            <!-- Coupon details will be populated via JavaScript -->
        </div>
        <div class="flex justify-end mt-4">
            <button id="closeViewModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Đóng</button>
        </div>
    </div>
</div>

<script>
    const openAddModal = document.getElementById('openAddModal');
    const couponModal = document.getElementById('couponModal');
    const closeModal = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const couponForm = document.getElementById('couponForm');
    const viewCouponModal = document.getElementById('viewCouponModal');
    const closeViewModal = document.getElementById('closeViewModal');
    const couponDetailsContent = document.getElementById('couponDetailsContent');
    const baseUrl = "{{ url('/') }}";

    // Open modal for adding coupon
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Thêm mã giảm giá';
        couponForm.reset();
        couponForm.querySelector('[name="id"]').value = '';
        couponForm.querySelector('[name="updated_at"]').value = ''; // Clear updated_at
        couponForm.querySelector('[name="is_active"]').checked = true;
        couponModal.classList.remove('hidden');
    });

    // Handle form submission
    couponForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(couponForm);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        data.is_active = formData.get('is_active') ? 1 : 0;

        console.log('Sending data:', data);

        const couponId = formData.get('id');
        const method = 'POST';
        const url = couponId ? `${baseUrl}/coupons/${couponId}/update` : `${baseUrl}/coupons`;

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text);
                throw new Error(`Server returned non-JSON response (status: ${response.status})`);
            }

            const result = await response.json();
            if (response.ok) {
                alert(result.message || 'Mã giảm giá đã được lưu thành công.');
                location.reload();
            } else if (response.status === 409) {
                // Handle conflict due to updated_at mismatch
                alert(result.message || 'Mã giảm giá đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu.');
            } else {
                console.error('Server error:', result.message);
                let errorMessage = result.message || 'Có lỗi xảy ra khi lưu mã giảm giá';
                if (result.errors) {
                    errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                }
                alert(errorMessage);
            }
        } catch (error) {
            console.error('Lỗi khi lưu mã giảm giá:', error);
            alert('Lỗi khi lưu mã giảm giá: ' + error.message);
        }
    });

    // Edit coupon
    document.querySelectorAll('.edit-coupon').forEach(button => {
        button.addEventListener('click', async () => {
            const couponId = button.dataset.id;
            try {
                const response = await fetch(`${baseUrl}/coupons/${couponId}`);
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response');
                }
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Không thể tải dữ liệu mã giảm giá');
                }
                const coupon = result.coupon;
                modalTitle.textContent = 'Chỉnh sửa mã giảm giá';
                couponForm.querySelector('[name="id"]').value = coupon.id;
                couponForm.querySelector('[name="code"]').value = coupon.code;
                couponForm.querySelector('[name="type"]').value = coupon.type;
                couponForm.querySelector('[name="value"]').value = coupon.value;
                couponForm.querySelector('[name="is_active"]').checked = coupon.is_active;
                couponForm.querySelector('[name="updated_at"]').value = result.updated_at; // Set updated_at
                couponModal.classList.remove('hidden');
            } catch (error) {
                console.error('Lỗi khi tải dữ liệu mã giảm giá:', error);
                alert('Có lỗi khi tải dữ liệu mã giảm giá: ' + error.message);
            }
        });
    });

    // View coupon details
    document.querySelectorAll('.view-coupon').forEach(button => {
        button.addEventListener('click', async () => {
            const couponId = button.dataset.id;
            try {
                const response = await fetch(`${baseUrl}/coupons/${couponId}`);
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response');
                }
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Không thể tải chi tiết mã giảm giá');
                }
                const coupon = result.coupon;
                couponDetailsContent.innerHTML = `
                    <p><strong>Mã giảm giá:</strong> ${coupon.code}</p>
                    <p><strong>Loại:</strong> ${coupon.type === 'percent' ? 'Phần trăm' : 'Cố định'}</p>
                    <p><strong>Giá trị:</strong> ${coupon.type === 'percent' ? coupon.value + '%' : new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(coupon.value)}</p>
                    <p><strong>Trạng thái:</strong> ${coupon.is_active ? 'Hoạt động' : 'Không hoạt động'}</p>
                    <p><strong>Ngày tạo:</strong> ${new Date(coupon.created_at).toLocaleString('vi-VN')}</p>
                    <p><strong>Ngày cập nhật:</strong> ${new Date(coupon.updated_at).toLocaleString('vi-VN')}</p>
                `;
                viewCouponModal.classList.remove('hidden');
            } catch (error) {
                console.error('Lỗi khi tải chi tiết mã giảm giá:', error);
                alert('Có lỗi khi tải chi tiết mã giảm giá: ' + error.message);
            }
        });
    });

    // Delete coupon
    document.querySelectorAll('.delete-coupon').forEach(button => {
        button.addEventListener('click', async () => {
            if (confirm('Bạn có chắc muốn xóa mã giảm giá này?')) {
                const couponId = button.dataset.id;
                try {
                    // Fetch coupon to get updated_at
                    const fetchResponse = await fetch(`${baseUrl}/coupons/${couponId}`);
                    const contentTypeFetch = fetchResponse.headers.get('content-type');
                    if (!contentTypeFetch || !contentTypeFetch.includes('application/json')) {
                        throw new Error('Server returned non-JSON response');
                    }
                    const fetchResult = await fetchResponse.json();
                    if (!fetchResult.success) {
                        throw new Error(fetchResult.message || 'Không thể tải dữ liệu mã giảm giá');
                    }
                    const updatedAt = fetchResult.updated_at;

                    // Send delete request with updated_at as query parameter
                    const response = await fetch(`${baseUrl}/coupons/${couponId}/delete?updated_at=${encodeURIComponent(updatedAt)}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response');
                    }
                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message || 'Mã giảm giá đã được xóa thành công.');
                        location.reload();
                    } else if (response.status === 409) {
                        // Handle conflict due to updated_at mismatch
                        alert(result.message || 'Mã giảm giá đã được chỉnh sửa bởi người khác. Vui lòng tải lại dữ liệu.');
                    } else {
                        console.error('Server error:', result);
                        throw new Error(result.message || 'Có lỗi xảy ra khi xóa mã giảm giá');
                    }
                } catch (error) {
                    console.error('Lỗi khi xóa mã giảm giá:', error);
                    alert('Có lỗi khi xóa mã giảm giá: ' + error.message);
                }
            }
        });
    });

    // Close modals
    closeModal.addEventListener('click', () => {
        couponModal.classList.add('hidden');
    });

    closeViewModal.addEventListener('click', () => {
        viewCouponModal.classList.add('hidden');
    });

    couponModal.addEventListener('click', (e) => {
        if (e.target === couponModal) {
            couponModal.classList.add('hidden');
        }
    });

    viewCouponModal.addEventListener('click', (e) => {
        if (e.target === viewCouponModal) {
            viewCouponModal.classList.add('hidden');
        }
    });
</script>
@endsection

@extends('layouts.admin')

@section('content')
    <!-- Meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý Deal</h2>
        </div>

        <!-- Deals Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày bắt
                            đầu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày kết
                            thúc</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($deals as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->product_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->product->product_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->start_date }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->end_date }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button class="text-teal-500 hover:text-teal-600 edit-deal" data-id="{{ $item->id }}"
                                    aria-label="Chỉnh sửa deal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Không có deal nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $deals->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Modal for Edit Deal -->
    <div id="dealModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Chỉnh sửa Deal</h3>
            <form id="dealForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="PUT">
                <input type="hidden" name="id" id="deal_id">
                <input type="hidden" name="updated_at" id="updated_at">
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
                    <label class="block text-sm font-medium text-gray-700" for="start_date">Ngày bắt đầu</label>
                    <input type="date" name="start_date" id="start_date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="end_date">Ngày kết thúc</label>
                    <input type="date" name="end_date" id="end_date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                        aria-label="Hủy">Hủy</button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200"
                        aria-label="Lưu deal">Lưu</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dealModal = document.getElementById('dealModal');
            const closeModal = document.getElementById('closeModal');
            const dealForm = document.getElementById('dealForm');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationModal = document.getElementById('closeNotificationModal');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            let shouldReload = false;

            // Helper function to format date to YYYY-MM-DD
            function formatDateToYMD(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return ''; // Invalid date
                return date.toISOString().split('T')[0]; // Returns YYYY-MM-DD
            }

            // Close all modals
            function closeAllModals() {
                dealModal.classList.add('hidden');
                notificationModal.classList.add('hidden');
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

            // Handle edit buttons with event delegation
            document.querySelector('tbody').addEventListener('click', async (e) => {
                const editButton = e.target.closest('.edit-deal');
                if (editButton) {
                    closeAllModals();
                    const dealId = editButton.dataset.id;
                    const url = `{{ route('admin.deals.show', ['id' => ':id']) }}`.replace(':id',
                        dealId);

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

                        const text = await response.text();
                        let deal;
                        try {
                            deal = JSON.parse(text);
                        } catch (parseError) {
                            console.error('Failed to parse JSON:', text);
                            showAlert('Phản hồi từ server không hợp lệ!', 'error');
                            return;
                        }

                        if (response.ok && deal.success) {
                            if (!deal.data || !deal.data.id) {
                                showAlert('Dữ liệu deal không đầy đủ, vui lòng kiểm tra database!',
                                    'error');
                                return;
                            }
                            dealForm.querySelector('[name="id"]').value = deal.data.id || '';
                            dealForm.querySelector('[name="product_id"]').value = deal.data
                                .product_id || '';
                            dealForm.querySelector('[name="start_date"]').value = formatDateToYMD(deal
                                .data.start_date);
                            dealForm.querySelector('[name="end_date"]').value = formatDateToYMD(deal
                                .data.end_date);
                            dealForm.querySelector('[name="updated_at"]').value = deal.data
                                .updated_at || '';
                            dealModal.classList.remove('hidden');
                        } else {
                            showAlert(deal.message || 'Không thể tải thông tin deal!', 'error');
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        if (error.name === 'AbortError') {
                            showAlert('Yêu cầu hết thời gian, vui lòng thử lại!', 'error');
                        } else if (error.message.includes('NetworkError') || error.message.includes(
                                'Failed to fetch')) {
                            showAlert('Không thể kết nối đến server, vui lòng kiểm tra kết nối mạng!',
                                'error');
                        } else {
                            showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                        }
                    }
                }
            });

            // Close edit modal
            closeModal.addEventListener('click', () => {
                closeAllModals();
            });
            dealModal.addEventListener('click', (e) => {
                if (e.target === dealModal) {
                    closeAllModals();
                }
            });

            // Close notification modal
            closeNotificationModal.addEventListener('click', closeNotificationAndReload);
            notificationModal.addEventListener('click', (e) => {
                if (e.target === notificationModal) closeNotificationAndReload();
            });

            // Handle form submission (update)
            dealForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!dealForm.checkValidity()) {
                    showAlert('Vui lòng điền đầy đủ các trường hợp lệ!', 'error');
                    dealForm.reportValidity();
                    return;
                }

                const submitButton = dealForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Đang lưu...';
                shouldReload = false;

                const formData = new FormData(dealForm);
                const url = `{{ route('admin.deals.update', ['id' => ':id']) }}`.replace(':id',
                    dealForm.querySelector('[name="id"]').value);

                try {
                    const response = await fetch(url, {
                        method: 'POST', // Using POST with _method=PUT for Laravel method spoofing
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

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
                    } else if (response.status === 409) {
                        showAlert(
                            result.message ||
                            'Deal đã được chỉnh sửa bởi người dùng khác. Vui lòng tải lại trang để cập nhật dữ liệu mới nhất!',
                            'error', true
                        );
                    } else if (response.status === 422 && result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        showAlert(errorMessages, 'error');
                    } else {
                        showAlert(result.message || 'Có lỗi không xác định!', 'error');
                    }
                } catch (error) {
                    console.error('Submit error:', error);
                    if (error.message.includes('NetworkError') || error.message.includes(
                            'Failed to fetch')) {
                        showAlert('Không thể kết nối đến server, vui lòng kiểm tra kết nối mạng!',
                            'error');
                    } else {
                        showAlert('Đã xảy ra lỗi: ' + error.message, 'error');
                    }
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                }
            });
        });
    </script>
@endsection

@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Quản lý đơn hàng</h2>
        <div class="flex items-center space-x-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã đơn hàng hoặc khách hàng" class="px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                <button type="submit" class="ml-2 bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <button id="openAddModal" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Thêm đơn hàng
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ giao</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->order_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $order->user->name }}">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->order_date->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $order->status }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $order->shipping_address }}">{{ $order->shipping_address }}</td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-teal-500 hover:text-teal-600 mr-4 view-order" data-id="{{ $order->order_id }}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-blue-500 hover:text-blue-600 mr-4 edit-order" data-id="{{ $order->order_id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-600 delete-order" data-id="{{ $order->order_id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Không có đơn hàng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>

<!-- Modal for Add/Edit Order -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm đơn hàng</h3>
        <form id="orderForm">
            <input type="hidden" name="order_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">ID Khách hàng</label>
                <input type="number" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    <option value="pending">Chờ xử lý</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="shipped">Đã giao</option>
                    <option value="delivered">Đã nhận</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng</label>
                <textarea name="shipping_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
            </div>
            <div id="orderDetails" class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Chi tiết đơn hàng</label>
                <div id="detailsContainer">
                    <div class="detail-item mb-2 flex space-x-2">
                        <input type="number" name="details[0][product_id]" placeholder="ID Sản phẩm" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                        <input type="number" name="details[0][quantity]" placeholder="Số lượng" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    </div>
                </div>
                <button type="button" id="addDetail" class="text-teal-500 hover:text-teal-600 text-sm">+ Thêm sản phẩm</button>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200" disabled>Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for View Order Details -->
<div id="viewOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Chi tiết đơn hàng</h3>
        <div id="orderDetailsContent" class="space-y-4">
            <!-- Order details will be populated via JavaScript -->
        </div>
        <div class="flex justify-end mt-4">
            <button id="closeViewModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Đóng</button>
        </div>
    </div>
</div>

<script>
    const openAddModal = document.getElementById('openAddModal');
    const orderModal = document.getElementById('orderModal');
    const closeModal = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const orderForm = document.getElementById('orderForm');
    const addDetail = document.getElementById('addDetail');
    const detailsContainer = document.getElementById('detailsContainer');
    const viewOrderModal = document.getElementById('viewOrderModal');
    const closeViewModal = document.getElementById('closeViewModal');
    const orderDetailsContent = document.getElementById('orderDetailsContent');
    const baseUrl = "{{ url('/') }}"; // Fixed baseUrl for consistency

    let detailCount = 1;

    // Function to update submit button state
    function updateSubmitButtonState() {
        const submitButton = orderForm.querySelector('button[type="submit"]');
        const priceInputs = detailsContainer.querySelectorAll('input[name*="details"][name$="[price]"]');
        const allPricesValid = Array.from(priceInputs).every(input => input.value && Number(input.value) > 0);
        submitButton.disabled = !allPricesValid;
    }

    // Fetch product price
    async function fetchProductPrice(productId, detailIndex) {
        try {
            const response = await fetch(`${baseUrl}/product/get/${productId}`);
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error(`Sản phẩm không tồn tại. Vui lòng nhập ID hợp lệ.`);
                }
                throw new Error(`Không thể lấy giá sản phẩm: ${response.statusText}`);
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server trả về phản hồi không phải JSON');
            }
            const product = await response.json();
            console.log('Product price fetched:', product);
            const hiddenPriceInput = document.querySelector(`input[name="details[${detailIndex}][price]"]`);
            if (hiddenPriceInput) {
                hiddenPriceInput.value = product.price;
            } else {
                const newHiddenInput = document.createElement('input');
                newHiddenInput.type = 'hidden';
                newHiddenInput.name = `details[${detailIndex}][price]`;
                newHiddenInput.value = product.price;
                detailsContainer.querySelector(`.detail-item:nth-child(${detailIndex + 1})`).appendChild(newHiddenInput);
            }
            updateSubmitButtonState();
        } catch (error) {
            console.error('Lỗi khi lấy giá sản phẩm:', error);
            alert(error.message);
            const productInput = document.querySelector(`input[name="details[${detailIndex}][product_id]"]`);
            if (productInput) {
                productInput.value = '';
                productInput.focus();
            }
            updateSubmitButtonState();
        }
    }

    // Open modal for adding order
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Thêm đơn hàng';
        orderForm.reset();
        detailsContainer.innerHTML = `
            <div class="detail-item mb-2 flex space-x-2">
                <input type="number" name="details[0][product_id]" placeholder="ID Sản phẩm" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                <input type="number" name="details[0][quantity]" placeholder="Số lượng" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
        `;
        detailCount = 1;
        updateSubmitButtonState();
        orderModal.classList.remove('hidden');
    });

    // Add more order detail fields
    addDetail.addEventListener('click', () => {
        const detailItem = document.createElement('div');
        detailItem.className = 'detail-item mb-2 flex space-x-2';
        detailItem.innerHTML = `
            <input type="number" name="details[${detailCount}][product_id]" placeholder="ID Sản phẩm" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
            <input type="number" name="details[${detailCount}][quantity]" placeholder="Số lượng" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
        `;
        detailsContainer.appendChild(detailItem);
        detailCount++;
        updateSubmitButtonState();
    });

    // Handle product_id input change to fetch price
    detailsContainer.addEventListener('change', (e) => {
        if (e.target.classList.contains('product-id-input')) {
            const productId = e.target.value;
            const detailIndex = e.target.name.match(/details\[(\d+)\]/)[1];
            if (productId) {
                fetchProductPrice(productId, detailIndex);
            }
        }
    });

    // Handle form submission
    orderForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(orderForm);
        const data = {};
        formData.forEach((value, key) => {
            if (key.startsWith('details')) {
                const matches = key.match(/details\[(\d+)\]\[(\w+)\]/);
                if (matches) {
                    const index = matches[1];
                    const field = matches[2];
                    if (!data.details) data.details = [];
                    if (!data.details[index]) data.details[index] = {};
                    data.details[index][field] = value;
                }
            } else {
                data[key] = value;
            }
        });

        console.log('Sending data:', data);

        const orderId = formData.get('order_id');
        const method = 'POST';
        const url = orderId ? `${baseUrl}/orders/${orderId}/update` : `${baseUrl}/orders`;

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
                location.reload();
            } else {
                console.error('Server error:', result);
                let errorMessage = result.message || 'Có lỗi xảy ra khi lưu đơn hàng';
                if (result.errors) {
                    errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                }
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Lỗi khi lưu đơn hàng:', error);
            alert('Lỗi khi lưu đơn hàng: ' + error.message);
        }
    });

    // Edit order
    document.querySelectorAll('.edit-order').forEach(button => {
        button.addEventListener('click', async () => {
            const orderId = button.dataset.id;
            try {
                const response = await fetch(`${baseUrl}/orders/${orderId}`);
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response');
                }
                const order = await response.json();
                modalTitle.textContent = 'Chỉnh sửa đơn hàng';
                orderForm.querySelector('[name="order_id"]').value = order.order_id;
                orderForm.querySelector('[name="user_id"]').value = order.user_id;
                orderForm.querySelector('[name="status"]').value = order.status;
                orderForm.querySelector('[name="shipping_address"]').value = order.shipping_address;

                detailsContainer.innerHTML = '';
                order.details.forEach((detail, index) => {
                    const detailItem = document.createElement('div');
                    detailItem.className = 'detail-item mb-2 flex space-x-2';
                    detailItem.innerHTML = `
                        <input type="number" name="details[${index}][product_id]" value="${detail.product_id}" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                        <input type="number" name="details[${index}][quantity]" value="${detail.quantity}" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                        <input type="hidden" name="details[${index}][price]" value="${detail.price}">
                    `;
                    detailsContainer.appendChild(detailItem);
                });
                detailCount = order.details.length;
                updateSubmitButtonState();
                orderModal.classList.remove('hidden');
            } catch (error) {
                console.error('Lỗi khi tải dữ liệu đơn hàng:', error);
                alert('Có lỗi khi tải dữ liệu đơn hàng: ' + error.message);
            }
        });
    });

    // View order details
    document.querySelectorAll('.view-order').forEach(button => {
        button.addEventListener('click', async () => {
            const orderId = button.dataset.id;
            try {
                const response = await fetch(`${baseUrl}/orders/${orderId}`);
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response');
                }
                const order = await response.json();
                orderDetailsContent.innerHTML = `
                    <p><strong>Mã đơn hàng:</strong> ${order.order_id}</p>
                    <p><strong>Khách hàng:</strong> ${order.user.name}</p>
                    <p><strong>Ngày đặt:</strong> ${new Date(order.order_date).toLocaleString('vi-VN')}</p>
                    <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.total_amount)}</p>
                    <p><strong>Trạng thái:</strong> ${order.status}</p>
                    <p><strong>Địa chỉ giao:</strong> ${order.shipping_address || 'N/A'}</p>
                    <h4 class="font-semibold mt-4">Chi tiết sản phẩm:</h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${order.details.map(detail => `
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-800">${detail.product.product_name}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">${detail.quantity}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(detail.price)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                viewOrderModal.classList.remove('hidden');
            } catch (error) {
                console.error('Lỗi khi tải chi tiết đơn hàng:', error);
                alert('Có lỗi khi tải chi tiết đơn hàng: ' + error.message);
            }
        });
    });

    // Delete order
    document.querySelectorAll('.delete-order').forEach(button => {
        button.addEventListener('click', async () => {
            if (confirm('Bạn có chắc muốn xóa đơn hàng này?')) {
                const orderId = button.dataset.id;
                try {
                    const response = await fetch(`${baseUrl}/orders/${orderId}/delete`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response');
                    }
                    const result = await response.json();
                    if (response.ok) {
                        location.reload();
                    } else {
                        console.error('Server error:', result);
                        throw new Error(result.error || 'Có lỗi xảy ra khi xóa đơn hàng');
                    }
                } catch (error) {
                    console.error('Lỗi khi xóa đơn hàng:', error);
                    alert('Có lỗi khi xóa đơn hàng: ' + error.message);
                }
            }
        });
    });

    // Close modals
    closeModal.addEventListener('click', () => {
        orderModal.classList.add('hidden');
    });

    closeViewModal.addEventListener('click', () => {
        viewOrderModal.classList.add('hidden');
    });

    orderModal.addEventListener('click', (e) => {
        if (e.target === orderModal) {
            orderModal.classList.add('hidden');
        }
    });

    viewOrderModal.addEventListener('click', (e) => {
        if (e.target === viewOrderModal) {
            viewOrderModal.classList.add('hidden');
        }
    });

    // Initialize submit button state
    orderForm.addEventListener('input', updateSubmitButtonState);
</script>
@endsection
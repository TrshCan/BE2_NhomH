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
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Tìm theo mã đơn hàng hoặc khách hàng"
                           class="px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <button type="submit"
                            class="ml-2 bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <button id="openAddModal"
                        class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Thêm đơn hàng
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
            <table class="min-w-full divideocode-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn
                        hàng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách
                        hàng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày
                        đặt
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng
                        tiền
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                        thái
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ
                        giao
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành
                        động
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $order->order_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate"
                            title="{{ $order->user->name }}">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $order->order_date->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ number_format($order->total_amount, 0, ',', '.') }}
                            VND
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $order->status }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate"
                            title="{{ $order->shipping_address }}">
                            <span class="address-display"
                                  data-address="{{ $order->shipping_address }}">{{ $order->shipping_address }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button class="text-teal-500 hover:text-teal-600 mr-4 view-order"
                                    data-id="{{ $order->order_id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-blue-500 hover:text-blue-600 mr-4 edit-order"
                                    data-id="{{ $order->order_id }}"
                                    data-updated-at="{{ $order->updated_at->format('Y-m-d H:i:s') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-600 delete-order"
                                    data-id="{{ $order->order_id }}"
                                    data-updated-at="{{ $order->updated_at->toDateTimeString() }}">
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
            {{ $orders->appends(['search' => request('search')])->links() }}
        </div>
    </div>

    <!-- Modal for Add/Edit Order -->
    <div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm đơn hàng</h3>
            <form id="orderForm">
                <input type="hidden" name="order_id">
                <input type="hidden" name="updated_at">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">ID Khách hàng</label>
                    <input type="number" name="user_id"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                            required>
                        <option value="pending">Chờ xử lý</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="shipped">Đã giao</option>
                        <option value="delivered">Đã nhận</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng</label>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tỉnh/Thành phố</label>
                        <select name="province" id="province"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                required>
                            <option value="">Chọn tỉnh/thành phố</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Quận/Huyện</label>
                        <select name="district" id="district"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                disabled required>
                            <option value="">Chọn quận/huyện</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Phường/Xã</label>
                        <select name="ward" id="ward"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                disabled required>
                            <option value="">Chọn phường/xã</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Địa chỉ chi tiết</label>
                        <input type="text" name="house_address"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                               required>
                    </div>
                </div>
                <div id="orderDetails" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Chi tiết đơn hàng</label>
                    <div id="detailsContainer">
                        <div class="detail-item mb-2 flex space-x-2">
                            <input type="number" name="details[0][product_id]" placeholder="ID Sản phẩm"
                                   class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input"
                                   required>
                            <input type="number" name="details[0][quantity]" placeholder="Số lượng"
                                   class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                   required>
                        </div>
                    </div>
                    <button type="button" id="addDetail" class="text-teal-500 hover:text-teal-600 text-sm">+ Thêm sản
                        phẩm
                    </button>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                        Hủy
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200"
                            disabled>Lưu
                    </button>
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
                <button id="closeViewModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                    Đóng
                </button>
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
        let detailsContainer = document.getElementById('detailsContainer');
        const viewOrderModal = document.getElementById('viewOrderModal');
        const closeViewModal = document.getElementById('closeViewModal');
        const orderDetailsContent = document.getElementById('orderDetailsContent');
        const baseUrl = "{{ url('/') }}";

        let detailCount = 1;

        // Location data handling
        let locationData = [];
        document.addEventListener('DOMContentLoaded', async () => {
            await loadLocationData();
            document.querySelectorAll('.address-display').forEach(span => {
                const addressString = span.dataset.address;
                const formattedAddress = convertAddressCodesToNames(addressString);
                span.textContent = formattedAddress;
                span.parentElement.title = formattedAddress;
            });
        });

        async function loadLocationData() {
            try {
                const response = await fetch("{{ asset('assets/json/nested-divisions.json') }}");
                locationData = await response.json();
            } catch (error) {
                console.error("Lỗi khi tải dữ liệu tỉnh/thành:", error);
            }
        }

        function convertAddressCodesToNames(addressString) {
            if (!addressString || !locationData.length) return addressString;
            const [houseAddress, wardCode, districtCode, provinceCode] = addressString.split(', ').map(str => str.trim());
            let formattedAddress = houseAddress || '';
            const province = locationData.find(p => p.code == provinceCode);
            if (province) {
                formattedAddress += `, ${province.name}`;
                const district = province.districts?.find(d => d.code == districtCode);
                if (district) {
                    formattedAddress += `, ${district.name}`;
                    const ward = district.wards?.find(w => w.code == wardCode);
                    if (ward) {
                        formattedAddress += `, ${ward.name}`;
                    }
                }
            }
            return formattedAddress;
        }

        const provinceSelect = orderForm.querySelector('#province');
        const districtSelect = orderForm.querySelector('#district');
        const wardSelect = orderForm.querySelector('#ward');

        function populateProvinces(selectedCode = '') {
            provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
            locationData.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                if (province.code == selectedCode) option.selected = true;
                provinceSelect.appendChild(option);
            });
        }

        function populateDistricts(provinceCode, selectedCode = '') {
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            const selectedProvince = locationData.find(p => p.code == provinceCode);
            if (selectedProvince && selectedProvince.districts) {
                districtSelect.disabled = false;
                selectedProvince.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    if (district.code == selectedCode) option.selected = true;
                    districtSelect.appendChild(option);
                });
            }
        }

        function populateWards(districtCode, provinceCode, selectedCode = '') {
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;
            const selectedProvince = locationData.find(p => p.code == provinceCode);
            const selectedDistrict = selectedProvince?.districts.find(d => d.code == districtCode);
            if (selectedDistrict && selectedDistrict.wards) {
                wardSelect.disabled = false;
                selectedDistrict.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    if (ward.code == selectedCode) option.selected = true;
                    wardSelect.appendChild(option);
                });
            }
        }

        provinceSelect.addEventListener('change', function () {
            populateDistricts(this.value);
        });

        districtSelect.addEventListener('change', function () {
            populateWards(this.value, provinceSelect.value);
        });

        document.querySelectorAll('.edit-order').forEach(button => {
            button.addEventListener('click', async () => {
                const orderId = button.dataset.id;
                const updatedAt = button.dataset.updatedAt;

                try {
                    const response = await fetch(`${baseUrl}/orders/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Ensure CSRF token is included
                        },
                        body: JSON.stringify({updated_at: updatedAt}) // Send necessary data
                    });

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server returned non-JSON response');
                    }

                    const order = await response.json();
                    modalTitle.textContent = 'Chỉnh sửa đơn hàng';
                    orderForm.querySelector('[name="order_id"]').value = order.order.order_id;
                    orderForm.querySelector('[name="user_id"]').value = order.order.user_id;
                    orderForm.querySelector('[name="status"]').value = order.order.status;
                    orderForm.querySelector('[name="updated_at"]').value = updatedAt;

                    const [houseAddress, wardCode, districtCode, provinceCode] = (order.order.shipping_address || '').split(', ').map(str => str.trim());
                    orderForm.querySelector('[name="house_address"]').value = houseAddress || '';
                    await loadLocationData();
                    populateProvinces(provinceCode);
                    populateDistricts(provinceCode, districtCode);
                    populateWards(districtCode, provinceCode, wardCode);

                    ensureDetailsContainer();
                    detailsContainer.innerHTML = '';

                    if (!order.order.orderdetails || order.order.orderdetails.length == 0) {
                        console.warn('No order details found, initializing with one empty detail');
                        detailsContainer.innerHTML = `
                    <div class="detail-item mb-2 flex space-x-2">
                        <input type="number" name="details[0][product_id]" placeholder="ID Sản phẩm" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                        <input type="number" name="details[0][quantity]" placeholder="Số lượng" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    </div>
                `;
                        detailCount = 1;
                    } else {
                        order.order.orderdetails.forEach((detail, index) => {
                            const detailItem = document.createElement('div');
                            detailItem.className = 'detail-item mb-2 flex space-x-2';
                            detailItem.innerHTML = `
                        <input type="number" name="details[${index}][product_id]" value="${detail.product_id}" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                        <input type="number" name="details[${index}][quantity]" value="${detail.quantity}" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                        <input type="hidden" name="details[${index}][price]" value="${detail.price}">
                    `;
                            detailsContainer.appendChild(detailItem);
                        });
                        detailCount = order.order.orderdetails.length;
                    }

                    updateSubmitButtonState();
                    orderModal.classList.remove('hidden');

                } catch (error) {
                    console.error('Lỗi khi tải dữ liệu đơn hàng:', error);
                    alert('Có lỗi khi tải dữ liệu đơn hàng: ' + error.message);
                }
            });
        });


        loadLocationData();

        function updateSubmitButtonState() {
            const submitButton = orderForm.querySelector('button[type="submit"]');
            const priceInputs = detailsContainer.querySelectorAll('input[name*="details"][name$="[price]"]');
            const allPricesValid = Array.from(priceInputs).every(input => input.value && Number(input.value) >= 0);
            submitButton.disabled = !allPricesValid;
        }

        function ensureDetailsContainer() {
            if (!detailsContainer || !document.contains(detailsContainer)) {
                console.warn('detailsContainer is null or not in DOM, reinitializing');
                detailsContainer = document.getElementById('detailsContainer');
                if (!detailsContainer) {
                    console.error('detailsContainer not found, creating new one');
                    const orderDetails = document.getElementById('orderDetails');
                    const newContainer = document.createElement('div');
                    newContainer.id = 'detailsContainer';
                    orderDetails.insertBefore(newContainer, orderDetails.querySelector('#addDetail'));
                    detailsContainer = newContainer;
                }
            }
        }

        async function fetchProductPrice(productId, detailIndex) {
            try {
                await loadLocationData();
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
                ensureDetailsContainer();
                const hiddenPriceInput = detailsContainer.querySelector(`input[name="details[${detailIndex}][price]"]`);
                if (hiddenPriceInput) {
                    hiddenPriceInput.value = product.price;
                } else {
                    const newHiddenInput = document.createElement('input');
                    newHiddenInput.type = 'hidden';
                    newHiddenInput.name = `details[${detailIndex}][price]`;
                    newHiddenInput.value = product.price;
                    const detailItem = detailsContainer.querySelector(`.detail-item:nth-child(${parseInt(detailIndex) + 1})`);
                    if (detailItem) {
                        detailItem.appendChild(newHiddenInput);
                    } else {
                        console.error(`Detail item for index ${detailIndex} not found`);
                    }
                }
                updateSubmitButtonState();
            } catch (error) {
                console.error('Lỗi khi lấy giá sản phẩm:', error);
                alert(error.message);
                ensureDetailsContainer();
                const productInput = detailsContainer.querySelector(`input[name="details[${detailIndex}][product_id]"]`);
                if (productInput) {
                    productInput.value = '';
                    productInput.focus();
                }
                updateSubmitButtonState();
            }
        }

        openAddModal.addEventListener('click', async () => {
            modalTitle.textContent = 'Thêm đơn hàng';
            orderForm.reset();
            ensureDetailsContainer();
            detailsContainer.innerHTML = `
            <div class="detail-item mb-2 flex space-x-2">
                <input type="number" name="details[0][product_id]" placeholder="ID Sản phẩm" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                <input type="number" name="details[0][quantity]" placeholder="Số lượng" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
        `;
            detailCount = 1;
            await loadLocationData();
            populateProvinces();
            updateSubmitButtonState();
            orderModal.classList.remove('hidden');
        });

        addDetail.addEventListener('click', () => {
            ensureDetailsContainer();
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

        detailsContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('product-id-input')) {
                const productId = e.target.value;
                const detailIndex = e.target.name.match(/details\[(\d+)\]/)[1];
                if (productId) {
                    fetchProductPrice(productId, detailIndex);
                }
            }
        });

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

            data.shipping_address = [
                data.house_address,
                data.ward,
                data.district,
                data.province
            ].filter(Boolean).join(', ');

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
                } else if (response.status === 409) {
                    alert(result.message || 'Đơn hàng đã bị chỉnh sửa bởi admin khác. Vui lòng tải lại trang.');

                    console.log('Updated_at from server:', result.updated_at_server);
                    console.log('Updated_at from request:', result.updated_at_request);
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
                alert('Có lỗi khi lưu đơn hàng: ' + error.message);
            }
        });

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
                    const formattedAddress = convertAddressCodesToNames(order.order.shipping_address || '');
                    orderDetailsContent.innerHTML = `
                <p><strong>Mã đơn hàng:</strong> ${order.order.order_id}</p>
                <p><strong>Khách hàng:</strong> ${order.order.user?.name || 'N/A'}</p>
                <p><strong>Ngày đặt:</strong> ${new Date(order.order.order_date).toLocaleString('vi-VN')}</p>
                <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(order.order.total_amount)}</p>
                <p><strong>Trạng thái:</strong> ${order.order.status}</p>
                <p><strong>Địa chỉ giao:</strong> ${formattedAddress || 'N/A'}</p>
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
                        ${(order.order.orderdetails && order.order.orderdetails.length > 0) ? order.order.orderdetails.map(detail => `
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-800">${detail.product?.product_name || 'N/A'}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">${detail.quantity}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">${new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(detail.price)}</td>
                            </tr>
                        `).join('') : '<tr><td colspan="3" class="px-4 py-2 text-sm text-gray-800 text-center">Không có chi tiết sản phẩm</td></tr>'}
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

        document.querySelectorAll('.delete-order').forEach(button => {
            button.addEventListener('click', async () => {
                const orderId = button.dataset.id;
                const updatedAt = button.dataset.updatedAt;
                if (confirm('Bạn có chắc muốn xóa đơn hàng ' + orderId + '?')) {
                    try {
                        const response = await fetch(`${baseUrl}/orders/${orderId}/delete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({updated_at: updatedAt}),
                        });
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server returned non-JSON response');
                        }
                        const result = await response.json();
                        if (response.ok) {
                            location.reload();
                        } else if (response.status === 409) {
                            alert(result.message || 'Đơn hàng đã bị chỉnh sửa bởi admin khác. Vui lòng tải lại trang.');
                            location.reload();
                        } else {
                            console.error('Server error:', result);
                            throw new Error(result.message || 'Có lỗi xảy ra khi xóa đơn hàng');
                        }
                    } catch (error) {
                        console.error('Lỗi khi xóa đơn hàng:', error);
                        alert('Có lỗi khi xóa đơn hàng: ' + error.message);
                    }
                }
            });
        });

        closeModal.addEventListener('click', () => {
            orderModal.classList.add('hidden');
            resetModal();
        });

        closeViewModal.addEventListener('click', () => {
            viewOrderModal.classList.add('hidden');
        });

        orderModal.addEventListener('click', (e) => {
            if (e.target === orderModal) {
                orderModal.classList.add('hidden');
                resetModal();
            }
        });

        viewOrderModal.addEventListener('click', (e) => {
            if (e.target === viewOrderModal) {
                viewOrderModal.classList.add('hidden');
            }
        });

        function resetModal() {
            orderForm.reset();
            ensureDetailsContainer();
            detailsContainer.innerHTML = `
            <div class="detail-item mb-2 flex space-x-2">
                <input type="number" name="details[0][product_id]" placeholder="ID Sản phẩm" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 product-id-input" required>
                <input type="number" name="details[0][quantity]" placeholder="Số lượng" class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
        `;
            detailCount = 1;
            updateSubmitButtonState();
        }

        orderForm.addEventListener('input', updateSubmitButtonState);

        ensureDetailsContainer();
    </script>
@endsection

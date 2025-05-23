@extends('layouts.clients_home')
@section('title', 'Cài đặt tài khoản')

@section('content')
    <div class="container mt-5" style="margin-top: 100px;">
        <h2 class="mb-4">Cài đặt tài khoản</h2>


        <div class="tab-content pt-3" id="settingsTabContent">
            {{-- Thông tin tài khoản --}}
            <div class="tab-pane fade show active" id="account" role="tabpanel">
                <form method="POST" action="{{ route('user.profile.post.update', $user->id) }}" class="mb-3">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">

                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $user->email) }}" disabled>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    @if (!$user->google_id)
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    @if (!$user->google_id)
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address', $user->address) }}" required>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>

            {{-- Lịch sử đơn hàng --}}
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <div id="orderError" class="alert alert-danger" style="display: none;"></div> <!-- New error div -->
                @if ($orders->isEmpty())
                    <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Địa chỉ giao</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                Chờ xử lý
                                            @break

                                            @case('processing')
                                                Đang xử lý
                                            @break

                                            @case('shipped')
                                                Đã giao
                                            @break

                                            @case('delivered')
                                                Đã nhận
                                            @break

                                            @case('cancelled')
                                                Đã hủy
                                            @break

                                            @default
                                                {{ $order->status }}
                                        @endswitch
                                    </td>
                                    <td><span class="address-display"
                                            data-address="{{ $order->shipping_address }}">{{ $order->shipping_address }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-order" data-id="{{ $order->order_id }}"
                                            data-bs-toggle="modal" data-bs-target="#viewOrderModal">Xem chi tiết</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Đổi mật khẩu --}}
            <div class="tab-pane fade" id="password" role="tabpanel">
                <div class="alert alert-warning">Chức năng đổi mật khẩu hiện tại chưa được kích hoạt. Vui lòng liên hệ quản
                    trị viên.</div>
                <form action="#" method="POST" disabled>
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="current_password" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="new_password" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" name="new_password_confirmation" disabled>
                    </div>
                    <button type="submit" class="btn btn-warning" disabled>Đổi mật khẩu</button>
                </form>
            </div>
        </div>

        <!-- Modal for View Order Details -->
        <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewOrderModalLabel">Chi tiết đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="orderDetailsContent" class="space-y-4">
                            <!-- Order details will be populated via JavaScript -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const viewOrderModal = document.getElementById('viewOrderModal');
        const orderDetailsContent = document.getElementById('orderDetailsContent');
        const orderErrorDiv = document.getElementById('orderError');
        const baseUrl = "{{ url('/') }}";
        let locationData = [];

        // Load location data and format addresses
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch("{{ asset('assets/json/nested-divisions.json') }}");
                locationData = await response.json();
                document.querySelectorAll('.address-display').forEach(span => {
                    const addressString = span.dataset.address;
                    const formattedAddress = convertAddressCodesToNames(addressString);
                    span.textContent = formattedAddress;
                });
            } catch (error) {
                console.error("Lỗi khi tải dữ liệu tỉnh/thành:", error);
                orderErrorDiv.style.display = 'block';
                orderErrorDiv.textContent = 'Có lỗi khi tải dữ liệu tỉnh/thành. Vui lòng thử lại sau.';
            }
        });

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
                    if (!order.success) {
                        orderErrorDiv.style.display = 'block';
                        orderErrorDiv.textContent =
                            'Có lỗi khi tải chi tiết đơn hàng: Đơn hàng không tồn tại hoặc đã bị xóa. Vui lòng tải lại trang.';
                        setTimeout(() => {
                            orderErrorDiv.style.display = 'none';
                        }, 5000);
                        return;
                    }
                    const formattedAddress = convertAddressCodesToNames(order.shipping_address || '');
                    orderDetailsContent.innerHTML = `
                    <p><strong>Mã đơn hàng:</strong> ${order.order_id}</p>
                    <p><strong>Khách hàng:</strong> ${order.user?.name || 'N/A'}</p>
                    <p><strong>Ngày đặt:</strong> ${new Date(order.order_date).toLocaleString('vi-VN')}</p>
                    <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.total_amount)}</p>
                    <p><strong>Trạng thái:</strong> ${
                        order.status === 'pending' ? 'Chờ xử lý' :
                        order.status === 'processing' ? 'Đang xử lý' :
                        order.status === 'shipped' ? 'Đã giao' :
                        order.status === 'delivered' ? 'Đã nhận' :
                        order.status === 'cancelled' ? 'Đã hủy' : order.status
                    }</p>
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
                            ${(order.details && order.details.length > 0) ? order.details.map(detail => `
                                                                                <tr>
                                                                                    <td class="px-4 py-2 text-sm text-gray-800">${detail.product?.product_name || 'N/A'}</td>
                                                                                    <td class="px-4 py-2 text-sm text-gray-800">${detail.quantity}</td>
                                                                                    <td class="px-4 py-2 text-sm text-gray-800">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(detail.price)}</td>
                                                                                </tr>
                                                                            `).join('') : '<tr><td colspan="3" class="px-4 py-2 text-sm text-gray-800 text-center">Không có chi tiết sản phẩm</td></tr>'}
                        </tbody>
                    </table>
                `;
                    orderErrorDiv.style.display = 'none'; // Hide error div if successful
                } catch (error) {
                    console.error('Lỗi khi tải chi tiết đơn hàng:', error);
                    orderErrorDiv.style.display = 'block';
                    orderErrorDiv.textContent =
                        'Có lỗi khi tải chi tiết đơn hàng: Đơn hàng không tồn tại hoặc đã bị xóa. Vui lòng tải lại trang.';
                    // Close the modal if it's open
                    const modal = bootstrap.Modal.getInstance(viewOrderModal);
                    if (modal) modal.hide();
                    setTimeout(() => {
                        orderErrorDiv.style.display = 'none';
                    }, 5000); // Hide after 5 seconds
                }
            });
        });
    </script>
@endsection

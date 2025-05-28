@extends('layouts.clients_home')

@section('content')
<main class="cart-page container mt-5 my-5">
    <h1 class="text-center mb-4">Giỏ Hàng</h1>

    @php $total = 0; @endphp

    <div id="cart-messages">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if (session('info'))
        <div class="alert alert-info" id="info-message" data-message="{{ session('info') }}">
            {{ session('info') }}
        </div>
        @endif
    </div>

    @if ($cart && $cart->items->count())
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-warning text-dark">
                <tr>
                    <th scope="col">Hình Ảnh</th>
                    <th scope="col">Sản Phẩm</th>
                    <th scope="col">Giá</th>
                    <th scope="col">Số Lượng</th>
                    <th scope="col">Tổng</th>
                    <th scope="col">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart->items as $item)
                @php
                $product = $item->product;
                $subtotal = $product->price * $item->quantity;
                $total += $subtotal;
                @endphp
                <tr>
                    <td>
                        <img src="{{ asset('assets/images/' . $product->image_url) }}"
                            alt="{{ $product->product_name }}" class="img-thumbnail"
                            style="width: 80px; height: 80px;">
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ number_format($product->price) }}đ</td>
                    <td>
                        <input type="number" class="form-control w-50 quantity-input"
                            value="{{ $item->quantity }}" min="1"
                            data-product-id="{{ $product->product_id }}"
                            data-updated-at="{{ $item->updated_at->toDateTimeString() }}"
                            onchange="updateQuantity(this)">
                    </td>
                    <td>{{ number_format($subtotal) }}đ</td>
                    <td>
                        <form action="{{ url('cart/delete/' . $product->product_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                    <td class="fw-bold">{{ number_format($total) }}đ</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-end gap-3">
        <form action="{{ url('cart/deleteall') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng không?')">
            @csrf
            <button type="submit" class="btn btn-warning">Xóa Tất Cả</button>
        </form>

        <a href="{{ route('checkout.show') }}" class="btn btn-primary checkout-btn">Thanh Toán</a>
    </div>
    @else
    <div class="alert alert-info text-center" role="alert">
        Giỏ hàng của bạn đang trống. <a href="{{ url('/') }}" class="alert-link">Tiếp tục mua sắm!</a>
    </div>
    @endif
</main>

<script>
    // Show pop-up alert for session info message
    document.addEventListener('DOMContentLoaded', function() {
        const infoMessage = document.getElementById('info-message');
        if (infoMessage) {
            const message = infoMessage.getAttribute('data-message');
            if (message) {
                alert(message);
            }
        }
    });

    function updateQuantity(input) {
        const productId = input.dataset.productId;
        let updatedAt = input.dataset.updatedAt;
        const qty = input.value;
        const messageContainer = document.getElementById('cart-messages');

        fetch(`/cart/update_quantity/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qty: qty,
                    updated_at: updatedAt
                })
            })
            .then(response => {
                return response.json().then(data => ({
                    status: response.status,
                    data: data
                }));
            })
            .then(({
                status,
                data
            }) => {
                messageContainer.innerHTML = ''; // Clear previous messages
                const alert = document.createElement('div');
                alert.className = `alert alert-${data.status === 'success' ? 'success' : 'danger'}`;

                // Use the server’s message if available, otherwise fallback to a generic message
                alert.textContent = data.message || (status === 409 ?
                    'Số lượng sản phẩm đã bị thay đổi bởi phiên làm việc khác. Vui lòng tải lại trang.' :
                    'Cập nhật không thành công.');

                // Update the data-updated-at attribute on conflict
                if (status === 409 && data.latest_updated_at) {
                    input.dataset.updatedAt = data.latest_updated_at;
                }

                messageContainer.appendChild(alert);

                // Scroll to top to show message
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                // Reload after a delay to show the message
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch(error => {
                messageContainer.innerHTML = '';
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.textContent = 'Có lỗi xảy ra khi cập nhật giỏ hàng: ' + error.message;
                messageContainer.appendChild(alert);
                console.error(error);
                setTimeout(() => window.location.reload(), 2000);
            });
    }
</script>
@endsection
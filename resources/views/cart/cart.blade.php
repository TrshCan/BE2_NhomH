@extends('layouts.clients_home');

@section('content')
<main class="cart-page container mt-5 my-5">
    <h1 class="text-center mb-4">Giỏ Hàng</h1>
    @if (empty(session('cart')))
    <div class="alert alert-info text-center" role="alert">
        Giỏ hàng của bạn đang trống. <a href="{{ url('/') }}" class="alert-link">Tiếp tục mua sắm!</a>
    </div>
    @else
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
                @foreach (session('cart') as $id => $items)
                    @php
                        $item = $products->getProductById_1item($id);
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ asset('assets/images/' . $item['image_url']) }}" alt="Product Image"
                                class="img-thumbnail" style="width: 80px; height: 80px;">
                        </td>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ number_format($item['price']) . 'đ' }}</td>
                        <td>
                            <input type="number" class="form-control w-25 mx-auto quantity-input"
                                value="{{ $items['quantity'] }}" min="1" data-id="{{ $id }}"
                                onchange="if(this.value < 1){alert('Số lượng phải lớn hơn hoặc bằng 1!');this.value=1;}else{window.location.href='{{ url(`cart/update_quantity`) }}/' + this.getAttribute('data-id') + '/' + this.value;}">
                        </td>
                        <td>{{ number_format($item['price'] * $items['quantity']) . 'đ' }}</td>
                        <td>
                            <a href="#" onclick="confirmDelete('{{ $id }}')"
                                class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                    <td class="fw-bold">{{ number_format($total, 0) . 'đ' }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="d-flex justify-content-end gap-3">
        <a href="#" onclick="confirmDeleteAll()" class="btn btn-warning">Xóa Tất Cả</a>
        <button onclick="checkLoginStatus()" class="btn btn-primary checkout-btn">Thanh Toán</button>
    </div>
    @endif
</main>

<!-- Modal for login prompt -->
<div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginPromptModalLabel">Yêu cầu đăng nhập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn cần đăng nhập để tiếp tục thanh toán. Vui lòng đăng nhập hoặc đăng ký tài khoản.
            </div>
            <div class="modal-footer">
                <a href="{{ url('login') }}" class="btn btn-primary">Đăng Nhập</a>
                <a href="{{ url('register') }}" class="btn btn-secondary">Đăng Ký</a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?")) {
        window.location.href = "{{ url('cart/delete') }}/" + id;
    }
}

function confirmDeleteAll() {
    if (confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng không?")) {
        window.location.href = "{{ url('cart/deleteall') }}";
    }
}

function checkLoginStatus() {
    if (session('user_id') && session('user_id') != 0)
        window.location.href = "{{ url('checkout') }}";
    else
        $('#loginPromptModal').modal('show');
}
</script>
@endsection
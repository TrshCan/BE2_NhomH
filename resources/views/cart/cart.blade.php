@extends('layouts.clients_home')

@section('content')
<main class="cart-page container mt-5 my-5">
    <h1 class="text-center mb-4">Giỏ Hàng</h1>

    @php $total = 0; @endphp

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
                            onchange="window.location.href = '/cart/update_quantity/{{ $product->product_id }}/' + this.value">

                    </td>
                    <td>{{ number_format($subtotal) }}đ</td>
                    <td>
                        <a href="{{ url('cart/delete/' . $product->product_id) }}"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')">Xóa</a>

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
        <a href="{{ url('cart/deleteall') }}"
            class="btn btn-warning"
            onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng không?')">Xóa Tất Cả</a>
        <button class="btn btn-primary checkout-btn">Thanh Toán</button>
    </div>
    @else
    <div class="alert alert-info text-center" role="alert">
        Giỏ hàng của bạn đang trống. <a href="{{ url('/') }}" class="alert-link">Tiếp tục mua sắm!</a>
    </div>
    @endif
</main>

@endsection
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Đặt Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container">
        <h2 class="mb-4 text-center">HÓA ĐƠN ĐẶT HÀNG</h2>
        <p><strong>Mã đơn hàng:</strong> {{ $order->order_id }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Khách hàng:</strong> {{ $order->user->full_name }}</p>
        <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
        <hr>
        <h5>Chi Tiết Sản Phẩm:</h5>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->product_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->price) }}đ</td>
                    <td>{{ number_format($detail->quantity * $detail->price) }}đ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-end fw-bold">Tổng cộng: {{ number_format($order->total_amount) }}đ</div>
        <div class="mt-4 text-center">
            <a href="{{ route('download_bill_pdf', ['order_id' => $order->order_id]) }}" class="btn btn-primary">Tải PDF</a>
            <a href="{{ route('download_bill_image', ['order_id' => $order->order_id]) }}" class="btn btn-success">Tải Ảnh</a>
        </div>
    </div>
</body>
</html>

@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2>Chi tiết người dùng</h2>
                <ul>
                    <li><strong>ID:</strong> {{ $user->id }}</li>
                    <li><strong>Họ tên:</strong> {{ $user->name }}</li>
                    <li><strong>Email:</strong> {{ $user->email }}</li>
                    <li><strong>Số điện thoại:</strong> {{ $user->phone }}</li>
                    <li><strong>Địa chỉ:</strong> {{ $user->address }}</li>
                    <li><strong>Vai trò:</strong> {{ $user->role }}</li>
                    <li><strong>Trạng thái:</strong> {{  $user->status->name }}</li>
                </ul>
                <a href="{{ route('user.list') }}" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
            <div class="col-md-4">
                <img src="https://i.ibb.co/SXd1FDnZ/t-o-cho-t-i-h-nh-nh-logo-Group-H.png" alt="Hình ảnh trang trí" class="img-fluid">
            </div>
        </div>
    </div>
@endsection
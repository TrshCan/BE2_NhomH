@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Đăng Ký</h5>
                    
                
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                   
                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ Và Tên</label>
                            <input type="text" class="form-control" id="fullname" name="name"
                                value="{{ old('name') }}" placeholder="Nhập Họ Và Tên" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" placeholder="Nhập Email..." required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật Khẩu</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận Mật Khẩu</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" name="phone"
                                value="{{ old('phone') }}" placeholder="Nhập SĐT..." required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address') }}" placeholder="Nhập Địa Chỉ..." required>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary w-100">Đăng Ký</button>
                        <a href="{{ route('login') }}">Quay trở lại đăng nhập</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
=======

    @if (session('success'))
        <div class="alert alert-success mt-3 text-center">
            {{ session('success') }}
        </div>
    @endif
@endsection

@endsection

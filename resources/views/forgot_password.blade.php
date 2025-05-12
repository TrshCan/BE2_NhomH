@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Quên Mật Khẩu</h5>
                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{route('password.forgot.post')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                value="{{ old('email') }}" placeholder="Nhập Email..." required>
                            @error('email')
                            <div class="invalid-feedback">{{ __('Email không hợp lệ') }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Gửi Link Đặt Lại Mật Khẩu</button>
                        <a href="{{ route('login') }}">Quay trở lại đăng nhập</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

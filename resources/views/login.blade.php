@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center mb-4">{{ __('Login') }}</h5>
            @if (session('error_admin'))
            <div class="alert alert-danger">
                {{ session('error_admin') }}
            </div>
            @endif
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
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" placeholder="{{ __('Nhập email') }}" required
                        autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __('Email không hợp lệ') }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Mật khẩu') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="{{ __('Nhập mật khẩu') }}" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ __('Mật khẩu không hợp lệ') }}</strong>
                    </span>
                    @enderror
                </div>
                @if (isset($error))
                <div class="alert alert-danger">
                    {{ __('Sai email hoặc mật khẩu') }}
                </div>
                @endif
                <button type="submit" class="btn btn-primary w-100">{{ __('Login') }}</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{route('password.forgot')}}" class="text-decoration-none">{{ __('Quên mật khẩu?') }}</a><br>
                <a href="{{route('register')}}" class="text-decoration-none">{{ __('Chưa có tài khoản? Đăng kí') }}</a>
            </div>

            <div class="mt-4">
                <a href="{{route('social.login','google')}}" class="btn btn-danger w-100 mb-2">
                    <i class="material-icons align-middle">account_circle</i> {{ __('Login với Google') }}
                </a>
            </div>
            @if (session('login_success'))
            <div class="alert alert-success mt-3">
                {{ session('login_success') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center mb-4">{{ __('Login') }}</h5>
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" placeholder="{{ __('Enter email') }}" required
                        autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="{{ __('Enter password') }}" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @if (isset($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endif
                <button type="submit" class="btn btn-primary w-100">{{ __('Login') }}</button>
            </form>
            <div class="mt-3 text-center">
                <a href="" class="text-decoration-none">{{ __('Forgot Your Password?') }}</a><br>
                <a href="{{route('register')}}" class="text-decoration-none">{{ __('Chưa Có Tài Khoản ? Register') }}</a>
            </div>

            <div class="mt-4">
                <a href="{{route('social.login','google')}}" class="btn btn-danger w-100 mb-2">
                    <i class="material-icons align-middle">account_circle</i> {{ __('Login with Google') }}
                </a>
                <a href="{{route('social.login','github')}}" class="btn btn-dark w-100">
                    <i class="fab fa-github align-middle"></i> {{ __('Login with GitHub') }}
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


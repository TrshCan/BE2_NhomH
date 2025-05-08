@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Trang chủ</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Chào mừng bạn đến với trang web của chúng tôi!</p>

                    <p>Hiện tại, chúng tôi có các chức năng sau:</p>

                    <ul>
                        <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

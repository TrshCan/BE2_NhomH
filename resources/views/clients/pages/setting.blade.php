
@extends('layouts.clients_home');
@section('title', 'Cài đặt tài khoản')

@section('content')
<div class="container mt-5" style="margin-top: 100px;">
    <h2 class="mb-4">Cài đặt tài khoản</h2>

    <ul class="nav nav-tabs" id="settingsTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="account-tab" data-bs-toggle="tab" href="#account" role="tab">Thông tin tài khoản</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">Lịch sử đơn hàng</a>
        </li>

    </ul>

    <div class="tab-content pt-3" id="settingsTabContent">
        {{-- Thông tin tài khoản --}}
        <div class="tab-pane fade show active" id="account" role="tabpanel">
            <form method="POST" action="{{ route('user.profile.post.update', $user->id) }}" class="mb-3">
            @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">

                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" disabled>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @if(!$user->google_id)
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                @if(!$user->google_id)
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}" required>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>

        {{-- Lịch sử đơn hàng --}}
        <div class="tab-pane fade" id="orders" role="tabpanel">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>

                        <tr>
                            <td></td>
                            <td></td>
                            <td> VND</td>
                            <td></td>
                        </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


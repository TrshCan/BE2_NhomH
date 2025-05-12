@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản lý người dùng</h2>


    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <form method="GET" action="{{ url('list') }}" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2 w-25" placeholder="Tìm theo tên hoặc email..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Tìm</button>
    </form>


    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Quyền hạn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->status)
                    <span class="badge {{ $user->status->name === 'Đã khóa' ? 'bg-danger' : 'bg-success' }}">
                        {{ $user->status->name }}
                    </span>
                    @else
                    <span class="badge bg-warning">Không xác định</span>
                    @endif
                </td>
                <td>{{ $user->role ?? 'user' }}</td>
                <td>
                    <a href="{{ url('update/' . $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ url('delete') }}" method="POST" style="display:inline-block;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <button onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Không tìm thấy người dùng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>


    <div class="d-flex justify-content-center">
        {{ $users->appends(request()->input())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
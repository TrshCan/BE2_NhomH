@extends('layouts.admin')
@section('content')
    <div class="container">
        <h2 class="mb-4">Quản lý người dùng</h2>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.indexUser') }}" class="mb-3 d-flex flex-column flex-md-row gap-2">
            <input type="text" name="search" class="form-control w-100 w-md-25" placeholder="Tìm theo tên hoặc email..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary w-30 w-md-auto">Tìm</button>
        </form>

        <!-- Responsive Table -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                <tr>
                    <th class="d-none d-lg-table-cell">ID</th>
                    <th>Họ và Tên</th>
                    <th class="d-none d-md-table-cell">Email</th>
                    <th>Trạng thái</th>
                    <th class="d-none d-lg-table-cell">Quyền hạn</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="d-none d-lg-table-cell">{{ $user->id }}</td>
                        <td><a href="{{ route('admin.showUser', $user->id) }}">{{ $user->name }}</a></td>
                        <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                        <td>
                            @if($user->status)
                                <span class="badge {{ $user->status->name === 'Đã khóa' ? 'bg-danger' : 'bg-success' }}">
                            {{ $user->status->name }}
                        </span>
                            @else
                                <span class="badge bg-warning">Không xác định</span>
                            @endif
                        </td>
                        <td class="d-none d-lg-table-cell">{{ $user->role ?? 'user' }}</td>
                        <td>
                            <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                <a href="{{ route('admin.postUpdateUser', $user->id) }}" class="btn btn-warning btn-sm w-100">Sửa</a>
                                <form action="{{ route('admin.deleteUser') }}" method="POST" class="d-inline-block w-100">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <button onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm w-100">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Không tìm thấy người dùng nào.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Custom CSS for additional responsiveness -->
    <style>
        @media (max-width: 767px) {
            h2 {
                font-size: 1.5rem;
            }
            .table {
                font-size: 0.875rem;
            }
            .badge {
                font-size: 0.75rem;
            }
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endsection

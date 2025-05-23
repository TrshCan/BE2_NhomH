@extends('layouts.clients_home')

@section('title', 'Blog')

@section('content')
    @foreach ($posts as $item)
        <div class="container py-5">
            <h1 class="display-4 mb-4">{{ $item->title }}</h1>
            <div class="mb-3">
                <small class="text-muted">
                    Đăng ngày: {{ $item->published_at }} | Tác giả: {{ $item->author }}
                </small>
            </div>
            @if ($item->image_url)
                <img src=" {{ asset('assets/images/' . $item->image_url) }}" class="img-fluid rounded mb-4"
                    alt="{{ $item->title }}">
            @endif
            <div class="content">
                {!! nl2br(e($item->content)) !!}
            </div>
            <a href="{{ route('products.home') }}" class="btn btn-primary mt-4">Quay lại Home</a>
        </div>
    @endforeach
@endsection

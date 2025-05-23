@extends('layouts.client')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Chi tiết bài viết</h1>
            <a href="{{ route('blog.index') }}"
                class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center"
                aria-label="Quay lại danh sách bài viết">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        <!-- Blog Details -->
        <div class="bg-white rounded-xl shadow-md p-6 animate-slide-in">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $item->title }}</h2>

            <!-- Blog Image -->
            @if ($item->image_url)
                <img src="{{ asset('assets/images/' . $item->image_url) }}" alt="{{ $item->title }}"
                    class="w-full h-64 object-cover rounded-lg mb-6">
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg mb-6 flex items-center justify-center">
                    <span class="text-gray-500">Không có hình ảnh</span>
                </div>
            @endif

            <!-- Blog Meta -->
            <div class="flex items-center text-sm text-gray-600 mb-4">
                <span class="mr-4">
                    <i class="fas fa-user mr-1"></i> {{ $item->author }}
                </span>
                <span>
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ $item->published_at ? $item->published_at->format('d/m/Y H:i') : 'Chưa đăng' }}
                </span>
            </div>

            <!-- Blog Content -->
            <div class="prose max-w-none text-gray-800 mb-6">
                {!! nl2br(e($item->content)) !!}
            </div>
        </div>
    </div>
@endsection

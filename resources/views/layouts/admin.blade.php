<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpokl5x2f2VjyN6z5z5z5z5z5z5z5z5z5z5z5z5z5z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('styles')
    <style>
        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .content-full {
            margin-left: 0 !important;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .pagination {
            justify-content: center;
        }

        .page-item {
            margin: 0 5px;
        }

        .page-link {
            color: #2dd4bf;
            /* Teal color to match your theme */
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: #2dd4bf;
            color: #fff;
            text-decoration: none;
        }

        .page-item.active .page-link {
            background-color: #2dd4bf;
            color: #fff;
            border-color: #2dd4bf;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-gray-100 font-['Inter'] antialiased">
    <!-- Top Navbar -->
    <nav class="bg-white fixed top-0 left-0 right-0 z-20 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <button id="toggleSidebar" class="text-gray-600 hover:text-teal-500 focus:outline-none">
                        <i id="toggleIcon" class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="ml-4 text-xl font-semibold text-gray-800">Admin Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- <div class="relative">
                        <input type="text" placeholder="Tìm kiếm..." class="bg-gray-100 text-gray-800 placeholder-gray-500 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-teal-500 transition duration-200 w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    </div> -->
                    <div class="relative">
                        <button class="flex items-center text-gray-600 hover:text-red-500 focus:outline-none">
                            <i class="fas fa-user-circle text-xl mr-2"></i>
                            @if (Auth::check())
                                <span>Chúc Ông Chủ May Mắn {{ Auth::user()->name }}</span>
                            @endif
                        </button>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-16">
        <!-- Sidebar -->
        <div id="sidebar"
            class="bg-gradient-to-b from-indigo-900 to-indigo-800 text-white w-64 space-y-6 py-7 px-4 fixed h-full transition-transform duration-300 z-10 glass-effect">
            <h2 class="text-2xl font-bold text-center mb-6">Control Panel</h2>
            <nav>
                <a href="{{ route('admin.indexUser') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"
                    style="text-decoration: none">
                    <i class="fas fa-users mr-3"></i> Quản lý người dùng
                </a>
                <a href="{{ route('admin.products') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"
                    style="text-decoration: none">
                    <i class="fas fa-box mr-3"></i> Quản lý Sản phẩm
                </a>
                <a href="{{ route('admin.images.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-image mr-3"></i> Quản lý ảnh
                </a>
                <a href="{{ route('admin.brands') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-copyright mr-3"></i>Quản lý Brands
                </a>
                <a href="{{ route('admin.categories') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-handshake mr-3"></i> Quản lý danh mục
                </a>
                <a href="{{ route('admin.deals.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-blog mr-3"></i> Quản lý Deal
                </a>
                <a href="{{ route('admin.blogs.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-box mr-3"></i> Quản lý Blogs
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500  transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-shopping-cart mr-3"></i> Đơn hàng
                </a>
                <a href="{{ route('admin.coupons.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-tags mr-3 mr-3"></i> Mã giảm giá
                </a>
                <a href="{{ route('admin.statistical') }}"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-chart-bar mr-3"></i> Báo cáo
                </a>
                <a href="#"
                    class="block py-2.5 px-4 rounded hover:bg-yellow-500 transition duration-200 flex items-center transform hover:scale-105"style="text-decoration: none">
                    <i class="fas fa-cog mr-3"></i> Cài đặt
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="content" class="flex-1 ml-64 p-10 transition-all duration-300">
            <div class="bg-white rounded-xl shadow-md p-8 animate-slide-in">

                @yield('content')
            </div>
        </div>
    </div>
    @yield('scripts')
    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
            content.classList.toggle('content-full');
            toggleIcon.classList.toggle('fa-bars');
            toggleIcon.classList.toggle('fa-times');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>

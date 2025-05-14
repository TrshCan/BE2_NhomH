# Shop Bán Hàng

Một trang web thương mại điện tử đơn giản được xây dựng với Laravel.

## Cách Sử Dụng

1. Sao chép kho lưu trữ vào máy tính của bạn: `git clone https://your-project.git`
2. Chuyển vào thư mục dự án: `cd BE2_NhomH`
3. Chuyển sang nhánh `Login`: `git checkout Login`
4. Cài đặt các phụ thuộc của dự án: `composer install`
5. Cài đặt Socialite: `composer require laravel/socialite`
6. Chạy di trú cơ sở dữ liệu: `php artisan migrate`
7. Khởi động máy chủ phát triển: `php artisan serve`



# BE2_NhomH

# WebBanHang - E-commerce Platform

## Project Structure
```
.
└── WebBanHang/
    ├── database/
    │   └── db.sql
    ├── public/
    │   ├── includes (use for handling php)/
    │   │   ├── config.php
    │   │   ├── Product_Database.php
    │   │   └── User_Database.php
    │   ├── pages/
    │   │   ├── cart.php
    │   │   ├── detail.php
    │   │   ├── login.php
    │   │   └── signup.php
    │   ├── assets/
    │   │   ├── css
    │   │   ├── js
    │   │   └── images
    │   └── admin/
    │       ├── dashboard.php
    │       ├── user_management.php
    │       └── user_crud.php
    ├── index.php (main menu)
    ├── .gitignore
    ├── LICENSE
    └── README.md
```

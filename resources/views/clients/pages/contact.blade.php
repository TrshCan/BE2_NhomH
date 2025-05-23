
@extends('layouts.clients_home')
@section('title', 'Contact')
@section('content')
    <!-- Thông báo Flash -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Phần Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.home') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Liên Hệ</li>
                </ol>
            </nav>
        </div>
    </section>

   

    <!-- Phần Liên Hệ -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="section-title">Liên Hệ Với Chúng Tôi</h2>
                    <p class="section-description">
                        Có nhiều cách để liên hệ với chúng tôi. Bạn có thể gửi email, gọi điện hoặc để lại lời nhắn, hãy chọn cách phù hợp nhất với bạn.
                    </p>
                    <div class="contact-info">
                        <p><i class="fas fa-phone"></i> (800) 686-6688</p>
                        <p><i class="fas fa-envelope"></i> <a href="mailto:info@techgear.com">info@techgear.com</a></p>
                        <p><i class="fas fa-map-marker-alt"></i> 123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh</p>
                        <p><i class="fas fa-clock"></i> Giờ làm việc: 8:00 - 18:00, Thứ Hai - Thứ Sáu</p>
                        <p><i class="fas fa-clock"></i> Chủ Nhật: Nghỉ</p>
                    </div>
                    <div class="social-links">
                        <h3>Theo Dõi Chúng Tôi</h3>
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="section-title">Kết Nối Với Chúng Tôi!</h2>
                    <p>Điền vào biểu mẫu dưới đây để nhận phản hồi miễn phí và bảo mật.</p>
                    <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Họ và Tên" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Địa Chỉ Email" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" class="form-control" placeholder="Chủ Đề" required>
                            @error('subject')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <textarea name="message" class="form-control" placeholder="Lời Nhắn" rows="5" required></textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi Lời Nhắn</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    
   
@endsection

@section('scripts')
    <script>
        // Phản hồi khi gửi biểu mẫu
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.textContent = 'Đang Xử Lý...';
                });
            });
        });

        // Khởi tạo Google Map
        function initMap() {
            const mapOptions = {
                center: { lat: 10.7769, lng: 106.7009 }, // Tọa độ TP. Hồ Chí Minh
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            const map = new google.maps.Map(document.getElementById('map'), mapOptions);

            // Thêm marker tại tọa độ
            new google.maps.Marker({
                position: { lat: 10.7769, lng: 106.7009 },
                map: map,
                title: 'TechGear - TP. Hồ Chí Minh'
            });
        }

        // Kiểm tra lỗi tải Google Maps
        window.addEventListener('error', function (e) {
            if (e.target.src && e.target.src.includes('maps.googleapis.com')) {
                console.error('Lỗi khi tải Google Maps API. Kiểm tra API Key hoặc kết nối mạng.');
            }
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
@endsection

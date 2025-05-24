<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            // Cảnh báo lừa đảo (10 entries)
            [
                'subtle' => 'Cảnh báo lừa đảo',
                'title' => 'Làm thế nào để nhận biết một giao dịch là lừa đảo?',
                'content' => 'Hãy cảnh giác với các dấu hiệu như: giá quá rẻ so với thị trường, yêu cầu chuyển tiền trước khi nhận hàng, thông tin liên hệ không rõ ràng.'
            ],
            [
                'subtle' => 'Cảnh báo lừa đảo',
                'title' => 'Tôi đã bị lừa. Phải làm gì?',
                'content' => 'Ngay lập tức báo cáo với chúng tôi, cung cấp đầy đủ bằng chứng và thông tin về vụ việc. Chúng tôi sẽ hỗ trợ bạn điều tra và giải quyết.'
            ],
            // More entries for each category...
            
            // Trả hàng (10 entries)
            [
                'subtle' => 'Trả hàng',
                'title' => 'Điều kiện để trả hàng là gì?',
                'content' => 'Sản phẩm phải nguyên vẹn, chưa qua sử dụng, còn nguyên tem nhãn và trong thời gian cho phép trả hàng (thường là 7-14 ngày).'
            ],
            [
                'subtle' => 'Trả hàng',
                'title' => 'Làm thế nào để trả hàng?',
                'content' => 'Truy cập mục "Đơn hàng của tôi", chọn sản phẩm muốn trả, điền lý do và làm theo hướng dẫn.'
            ],
            
            // Hoàn tiền (8 entries)
            [
                'subtle' => 'Hoàn tiền',
                'title' => 'Quy trình hoàn tiền như thế nào?',
                'content' => 'Sau khi yêu cầu được chấp nhận, chúng tôi sẽ xử lý hoàn tiền trong vòng 3-5 ngày làm việc vào phương thức thanh toán gốc.'
            ],
            [
                'subtle' => 'Hoàn tiền',
                'title' => 'Tôi chưa nhận được tiền hoàn',
                'content' => 'Kiểm tra lại tài khoản sau 5-7 ngày. Nếu vẫn chưa nhận được, vui lòng liên hệ bộ phận hỗ trợ.'
            ],
            
            // Lỗi (7 entries)
            [
                'subtle' => 'Lỗi',
                'title' => 'Tôi gặp lỗi khi thanh toán',
                'content' => 'Kiểm tra kết nối internet, thử lại sau vài phút. Nếu vẫn không được, hãy liên hệ bộ phận hỗ trợ kỹ thuật.'
            ],
            [
                'subtle' => 'Lỗi',
                'title' => 'Không thể đăng nhập',
                'content' => 'Kiểm tra tên đăng nhập và mật khẩu. Sử dụng chức năng "Quên mật khẩu" nếu cần.'
            ],
            
            // Thành viên mới (8 entries)
            [
                'subtle' => 'Thành viên mới',
                'title' => 'Làm sao để đăng ký tài khoản?',
                'content' => 'Truy cập trang chủ, nhấp vào nút "Đăng ký", điền đầy đủ thông tin cá nhân và làm theo hướng dẫn.'
            ],
            [
                'subtle' => 'Thành viên mới',
                'title' => 'Ưu đãi dành cho thành viên mới',
                'content' => 'Thành viên mới sẽ được giảm 10% cho đơn hàng đầu tiên và nhận mã ưu đãi đặc biệt.'
            ],
            
            // Hủy đơn (7 entries)
            [
                'subtle' => 'Hủy đơn',
                'title' => 'Tôi muốn hủy đơn hàng',
                'content' => 'Bạn có thể hủy đơn trong vòng 1 giờ sau khi đặt hàng. Truy cập "Đơn hàng của tôi" và chọn "Hủy đơn".'
            ],
            [
                'subtle' => 'Hủy đơn',
                'title' => 'Điều kiện hủy đơn',
                'content' => 'Đơn hàng chưa được xác nhận và vận chuyển. Các đơn đã xác nhận sẽ không thể hủy.'
            ]
        ];

        DB::table('faqs')->insert($faqs);
    }
}
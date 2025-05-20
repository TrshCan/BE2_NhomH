<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run()
    {
        Faq::create([
            'subtle' => 'Cảnh báo',
            'title' => 'Mua sắm an toàn cùng H',
            'content' => '<p>Để đảm bảo mua sắm an toàn cùng H, bạn nên:</p><ul><li>Chỉ thanh toán thông qua các kênh chính thức của H</li><li>Không chia sẻ thông tin tài khoản, mật khẩu với người khác</li><li>Kiểm tra kỹ thông tin sản phẩm trước khi mua</li><li>Giữ lại chứng từ thanh toán và mã đơn hàng</li><li>Liên hệ ngay với bộ phận CSKH khi phát hiện dấu hiệu lừa đảo</li></ul>',
        ]);

        Faq::create([
            'subtle' => 'Trả hàng',
            'title' => 'Cách đóng gói đơn hàng hoàn trả',
            'content' => '<p>Để đóng gói đơn hàng hoàn trả, bạn cần:</p><ul><li>Đảm bảo sản phẩm còn nguyên vẹn, đầy đủ phụ kiện và tem mác</li><li>Đóng gói sản phẩm cẩn thận trong hộp/túi bảo vệ</li><li>Đính kèm phiếu hoàn trả và mã đơn hàng</li><li>Liên hệ đơn vị vận chuyển được chỉ định</li><li>Giữ lại biên nhận vận chuyển để theo dõi</li></ul>',
        ]);

        // Add more FAQs as needed
    }
}
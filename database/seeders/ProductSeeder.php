<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        $category = Category::all();
        $company = Company::all();
        Product::create(
            [
                'id_company' => 1,
                'name' => 'Tủ sấy quần áo',
                'price' => (rand(10000,100000)),
                'description' => 'Kích thước: 75*50*147cm.
                ️ Công suất: 1000w.
                ️ Nhiệt độ sấy: 60-70 độ.
                ️ Khối lượng sấy: 15kg.
                ️ Tính năng hẹn giờ: 0-180 phút.',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/1.png'),
            ],
        )->category()->attach($category->where('id',1)->first());
        Product::create(
            [
                'id_company' => 1,
                'name' => 'Nồi chiên không dầu',
                'price' => (rand(10000,100000)),
                'description' => 'NỒI CHIÊN KHÔNG DẦU ĐA NĂNG
                Nồi chiên tích hợp lò sấy và nướng đa năng
                Dung tích 14 lít.
                Thiết kế vô cùng bắt mắt cùng màu xanh coban sang trọng và hiện đại
                Công suất mạnh mẽ 1700w, hoạt động gia nhiệt trên, gia nhiệt dưới cùng quạt đối lưu sẽ giúp chín đều thực phẩm, không cần lật thực phẩm trong quá trình nấu nướng
                Có 16 chức năng để lựa chọn, dễ dàng làm vô số món ăn và món bánh,...bằng NCKD thông minh này
                Màn hình cảm ứng điện tử hiện đại, nắp kính sang trọng giúp dễ dàng quan sát thực phẩm bên trong',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/2.png'),
            ],
        )->category()->attach([1]);
        Product::create(
            [
                'id_company' => 1,
                'name' => 'Bếp lẩu nướng đa năng',
                'price' => (rand(10000,100000)),
                'description' => '- Tên sản phẩm: Bếp Nướng Điện, Nồi Lẩu Điện, nồi lẩu nướng, nồi lẩu đa năng
                - Điện áp: 220V ~ 50Hz
                - Công suất: 2700
                - Nhiệt độ: 0 - 230 độ C
                - Chất liệu bộ phát nhiệt:Inox 304
                - Phương thức điều khiển: Nút vặn
                - Chất liệu: Vỏ chống dính an toàn
                -Loại sản phẩm: bếp lẩu liền không tách rời',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/3.png'),
            ],
        )->category()->attach([1]);
        Product::create(
            [
                'id_company' => 1,
                'name' => 'Đầu Lọc Nước Tại Vòi',
                'price' => (rand(10000,100000)),
                'description' => 'Lắp vừa mọi loại đầu ống
                Bộ lọc nước tại vòi hiện đại tiêu chuẩn
                Bộ lọc nước tại vòi hiện đại được làm từ chất liệu INOX siêu bền và thiết kế lõi lọc Sứ cao cấp, sáng bóng, bền đẹp và sang trọng.- Sản phẩm có thiết kế nhỏ gọn, dễ dàng lắp đặt, tương thích với hầu hết các loại vòi nước. Dụng cụ có thể lọc các độc tố gây bệnh như asen, amoni, các kim loại nặng, đóng cặ và khử sạch mùi nước sinh hoạt. có thể lọc các độc tố gây bệnh như asen, amoni, các kim loại nặng, đóng cặ và khử sạch mùi nước sinh hoạt.',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/4.png'),
            ],
        )->category()->attach([1]);
        Product::create(
            [
                'id_company' => 2,
                'name' => 'Robot tự đổ rác hút bụi lau nhà',
                'price' => (rand(10000,100000)),
                'description' => 'Robot hút bụi lau nhà tự đổ rác Neabot N1 Plus hàng chính hãng 2021',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/5.png'),
            ],
        )->category()->attach([1,2]);
        Product::create(
            [
                'id_company' => 2,
                'name' => 'Bộ cáp sạc nhanh đa năng',
                'price' => (rand(10000,100000)),
                'description' => 'Tất cả cáp sạc, đầu sạc, chọc sim, khay đựng sim, giá đỡ điện thoại... đều có mặt trong RC-190

                Cáp sạc nhanh đủ bộ 3 đầu phù hợp với hầu hết các thiết bị thông minh hiện nay

                Tốc độ sạc lên đến 60W

                Công nghệ tự động nhận dạng thiết bị và điều chỉnh công suất, điện áp và dòng điện phù hợp với từng loại thiết bị',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/6.png'),
            ],
        )->category()->attach([2]);
        Product::create(
            [
                'id_company' => 2,
                'name' => 'Loa siêu trầm',
                'price' => (rand(10000,100000)),
                'description' => 'Công suất vượt trội và mở rộng tần số thấp xuống sâu đến 16 Hz với biên độ chính xác và có kiểm soát. Trang bị củ loa SVS 12 inch hoàn toàn mới, bộ khuếch đại Sledge 550 Watts RMS, công suất cực đại 1.500+ Watts với đầu ra MOSFET hoàn toàn tách biệt và ứng dụng điện thoại thông minh SVS subwoofer DSP. Được tối ưu hóa với thiết kế thùng loa họng thông hơi kép để loa đạt được mức âm trầm với độ méo tiếng thấp',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/7.png'),
            ],
        )->category()->attach([2]);
        Product::create(
            [
                'id_company' => 3,
                'name' => 'Chả ram tôm đất',
                'price' => (rand(10000,100000)),
                'description' => 'Với sản phẩm tươi sống, trọng lượng thực tế có thể chênh lệch khoảng 10%.',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/8.png'),
            ],
        )->category()->attach([3]);
        Product::create(
            [
                'id_company' => 3,
                'name' => 'Trứng cá ngừ đại dương',
                'price' => (rand(10000,100000)),
                'description' => 'Trứng cá ngừ đại dương là thực phẩm hải sản sung dinh dưỡng tốt nhất cho người gầy, người già yếu đặc biệt là cho trẻ em.',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/9.png'),
            ],
        )->category()->attach([3]);
        Product::create(
            [
                'id_company' => 3,
                'name' => 'Bánh Danisa',
                'price' => (rand(10000,100000)),
                'description' => 'tra mạng là có',
                'amount_sold' => rand(0,100),
                'amount_remaining' => rand(0,100),
                'image' => ('https://pbl5-backend.herokuapp.com/products/10.png'),
            ],
        )->category()->attach([3]);
    }
}

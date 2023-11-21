<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## OrganicE-ecommerceBackend
<<<<<<< HEAD
=======
## Project Overview
<p>OrganicE là một nền tảng thương mại điện tử bền vững tập trung vào việc cung cấp các sản phẩm hữu cơ và thân thiện với môi trường cho người tiêu dùng có ý thức về môi trường. Backend xử lý nhiều khía cạnh của hệ sinh thái thương mại điện tử, bao gồm xác thực người dùng, quản lý sản phẩm, xử lý đơn hàng và nhiều tính năng khác.</p>
## Getting Started
<p>Dự án trả về api hiển thị, để quản lý sản phẩm, danh mục, tài khoản người dùng và đơn đặt hàng. API Xác thực người dùng được xử lý bằng Laravel Sanctum. Người dùng có thể đăng ký, đăng nhập và thực hiện các hành động được xác thực.</p>
<ul>Đảm bảo bạn đã cài đặt:
    <li>PhP</li>
    <li>Composer</li>
    <li>Laravel</li>
</ul>
<h6>Cách cài đặt:</h6>

    # Sao chép dự án
    git clone https://github.com/Buiducthang123/OrganicE-ecommerceBackend.git
    
    #Di chuyển vào thư mục dự án
    cd backend
    
    #Cài đặt các phụ thuộc
    composer install
    
    #Tạo một bản sao của tệp .env
    cp .env.example .env
    
    # Tạo khóa ứng dụng
    php artisan key:generate
<h6>Cách chạy:</h6>

    #Chạy các di chuyển cơ sở dữ liệu
    php artisan migrate

    # Bắt đầu máy chủ phát triển
    php artisan serve
<h6>Khum chạy được là lỗi của bạn =))</h6>
<h6>Để xem tất cả các route api cần chạy: </h6>

    php artisan route:list
##Các api và cách sử dụng
<h6>Method: Get</h6>
    <ul>
        <li>api/getCurrentUser: Trả về thông tin user hiện tại đang đăng nhập</li>
        <li>api/categories: Trả về ra danh sách các categories</li>
        <li>api/categories/{category}: Trả về danh sách tất cả các sản phẩm trong category</li>
        <li>api/product: Trả về danh sách tất cả product</li>
        <li>api/products/featuredProducts: Trả về danh sách các sản phẩm nổi bật </li>
        <li>api/products/bestSellerProducts: Trả về danh sách các sản phẩm bán chạy nhât </li>
        <li>api/products/hotDeals: Trả về danh sách sản phẩm có ưu đãi lớn</li>
        <li>api/products/searchProduct: Tìm kiếm sản phẩm</li>
        <li>api/produts/quickView/{id}: Trả về thông tin nhanh của sản phẩm</li>
        <li>api/reviews: Trả về đánh giá của khách hàng về trang web</li>
        <li>api/cart: Trả về các sản phẩm trong giỏ hàng của user đang đăng nhập</li>
        <li>api/carts/quickInfor: Trả về thông tin nhanh của giỏ hàng</li>
        <li>api/wish_list: Trả về danh sách sản phẩm yêu thích của User yêu thích </li>
        <li>api//blog: Trả về các bài blog</li>
        <li>api//blog/{id}: Xem chi tiết blog</li>
        <li>api/blog/comments/{blog_id}: Hiển thị các bình luận của 1 blog</li>
        <li>api/order_detail: Trả về các đơn hàng đã đặt</li>
    </ul>




>>>>>>> ec455b7daffb5133f132cf4a3a2db7934918ed1a

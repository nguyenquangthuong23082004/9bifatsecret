<?= $this->extend('inc/head') ?>

<?= $this->section('content') ?>
<section class="hero" id="home">
    <div class="hero-copy">
        <p style="text-transform:uppercase; letter-spacing:.2em; color:#2563eb; font-weight:700; margin-bottom:16px;">Landing Page</p>
        <h1>Đơn giản, sạch và tập trung vào nội dung chính</h1>
        <p>Trang này được thiết kế lại từ đầu với cấu trúc nhẹ và chỉ giữ lại phần nội dung cần thiết cho một landing page.</p>
        <div class="cta-group">
            <a class="cta-primary" href="#contact">Liên hệ ngay</a>
            <a class="cta-secondary" href="#about">Xem thêm</a>
        </div>
    </div>
    <div class="hero-visual">
        <div class="hero-card">Hình ảnh chính hoặc thông điệp nổi bật</div>
    </div>
</section>
<section class="section" id="about">
    <h2>Giới thiệu</h2>
    <p>Đây là landing page gọn gàng, không còn giao diện cũ hoặc logic hiển thị phức tạp của trang trước đó.</p>
</section>
<section class="section" id="features">
    <div class="grid">
        <div class="card">
            <h3>Gọn nhẹ</h3>
            <p>Mọi phần đều được thiết kế để chạy nhanh và dễ chỉnh sửa tiếp.</p>
        </div>
        <div class="card">
            <h3>Không logic thừa</h3>
            <p>Không còn các thành phần view động, script UI hoặc CSS cồng kềnh.</p>
        </div>
        <div class="card">
            <h3>Dễ mở rộng</h3>
            <p>Thêm nội dung mới, form liên hệ hoặc CTA bất cứ lúc nào.</p>
        </div>
    </div>
</section>
<section class="section" id="contact">
    <h2>Liên hệ</h2>
    <p>Thêm thông tin liên hệ tại đây nếu bạn muốn yêu cầu người dùng gửi form hoặc gọi điện.</p>
</section>
<?= $this->endSection() ?>

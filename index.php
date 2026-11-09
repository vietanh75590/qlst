<?php

// Khởi động session
session_start();

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/config/Access.php';

// Lấy tên controller và action từ URL, mặc định là 'dashboard' và 'index'
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Lấy id nếu có (dùng cho edit, delete, view - hỗ trợ cả chuỗi như SP01, KH01 và số)
$id = isset($_GET['id']) ? trim($_GET['id']) : null;

// Ánh xạ tên URL sang tên lớp có chữ hoa tương ứng trên Linux
$controllers = [
    'dashboard' => 'DashboardController',
    'donhang' => 'DonHangController',
    'phieunhap' => 'PhieuNhapController',
    'sanpham' => 'SanPhamController',
    'danhmuc' => 'DanhMucController',
    'nhacungcap' => 'NhaCungCapController',
    'nguoidung' => 'NguoiDungController',
    'calamviec' => 'CaLamViecController',
    'lichlamviec' => 'LichLamViecController',
];
$controllerClass = $controllers[strtolower($controllerName)] ?? null;

// Đường dẫn tới file controller
$controllerFile = __DIR__ . '/controllers/' . $controllerClass . '.php';

try {
    // Nếu controller không tồn tại (ví dụ: video đã gỡ bỏ), tự động chuyển về trang chủ Dashboard
    if (!$controllerClass || !file_exists($controllerFile)) {
        header('Location: index.php?controller=dashboard&action=index');
        exit;
    }

    // Nạp file controller
    require_once $controllerFile;

    // Kiểm tra class controller có tồn tại không
    if (!class_exists($controllerClass)) {
        throw new Exception("Controller '{$controllerClass}' không tồn tại.");
    }

    // Khởi tạo controller
    $controller = new $controllerClass();

    // Kiểm tra phương thức action có tồn tại không
    if (!method_exists($controller, $action)) {
        throw new Exception("Hành động '{$action}' không tồn tại trong {$controllerClass}.");
    }

    // Gọi phương thức action, truyền id nếu có
    if ($id !== null) {
        $controller->$action($id);
    } else {
        $controller->$action();
    }

} catch (Exception $e) {
    // Xử lý lỗi thân thiện với người dùng
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lỗi - Quản Lý Siêu Thị</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h1 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Lỗi</h1>
                            <p class="lead"><?php echo htmlspecialchars($e->getMessage()); ?></p>
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-house"></i> Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

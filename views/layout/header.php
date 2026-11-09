<?php
$isLoggedIn = isset($_SESSION['user_id']) || isset($_SESSION['user']);
$userName = $_SESSION['user_name'] ?? ($_SESSION['user']['hoten'] ?? 'Khách');
$userRole = strtolower($_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? 'guest'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= !empty($title) ? htmlspecialchars($title) . ' - ' : '' ?>Hệ Thống Quản Lý Siêu Thị</title>
</head>
<body>
    <h1>HỆ THỐNG QUẢN LÝ SIÊU THỊ</h1>
    <table border="0" width="100%">
        <tr>
            <td>
                <b>MENU:</b>
                <?php if ($userRole === 'customer'): ?>
                    <!-- Khách hàng (CUSTOMER) -->
                    <a href="index.php?controller=sanpham&action=index">Sản phẩm</a> |
                    <a href="index.php?controller=danhmuc&action=index">Danh mục</a> |
                    <a href="index.php?controller=donhang&action=create">Đặt mua hàng</a> |
                    <a href="index.php?controller=donhang&action=index">Lịch sử đơn hàng</a>
                <?php elseif (in_array($userRole, ['admin', 'sales', 'warehouse'], true)): ?>
                    <a href="index.php?controller=sanpham&action=index">Sản phẩm</a> |
                    <a href="index.php?controller=danhmuc&action=index">Danh mục</a> |
                    <?php if (in_array($userRole, ['admin', 'sales'], true)): ?>
                        <a href="index.php?controller=donhang&action=index">Bán hàng</a> |
                    <?php endif; ?>
                    <?php if (in_array($userRole, ['admin', 'warehouse'], true)): ?>
                        <a href="index.php?controller=nhacungcap&action=index">Nhà cung cấp</a> |
                        <a href="index.php?controller=phieunhap&action=index">Nhập kho</a> |
                    <?php endif; ?>
                    <?php if ($userRole === 'admin'): ?>
                        <a href="index.php?controller=dashboard&action=index">Trang chủ</a> |
                        <a href="index.php?controller=nguoidung&action=index">Nhân sự</a> |
                        <a href="index.php?controller=calamviec&action=index">Ca làm việc</a> |
                    <?php endif; ?>
                    <a href="index.php?controller=lichlamviec&action=index">Lịch làm việc</a>
                <?php else: ?>
                    <!-- Chưa đăng nhập -->
                    <a href="index.php?controller=sanpham&action=index">Sản phẩm</a> |
                    <a href="index.php?controller=danhmuc&action=index">Danh mục</a>
                <?php endif; ?>
            </td>
            <td align="right">
                <?php if ($isLoggedIn): ?>
                    Xin chào, <b><?= htmlspecialchars($userName) ?></b> (<?= strtoupper($userRole) ?>) | 
                    <a href="index.php?controller=nguoidung&action=logout">Đăng xuất</a>
                <?php else: ?>
                    <a href="index.php?controller=nguoidung&action=login">Đăng nhập</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <hr>
    <?php if (isset($_SESSION['message'])): ?>
        <p><b>[Thông báo]:</b> <?= htmlspecialchars($_SESSION['message']) ?></p>
        <?php unset($_SESSION['message']); ?>
        <hr>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p><font color="red"><b>[Lỗi]:</b> <?= htmlspecialchars($_SESSION['error']) ?></font></p>
        <?php unset($_SESSION['error']); ?>
        <hr>
    <?php endif; ?>

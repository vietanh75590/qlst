<?php
require_once 'views/layout/header.php';
$userRole = strtolower($_SESSION['user_role'] ?? 'guest');
$isAdminOrStaff = in_array($userRole, ['admin', 'staff']);
$isCustomer = ($userRole === 'customer');
?>

<h2><?= $isCustomer ? 'DANH MỤC SẢN PHẨM SIÊU THỊ' : 'QUẢN LÝ SẢN PHẨM / HÀNG HÓA' ?></h2>

<table border="0" width="100%">
    <tr>
        <td>
            <form action="index.php" method="GET">
                <input type="hidden" name="controller" value="sanpham">
                <input type="hidden" name="action" value="index">
                Tìm kiếm sản phẩm: 
                <input type="text" name="keyword" placeholder="Nhập tên hoặc mã sản phẩm" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                <input type="submit" value="Tìm kiếm">
                <?php if (!empty($_GET['keyword']) || !empty($_GET['id_danhmuc'])): ?>
                    <a href="index.php?controller=sanpham&action=index">Xem tất cả</a>
                <?php endif; ?>
            </form>
        </td>
        <td align="right">
            <?php if ($isAdminOrStaff): ?>
                <a href="index.php?controller=sanpham&action=create"><b>+ Thêm sản phẩm mới</b></a>
            <?php elseif ($isCustomer): ?>
                <a href="index.php?controller=donhang&action=create"><b>+ ĐẶT MUA HÀNG TRỰC TUYẾN</b></a>
            <?php endif; ?>
        </td>
    </tr>
</table>

<br>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Mã SP</th>
        <th>Tên sản phẩm</th>
        <th>Danh mục</th>
        <th>Nhà cung cấp</th>
        <th>Giá bán</th>
        <th>Đơn vị</th>
        <th>Tồn kho</th>
        <th>Hạn dùng</th>
        <th>Thao tác</th>
    </tr>
    <?php if (!empty($sanphams)): ?>
        <?php foreach ($sanphams as $sp): ?>
            <tr align="center">
                <td><?= $sp['id'] ?></td>
                <td align="left"><b><?= htmlspecialchars($sp['tensp']) ?></b></td>
                <td><?= htmlspecialchars($sp['tendanhmuc'] ?? 'Chưa phân loại') ?></td>
                <td><?= htmlspecialchars($sp['tennhacungcap'] ?? 'N/A') ?></td>
                <td align="right"><b><?= number_format($sp['giasp']) ?> đ</b></td>
                <td><?= htmlspecialchars($sp['donvi']) ?></td>
                <td>
                    <?php if ($sp['tonkho'] <= 10): ?>
                        <b><font color="red"><?= $sp['tonkho'] ?> (Sắp hết)</font></b>
                    <?php else: ?>
                        <?= $sp['tonkho'] ?>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($sp['hsd'] ?? 'N/A') ?></td>
                <td>
                    <?php if ($isAdminOrStaff): ?>
                        <a href="index.php?controller=sanpham&action=edit&id=<?= $sp['id'] ?>">Sửa</a> |
                        <a href="index.php?controller=sanpham&action=delete&id=<?= $sp['id'] ?>">Xóa</a>
                    <?php elseif ($isCustomer): ?>
                        <a href="index.php?controller=donhang&action=create&id_sp=<?= $sp['id'] ?>"><b>[+ Đặt mua]</b></a>
                    <?php else: ?>
                        <a href="index.php?controller=nguoidung&action=login">Đăng nhập để mua</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" align="center">Không có sản phẩm nào.</td>
        </tr>
    <?php endif; ?>
</table>



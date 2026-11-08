<?php
require_once 'views/layout/header.php';
?>

<h2>TỔNG QUAN HỆ THỐNG (DASHBOARD)</h2>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th width="25%">Tổng sản phẩm</th>
        <th width="25%">Tổng đơn hàng</th>
        <th width="25%">Tổng doanh thu</th>
        <th width="25%">Sắp hết hàng</th>
    </tr>
    <tr align="center">
        <td><h3><?= isset($tongSanPham) ? $tongSanPham : 0 ?></h3></td>
        <td><h3><?= isset($tongDonHang) ? $tongDonHang : 0 ?></h3></td>
        <td><h3><?= isset($doanhThu) ? number_format($doanhThu) : 0 ?> đ</h3></td>
        <td><h3><font color="red"><?= isset($sapHetHang) ? $sapHetHang : 0 ?></font></h3></td>
    </tr>
</table>

<br>

<h3>DANH SÁCH SẢN PHẨM SẮP HẾT HÀNG (TỒN KHO &le; 10)</h3>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th>Mã SP</th>
        <th>Tên sản phẩm</th>
        <th>Số lượng tồn</th>
        <th>Đơn vị</th>
        <th>Hạn sử dụng</th>
        <th>Thao tác</th>
    </tr>
    <?php if (!empty($sanPhamSapHet)): ?>
        <?php foreach ($sanPhamSapHet as $sp): ?>
            <tr align="center">
                <td><?= $sp['id'] ?></td>
                <td align="left"><?= htmlspecialchars($sp['tensp']) ?></td>
                <td><b><font color="red"><?= $sp['tonkho'] ?></font></b></td>
                <td><?= htmlspecialchars($sp['donvi']) ?></td>
                <td><?= htmlspecialchars($sp['hsd'] ?? 'N/A') ?></td>
                <td>
                    <a href="index.php?controller=sanpham&action=edit&id=<?= $sp['id'] ?>">Cập nhật kho/giá</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" align="center">Hiện không có sản phẩm nào sắp hết hàng.</td>
        </tr>
    <?php endif; ?>
</table>



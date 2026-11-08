<?php
require_once 'views/layout/header.php';
?>

<h2>XÓA SẢN PHẨM</h2>
<p><a href="index.php?controller=sanpham&action=index">&laquo; Quay lại danh sách sản phẩm</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#ffcccc">
        <td colspan="2" align="center"><b style="color: red;">⚠️ XÁC NHẬN XÓA SẢN PHẨM</b></td>
    </tr>
    <tr>
        <td width="150"><b>Mã sản phẩm:</b></td>
        <td><?= htmlspecialchars($sanpham['id']) ?></td>
    </tr>
    <tr>
        <td><b>Tên sản phẩm:</b></td>
        <td><?= htmlspecialchars($sanpham['tensp']) ?></td>
    </tr>
    <tr>
        <td><b>Danh mục:</b></td>
        <td><?= htmlspecialchars($sanpham['tendanhmuc'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <td><b>Giá bán:</b></td>
        <td><?= number_format($sanpham['giasp']) ?> đ</td>
    </tr>
    <tr>
        <td><b>Tồn kho:</b></td>
        <td><?= $sanpham['tonkho'] ?></td>
    </tr>
</table>

<br>

<p style="color: red;"><b>⚠️ Lưu ý: Hành động này không thể hoàn tác!</b></p>

<form action="index.php?controller=sanpham&action=delete&id=<?= $sanpham['id'] ?>" method="POST" style="display: inline;">
    <input type="submit" value="✓ Xác nhận xóa" onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này không?')" style="background-color: red; color: white; padding: 8px 15px; cursor: pointer;">
</form>

<a href="index.php?controller=sanpham&action=index" style="padding: 8px 15px; background-color: #ccc; text-decoration: none;">✗ Hủy</a>

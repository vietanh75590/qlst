<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA NGƯỜI DÙNG</h2>
<p><a href="index.php?controller=nguoidung&action=index">&laquo; Quay lại danh sách người dùng</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>ID:</b> <?= htmlspecialchars($nguoidung['id']) ?></p>
    <p><b>Họ tên:</b> <?= htmlspecialchars($nguoidung['hoten']) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($nguoidung['email']) ?></p>
    <p><b>Vai trò:</b> <?= htmlspecialchars($nguoidung['role']) ?></p>
    <p><b>Số điện thoại:</b> <?= htmlspecialchars($nguoidung['sodienthoai'] ?? 'N/A') ?></p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=nguoidung&action=delete&id=<?= $nguoidung['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=nguoidung&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

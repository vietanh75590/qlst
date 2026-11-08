<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA NHÀ CUNG CẤP</h2>
<p><a href="index.php?controller=nhacungcap&action=index">&laquo; Quay lại danh sách nhà cung cấp</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>Mã nhà cung cấp:</b> <?= htmlspecialchars($nhacungcap['id']) ?></p>
    <p><b>Tên nhà cung cấp:</b> <?= htmlspecialchars($nhacungcap['tennhacungcap']) ?></p>
    <p><b>Số điện thoại:</b> <?= htmlspecialchars($nhacungcap['sodienthoai']) ?></p>
    <p><b>Địa chỉ:</b> <?= htmlspecialchars($nhacungcap['diachi']) ?></p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=nhacungcap&action=delete&id=<?= $nhacungcap['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=nhacungcap&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

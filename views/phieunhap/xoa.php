<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA PHIẾU NHẬP</h2>
<p><a href="index.php?controller=phieunhap&action=index">&laquo; Quay lại danh sách phiếu nhập</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>Mã phiếu nhập:</b> <?= htmlspecialchars($phieunhap['id']) ?></p>
    <p><b>Ngày nhập:</b> <?= htmlspecialchars($phieunhap['ngaynhap'] ?? 'N/A') ?></p>
    <p><b>Nhà cung cấp:</b> <?= htmlspecialchars($phieunhap['id_nhacungcap']) ?></p>
    <p><b>Tổng tiền:</b> <?= number_format($phieunhap['tongtien'] ?? 0) ?> đ</p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=phieunhap&action=delete&id=<?= $phieunhap['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=phieunhap&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

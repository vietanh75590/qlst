<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA ĐƠN HÀNG</h2>
<p><a href="index.php?controller=donhang&action=index">&laquo; Quay lại danh sách đơn hàng</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>Mã đơn hàng:</b> <?= htmlspecialchars($donhang['id']) ?></p>
    <p><b>Ngày tạo:</b> <?= htmlspecialchars($donhang['ngaytao'] ?? 'N/A') ?></p>
    <p><b>Khách hàng:</b> <?= htmlspecialchars($donhang['id_khachhang']) ?></p>
    <p><b>Tổng tiền:</b> <?= number_format($donhang['tongtien']) ?> đ</p>
    <p><b>Trạng thái:</b> <?= htmlspecialchars($donhang['trangthai'] ?? 'N/A') ?></p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=donhang&action=delete&id=<?= $donhang['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=donhang&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

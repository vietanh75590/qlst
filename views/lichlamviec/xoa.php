<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA LỊCH LÀM VIỆC</h2>
<p><a href="index.php?controller=lichlamviec&action=index">&laquo; Quay lại danh sách lịch làm việc</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>ID:</b> <?= htmlspecialchars($lichlamviec['id']) ?></p>
    <p><b>Nhân viên:</b> <?= htmlspecialchars($lichlamviec['id_nhanvien']) ?></p>
    <p><b>Ca làm:</b> <?= htmlspecialchars($lichlamviec['id_ca']) ?></p>
    <p><b>Ngày làm:</b> <?= htmlspecialchars($lichlamviec['ngaylam']) ?></p>
    <p><b>Ghi chú:</b> <?= htmlspecialchars($lichlamviec['ghichu'] ?? 'N/A') ?></p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=lichlamviec&action=delete&id=<?= $lichlamviec['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=lichlamviec&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

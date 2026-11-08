<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA CA LÀM VIỆC</h2>
<p><a href="index.php?controller=calamviec&action=index">&laquo; Quay lại danh sách ca làm việc</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>ID Ca:</b> <?= htmlspecialchars($calamviec['id']) ?></p>
    <p><b>Tên ca:</b> <?= htmlspecialchars($calamviec['tenca']) ?></p>
    <p><b>Giờ bắt đầu:</b> <?= htmlspecialchars($calamviec['giobatdau']) ?></p>
    <p><b>Giờ kết thúc:</b> <?= htmlspecialchars($calamviec['gioketthuc']) ?></p>
    <p><b>Mô tả:</b> <?= htmlspecialchars($calamviec['mota'] ?? 'N/A') ?></p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=calamviec&action=delete&id=<?= $calamviec['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=calamviec&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

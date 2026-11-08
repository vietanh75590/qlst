<?php
require_once 'views/layout/header.php';
?>

<h2>XÁC NHẬN XÓA DANH MỤC</h2>
<p><a href="index.php?controller=danhmuc&action=index">&laquo; Quay lại danh sách danh mục</a></p>

<div style="border: 2px solid #ff9999; padding: 15px; background-color: #ffe6e6; width: 500px;">
    <h3 style="color: red;">⚠️ Bạn có chắc chắn muốn xóa?</h3>
    <p><b>Mã danh mục:</b> <?= htmlspecialchars($danhmuc['id']) ?></p>
    <p><b>Tên danh mục:</b> <?= htmlspecialchars($danhmuc['tendanhmuc']) ?></p>
    <p><b>Mô tả:</b> <?= htmlspecialchars($danhmuc['mota'] ?? 'N/A') ?></p>
    <p style="color: red;"><b>Lưu ý: Hành động này không thể hoàn tác!</b></p>

    <form action="index.php?controller=danhmuc&action=delete&id=<?= $danhmuc['id'] ?>" method="POST" style="margin-top: 20px;">
        <button type="submit" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">
            ✓ Xác nhận xóa
        </button>
        <button type="button" onclick="window.location.href='index.php?controller=danhmuc&action=index'" style="background-color: gray; color: white; padding: 8px 15px; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Hủy
        </button>
    </form>
</div>

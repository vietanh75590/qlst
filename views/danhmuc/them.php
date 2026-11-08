<?php
require_once 'views/layout/header.php';
?>

<h2>THÊM DANH MỤC HÀNG HÓA MỚI</h2>
<p><a href="index.php?controller=danhmuc&action=index">&laquo; Quay lại danh sách</a></p>

<form action="index.php?controller=danhmuc&action=create" method="POST">
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <td><b>Mã danh mục:</b></td>
            <td><input type="text" name="id" placeholder="Tự động tạo (DM06...) nếu để trống"></td>
        </tr>
        <tr>
            <td><b>Tên danh mục (*):</b></td>
            <td><input type="text" name="tendanhmuc" size="40" required></td>
        </tr>
        <tr>
            <td><b>Danh mục cha (không bắt buộc):</b></td>
            <td>
                <select name="id_danhmuc_cha">
                    <option value="">-- Không có (danh mục chính) --</option>
                    <?php if (!empty($danhmucs)): ?>
                        <?php foreach ($danhmucs as $dm): ?>
                            <option value="<?= $dm['id'] ?>"><?= htmlspecialchars($dm['tendanhmuc']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Mô tả:</b></td>
            <td><textarea name="mota" rows="4" cols="40"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Lưu danh mục">
                <a href="index.php?controller=danhmuc&action=index">Hủy</a>
            </td>
        </tr>
    </table>
</form>



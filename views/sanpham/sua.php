<?php
require_once 'views/layout/header.php';
?>

<h2>CHỈNH SỬA SẢN PHẨM: <?= htmlspecialchars($sanpham['tensp']) ?></h2>
<p><a href="index.php?controller=sanpham&action=index">&laquo; Quay lại danh sách sản phẩm</a></p>

<form action="index.php?controller=sanpham&action=edit&id=<?= $sanpham['id'] ?>" method="POST">
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <td><b>Mã sản phẩm:</b></td>
            <td><b><?= $sanpham['id'] ?></b></td>
        </tr>
        <tr>
            <td><b>Tên sản phẩm (*):</b></td>
            <td><input type="text" name="tensp" size="50" value="<?= htmlspecialchars($sanpham['tensp']) ?>" required></td>
        </tr>
        <tr>
            <td><b>Danh mục hàng (*):</b></td>
            <td>
                <select name="id_danhmuc" required>
                    <?php foreach ($danhmucs as $dm): ?>
                        <option value="<?= $dm['id'] ?>" <?= $dm['id'] === $sanpham['id_danhmuc'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dm['tendanhmuc']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Nhà cung cấp:</b></td>
            <td>
                <select name="id_nhacungcap">
                    <?php foreach ($nhacungcaps as $ncc): ?>
                        <option value="<?= $ncc['id'] ?>" <?= $ncc['id'] === $sanpham['id_nhacungcap'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ncc['tennhacungcap']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Giá bán (VNĐ) (*):</b></td>
            <td><input type="number" name="giasp" value="<?= (int)$sanpham['giasp'] ?>" min="0" required></td>
        </tr>
        <tr>
            <td><b>Đơn vị tính:</b></td>
            <td><input type="text" name="donvi" value="<?= htmlspecialchars($sanpham['donvi']) ?>"></td>
        </tr>
        <tr>
            <td><b>Số lượng tồn kho hiện tại:</b></td>
            <td><b><?= $sanpham['tonkho'] ?></b> <?= htmlspecialchars($sanpham['donvi']) ?> <i>(Quản lý qua Nhập kho và Bán hàng)</i></td>
        </tr>
        <tr>
            <td><b>Mô tả sản phẩm:</b></td>
            <td><textarea name="mota" rows="4" cols="50"><?= htmlspecialchars($sanpham['mota'] ?? '') ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Cập nhật thông tin">
                <a href="index.php?controller=sanpham&action=index">Hủy bỏ</a>
            </td>
        </tr>
    </table>
</form>

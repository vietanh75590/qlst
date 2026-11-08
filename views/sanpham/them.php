<?php
require_once 'views/layout/header.php';
?>

<h2>THÊM SẢN PHẨM MỚI</h2>
<p><a href="index.php?controller=sanpham&action=index">&laquo; Quay lại danh sách sản phẩm</a></p>

<form action="index.php?controller=sanpham&action=create" method="POST">
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <td><b>Mã sản phẩm:</b></td>
            <td><input type="text" name="id" placeholder="Tự động tạo nếu để trống (ví dụ: SP09)"></td>
        </tr>
        <tr>
            <td><b>Tên sản phẩm (*):</b></td>
            <td><input type="text" name="tensp" size="50" required></td>
        </tr>
        <tr>
            <td><b>Danh mục hàng (*):</b></td>
            <td>
                <select name="id_danhmuc" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($danhmucs as $dm): ?>
                        <option value="<?= $dm['id'] ?>"><?= htmlspecialchars($dm['tendanhmuc']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Nhà cung cấp:</b></td>
            <td>
                <select name="id_nhacungcap">
                    <option value="">-- Chọn nhà cung cấp --</option>
                    <?php foreach ($nhacungcaps as $ncc): ?>
                        <option value="<?= $ncc['id'] ?>"><?= htmlspecialchars($ncc['tennhacungcap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Giá bán (VNĐ) (*):</b></td>
            <td><input type="number" name="giasp" value="50000" min="0" required></td>
        </tr>
        <tr>
            <td><b>Đơn vị tính:</b></td>
            <td><input type="text" name="donvi" value="Hộp"></td>
        </tr>
        <tr>
            <td><b>Số lượng tồn kho ban đầu:</b></td>
            <td><input type="number" name="tonkho" value="20" min="0"></td>
        </tr>
        <tr>
            <td><b>Hạn sử dụng:</b></td>
            <td><input type="date" name="hsd" value="<?= date('Y-m-d', strtotime('+6 months')) ?>"></td>
        </tr>
        <tr>
            <td><b>Mô tả sản phẩm:</b></td>
            <td><textarea name="mota" rows="4" cols="50"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Lưu sản phẩm">
                <input type="reset" value="Nhập lại">
            </td>
        </tr>
    </table>
</form>

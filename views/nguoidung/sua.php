<?php
/**
 * Chỉnh sửa người dùng - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>CHỈNH SỬA NGƯỜI DÙNG: <?= htmlspecialchars($nguoidung['hoten']) ?></h2></td>
        <td align="right">
            <a href="index.php?controller=nguoidung&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<form action="index.php?controller=nguoidung&action=edit&id=<?= $nguoidung['id'] ?>" method="POST">
    <table border="1" cellpadding="8" cellspacing="0" width="600">
        <tr bgcolor="#f0f0f0">
            <th colspan="2" align="left">Thông tin người dùng</th>
        </tr>
        <tr>
            <td width="35%"><b>Mã người dùng:</b></td>
            <td><b><?= htmlspecialchars($nguoidung['id']) ?></b></td>
        </tr>
        <tr>
            <td><b>Họ và tên (*):</b></td>
            <td><input type="text" name="hoten" required value="<?= htmlspecialchars($nguoidung['hoten']) ?>" size="40"></td>
        </tr>
        <tr>
            <td><b>Email (*):</b></td>
            <td><input type="email" name="email" required value="<?= htmlspecialchars($nguoidung['email']) ?>" size="40"></td>
        </tr>
        <tr>
            <td><b>Mật khẩu mới:</b></td>
            <td>
                <input type="password" name="matkhau" placeholder="Để trống nếu không đổi" size="40">
                <br><small><i>(Chỉ nhập nếu muốn đổi mật khẩu)</i></small>
            </td>
        </tr>
        <tr>
            <td><b>Số điện thoại:</b></td>
            <td><input type="text" name="sodienthoai" value="<?= htmlspecialchars($nguoidung['sodienthoai'] ?? '') ?>" size="40"></td>
        </tr>
        <tr>
            <td><b>Vai trò:</b></td>
            <td>
                <select name="role">
                    <option value="customer" <?= $nguoidung['role'] === 'customer' ? 'selected' : '' ?>>Khách hàng (Customer)</option>
                    <option value="staff" <?= $nguoidung['role'] === 'staff' ? 'selected' : '' ?>>Nhân viên (Staff)</option>
                    <option value="admin" <?= $nguoidung['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Chức vụ:</b></td>
            <td><input type="text" name="chucvu" value="<?= htmlspecialchars($nguoidung['chucvu'] ?? '') ?>" size="40"></td>
        </tr>
        <tr>
            <td><b>Mức lương (VNĐ):</b></td>
            <td><input type="number" name="luong" value="<?= (int)($nguoidung['luong'] ?? 0) ?>" size="40"></td>
        </tr>
        <tr>
            <td><b>Địa chỉ:</b></td>
            <td>
                <textarea name="diachi" rows="3" cols="42"><?= htmlspecialchars($nguoidung['diachi'] ?? '') ?></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="LƯU THAY ĐỔI">
                <a href="index.php?controller=nguoidung&action=index"><button type="button">Hủy</button></a>
            </td>
        </tr>
    </table>
</form>


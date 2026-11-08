<?php
/**
 * Thêm người dùng mới - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>THÊM NGƯỜI DÙNG / NHÂN VIÊN MỚI</h2></td>
        <td align="right">
            <a href="index.php?controller=nguoidung&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<form action="index.php?controller=nguoidung&action=store" method="POST">
    <table border="1" cellpadding="8" cellspacing="0" width="600">
        <tr bgcolor="#f0f0f0">
            <th colspan="2" align="left">Thông tin người dùng</th>
        </tr>
        <tr>
            <td width="35%"><b>Họ và tên (*):</b></td>
            <td><input type="text" name="hoten" required size="40"></td>
        </tr>
        <tr>
            <td><b>Email (*):</b></td>
            <td><input type="email" name="email" required size="40"></td>
        </tr>
        <tr>
            <td><b>Mật khẩu (*):</b></td>
            <td><input type="password" name="matkhau" required size="40"></td>
        </tr>
        <tr>
            <td><b>Số điện thoại:</b></td>
            <td><input type="text" name="sodienthoai" size="40"></td>
        </tr>
        <tr>
            <td><b>Địa chỉ:</b></td>
            <td><input type="text" name="diachi" size="40"></td>
        </tr>
        <tr>
            <td><b>Vai trò (*):</b></td>
            <td>
                <select name="role" required>
                    <option value="">-- Chọn vai trò --</option>
                    <option value="customer">Khách hàng (Customer)</option>
                    <option value="staff">Nhân viên (Staff)</option>
                    <option value="admin">Quản trị viên (Admin)</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Chức vụ:</b></td>
            <td><input type="text" name="chucvu" placeholder="Thu ngân, Quản lý kho..." size="40"></td>
        </tr>
        <tr>
            <td><b>Mức lương (VNĐ):</b></td>
            <td><input type="number" name="luong" min="0" placeholder="VD: 7000000" size="40"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="LƯU NGƯỜI DÙNG">
                <a href="index.php?controller=nguoidung&action=index"><button type="button">Hủy</button></a>
            </td>
        </tr>
    </table>
</form>



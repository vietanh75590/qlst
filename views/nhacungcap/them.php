<?php
require_once 'views/layout/header.php';
?>

<h2>THÊM NHÀ CUNG CẤP MỚI</h2>
<p><a href="index.php?controller=nhacungcap&action=index">&laquo; Quay lại danh sách</a></p>

<form action="index.php?controller=nhacungcap&action=create" method="POST">
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <td><b>Mã nhà cung cấp:</b></td>
            <td><input type="text" name="id" placeholder="Tự động tạo (NCC05...) nếu để trống"></td>
        </tr>
        <tr>
            <td><b>Tên nhà cung cấp (*):</b></td>
            <td><input type="text" name="tennhacungcap" size="50" required></td>
        </tr>
        <tr>
            <td><b>Số điện thoại:</b></td>
            <td><input type="text" name="sodienthoai" size="20"></td>
        </tr>
        <tr>
            <td><b>Địa chỉ:</b></td>
            <td><textarea name="diachi" rows="3" cols="50"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Lưu nhà cung cấp">
                <a href="index.php?controller=nhacungcap&action=index">Hủy</a>
            </td>
        </tr>
    </table>
</form>

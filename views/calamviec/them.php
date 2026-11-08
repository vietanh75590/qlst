<?php
/**
 * Thêm ca làm việc mới - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>THÊM CA LÀM VIỆC MỚI</h2></td>
        <td align="right">
            <a href="index.php?controller=calamviec&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<form action="index.php?controller=calamviec&action=create" method="POST">
    <table border="1" cellpadding="8" cellspacing="0" width="500">
        <tr bgcolor="#f0f0f0">
            <th colspan="2" align="left">Thông tin ca làm việc</th>
        </tr>
        <tr>
            <td width="35%"><b>Tên ca làm việc (*):</b></td>
            <td><input type="text" name="tenca" placeholder="Ví dụ: Ca sáng, Ca tối" required size="35"></td>
        </tr>
        <tr>
            <td><b>Giờ bắt đầu (*):</b></td>
            <td><input type="time" name="giobatdau" required value="08:00"></td>
        </tr>
        <tr>
            <td><b>Giờ kết thúc (*):</b></td>
            <td><input type="time" name="gioketthuc" required value="17:00"></td>
        </tr>
        <tr>
            <td><b>Mô tả nhiệm vụ:</b></td>
            <td>
                <textarea name="mota" rows="3" cols="35" placeholder="Sắp xếp quầy kệ, kiểm kê hàng hóa..."></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="LƯU CA LÀM VIỆC">
                <a href="index.php?controller=calamviec&action=index"><button type="button">Hủy</button></a>
            </td>
        </tr>
    </table>
</form>

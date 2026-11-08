<?php
/**
 * Chỉnh sửa ca làm việc - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>CHỈNH SỬA CA LÀM VIỆC: <?= htmlspecialchars($calamviec['tenca']) ?></h2></td>
        <td align="right">
            <a href="index.php?controller=calamviec&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<form action="index.php?controller=calamviec&action=edit&id=<?= $calamviec['id'] ?>" method="POST">
    <table border="1" cellpadding="8" cellspacing="0" width="500">
        <tr bgcolor="#f0f0f0">
            <th colspan="2" align="left">Thông tin ca làm việc</th>
        </tr>
        <tr>
            <td width="35%"><b>Mã ca:</b></td>
            <td><b><?= $calamviec['id'] ?></b></td>
        </tr>
        <tr>
            <td><b>Tên ca làm việc (*):</b></td>
            <td><input type="text" name="tenca" required value="<?= htmlspecialchars($calamviec['tenca']) ?>" size="35"></td>
        </tr>
        <tr>
            <td><b>Giờ bắt đầu (*):</b></td>
            <td><input type="time" name="giobatdau" required value="<?= substr($calamviec['giobatdau'], 0, 5) ?>"></td>
        </tr>
        <tr>
            <td><b>Giờ kết thúc (*):</b></td>
            <td><input type="time" name="gioketthuc" required value="<?= substr($calamviec['gioketthuc'], 0, 5) ?>"></td>
        </tr>
        <tr>
            <td><b>Mô tả nhiệm vụ:</b></td>
            <td>
                <textarea name="mota" rows="3" cols="35"><?= htmlspecialchars($calamviec['mota'] ?? '') ?></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="LƯU THAY ĐỔI">
                <a href="index.php?controller=calamviec&action=index"><button type="button">Hủy</button></a>
            </td>
        </tr>
    </table>
</form>

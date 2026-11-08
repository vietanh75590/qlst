<?php
/**
 * Phân ca làm việc mới - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>PHÂN CA LÀM VIỆC MỚI CHO NHÂN VIÊN</h2></td>
        <td align="right">
            <a href="index.php?controller=lichlamviec&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<form action="index.php?controller=lichlamviec&action=create" method="POST">
    <table border="1" cellpadding="8" cellspacing="0" width="550">
        <tr bgcolor="#f0f0f0">
            <th colspan="2" align="left">Thông tin phân ca</th>
        </tr>
        <tr>
            <td width="35%"><b>Chọn nhân viên (*):</b></td>
            <td>
                <select name="id_nhanvien" required>
                    <option value="">-- Chọn nhân viên --</option>
                    <?php foreach ($nhanviens as $nv): ?>
                        <option value="<?= $nv['id'] ?>">
                            <?= htmlspecialchars($nv['hoten']) ?> (<?= $nv['chucvu'] ?? $nv['role'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Chọn ca làm việc (*):</b></td>
            <td>
                <select name="id_ca" required>
                    <option value="">-- Chọn ca làm việc --</option>
                    <?php foreach ($calamviecs as $ca): ?>
                        <option value="<?= $ca['id'] ?>">
                            <?= htmlspecialchars($ca['tenca']) ?> (<?= substr($ca['giobatdau'], 0, 5) ?> - <?= substr($ca['gioketthuc'], 0, 5) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Ngày làm việc (*):</b></td>
            <td>
                <input type="date" name="ngaylam" value="<?= date('Y-m-d') ?>" required>
            </td>
        </tr>
        <tr>
            <td><b>Ghi chú phân công:</b></td>
            <td>
                <input type="text" name="ghichu" placeholder="Trực quầy thu ngân, nhận hàng..." size="35">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="LƯU PHÂN CA">
                <a href="index.php?controller=lichlamviec&action=index"><button type="button">Hủy</button></a>
            </td>
        </tr>
    </table>
</form>

<?php
/**
 * Danh sách lịch làm việc - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>QUẢN LÝ LỊCH PHÂN CA & CHẤM CÔNG</h2></td>
        <td align="right">
            <a href="index.php?controller=lichlamviec&action=create"><b>+ PHÂN CA LÀM VIỆC</b></a>
        </td>
    </tr>
</table>

<?php if (isset($_GET['msg'])): ?>
    <p>
        <?php
        if ($_GET['msg'] === 'created') echo '<font color="green"><b>[Thành công]: Đã phân ca làm việc thành công!</b></font>';
        if ($_GET['msg'] === 'updated') echo '<font color="green"><b>[Thành công]: Đã cập nhật ca làm việc!</b></font>';
        if ($_GET['msg'] === 'deleted') echo '<font color="red"><b>[Thông báo]: Đã xóa lịch làm việc!</b></font>';
        ?>
    </p>
    <hr>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f0f0f0">
        <th align="center" width="60">#</th>
        <th align="left">Nhân viên</th>
        <th align="center">Ca làm việc</th>
        <th align="center">Khung giờ</th>
        <th align="center">Ngày làm việc</th>
        <th align="left">Ghi chú nhiệm vụ</th>
        <th align="center" width="150">Thao tác</th>
    </tr>
    <?php if (!empty($lichlamviecs)): ?>
        <?php foreach ($lichlamviecs as $llv): ?>
            <tr>
                <td align="center"><b><?= $llv['id'] ?></b></td>
                <td><b><?= htmlspecialchars($llv['tennhanvien']) ?></b></td>
                <td align="center"><?= htmlspecialchars($llv['tenca']) ?></td>
                <td align="center"><?= substr($llv['giobatdau'], 0, 5) ?> - <?= substr($llv['gioketthuc'], 0, 5) ?></td>
                <td align="center"><?= date('d/m/Y', strtotime($llv['ngaylam'])) ?></td>
                <td><?= htmlspecialchars($llv['ghichu'] ?? 'Không có') ?></td>
                <td align="center">
                    <a href="index.php?controller=lichlamviec&action=edit&id=<?= $llv['id'] ?>">[Sửa]</a> |
                    <a href="index.php?controller=lichlamviec&action=delete&id=<?= $llv['id'] ?>">[Xóa]</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" align="center">Chưa có lịch phân ca làm việc nào.</td>
        </tr>
    <?php endif; ?>
</table>



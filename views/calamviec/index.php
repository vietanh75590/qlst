<?php
/**
 * Danh sách ca làm việc - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>QUẢN LÝ CA LÀM VIỆC</h2></td>
        <td align="right">
            <a href="index.php?controller=calamviec&action=create"><b>+ THÊM CA LÀM VIỆC</b></a>
        </td>
    </tr>
</table>

<?php if (isset($_GET['msg'])): ?>
    <p>
        <?php
        if ($_GET['msg'] === 'created') echo '<font color="green"><b>[Thành công]: Đã thêm ca làm việc thành công!</b></font>';
        if ($_GET['msg'] === 'updated') echo '<font color="green"><b>[Thành công]: Đã cập nhật ca làm việc!</b></font>';
        if ($_GET['msg'] === 'deleted') echo '<font color="red"><b>[Thông báo]: Đã xóa ca làm việc!</b></font>';
        ?>
    </p>
    <hr>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f0f0f0">
        <th align="center" width="80">Mã ca</th>
        <th align="left">Tên ca làm việc</th>
        <th align="center">Giờ bắt đầu</th>
        <th align="center">Giờ kết thúc</th>
        <th align="left">Mô tả công việc</th>
        <th align="center" width="150">Thao tác</th>
    </tr>
    <?php if (!empty($calamviecs)): ?>
        <?php foreach ($calamviecs as $ca): ?>
            <tr>
                <td align="center"><b><?= $ca['id'] ?></b></td>
                <td><b><?= htmlspecialchars($ca['tenca']) ?></b></td>
                <td align="center"><?= substr($ca['giobatdau'], 0, 5) ?></td>
                <td align="center"><?= substr($ca['gioketthuc'], 0, 5) ?></td>
                <td><?= htmlspecialchars($ca['mota'] ?? '') ?></td>
                <td align="center">
                    <a href="index.php?controller=calamviec&action=edit&id=<?= $ca['id'] ?>">[Sửa]</a> |
                    <a href="index.php?controller=calamviec&action=delete&id=<?= $ca['id'] ?>">[Xóa]</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" align="center">Chưa có ca làm việc nào.</td>
        </tr>
    <?php endif; ?>
</table>


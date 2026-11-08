<?php
/**
 * Danh sách nhân sự / người dùng - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>QUẢN LÝ NHÂN SỰ & NGƯỜI DÙNG</h2></td>
        <td align="right">
            <a href="index.php?controller=nguoidung&action=create"><b>+ THÊM NGƯỜI DÙNG MỚI</b></a>
        </td>
    </tr>
</table>

<?php if (isset($_GET['msg'])): ?>
    <p>
        <?php
        if ($_GET['msg'] === 'created') echo '<font color="green"><b>[Thành công]: Thêm người dùng thành công!</b></font>';
        if ($_GET['msg'] === 'updated') echo '<font color="green"><b>[Thành công]: Cập nhật người dùng thành công!</b></font>';
        if ($_GET['msg'] === 'deleted') echo '<font color="red"><b>[Thông báo]: Đã xóa người dùng!</b></font>';
        ?>
    </p>
    <hr>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f0f0f0">
        <th align="center">Mã</th>
        <th align="left">Họ và tên</th>
        <th align="left">Email</th>
        <th align="center">Số điện thoại</th>
        <th align="center">Vai trò</th>
        <th align="left">Chức vụ</th>
        <th align="right">Lương</th>
        <th align="center">Điểm TL</th>
        <th align="center">Thao tác</th>
    </tr>
    <?php if (!empty($nguoidungs)): ?>
        <?php foreach ($nguoidungs as $nd): ?>
            <tr>
                <td align="center"><b><?= $nd['id'] ?></b></td>
                <td><b><?= htmlspecialchars($nd['hoten']) ?></b></td>
                <td><?= htmlspecialchars($nd['email']) ?></td>
                <td align="center"><?= htmlspecialchars($nd['sodienthoai'] ?? '') ?></td>
                <td align="center">
                    <b><?= strtoupper(htmlspecialchars($nd['role'])) ?></b>
                </td>
                <td><?= htmlspecialchars($nd['chucvu'] ?? 'N/A') ?></td>
                <td align="right"><?= $nd['luong'] ? number_format($nd['luong'], 0, ',', '.') . ' đ' : 'N/A' ?></td>
                <td align="center"><?= number_format($nd['diemtichluy'] ?? 0) ?></td>
                <td align="center">
                    <a href="index.php?controller=nguoidung&action=edit&id=<?= $nd['id'] ?>">[Sửa]</a> |
                    <a href="index.php?controller=nguoidung&action=delete&id=<?= $nd['id'] ?>">[Xóa]</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" align="center">Không có người dùng nào.</td>
        </tr>
    <?php endif; ?>
</table>



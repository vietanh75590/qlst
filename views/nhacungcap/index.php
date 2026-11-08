<?php
require_once 'views/layout/header.php';
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>

<h2>QUẢN LÝ NHÀ CUNG CẤP</h2>

<table border="0" width="100%">
    <tr>
        <td>Danh sách các đối tác cung cấp hàng hóa cho siêu thị</td>
        <td align="right">
            <?php if ($isAdmin): ?>
                <a href="index.php?controller=nhacungcap&action=create"><b>+ Thêm nhà cung cấp mới</b></a>
            <?php endif; ?>
        </td>
    </tr>
</table>

<br>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th width="12%">Mã NCC</th>
        <th width="33%">Tên nhà cung cấp</th>
        <th width="15%">Số điện thoại</th>
        <th>Địa chỉ</th>
        <?php if ($isAdmin): ?>
            <th width="15%">Thao tác</th>
        <?php endif; ?>
    </tr>
    <?php if (!empty($nhacungcaps)): ?>
        <?php foreach ($nhacungcaps as $ncc): ?>
            <tr>
                <td align="center"><b><?= $ncc['id'] ?></b></td>
                <td><b><?= htmlspecialchars($ncc['tennhacungcap']) ?></b></td>
                <td><?= htmlspecialchars($ncc['sodienthoai'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($ncc['diachi'] ?? '') ?></td>
                <?php if ($isAdmin): ?>
                    <td align="center">
                        <a href="index.php?controller=nhacungcap&action=edit&id=<?= $ncc['id'] ?>">Sửa</a> |
                        <a href="index.php?controller=nhacungcap&action=delete&id=<?= $ncc['id'] ?>">Xóa</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="<?= $isAdmin ? 5 : 4 ?>" align="center">Không có nhà cung cấp nào.</td>
        </tr>
    <?php endif; ?>
</table>



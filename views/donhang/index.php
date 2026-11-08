<?php
require_once 'views/layout/header.php';
$userRole = strtolower($_SESSION['user_role'] ?? 'guest');
$isAdminOrStaff = in_array($userRole, ['admin', 'staff']);
?>

<h2><?= ($userRole === 'customer') ? 'LỊCH SỬ ĐƠN HÀNG CỦA BẠN' : 'QUẢN LÝ BÁN HÀNG & ĐƠN HÀNG' ?></h2>

<table border="0" width="100%">
    <tr>
        <td>
            <?= ($userRole === 'customer') ? 'Danh sách các đơn hàng bạn đã mua tại siêu thị:' : 'Danh sách các giao dịch bán hàng tại siêu thị:' ?>
        </td>
        <td align="right">
            <?php if ($isAdminOrStaff): ?>
                <a href="index.php?controller=donhang&action=create"><b>+ LẬP ĐƠN BÁN HÀNG MỚI</b></a>
            <?php elseif ($userRole === 'customer'): ?>
                <a href="index.php?controller=donhang&action=create"><b>+ ĐẶT MUA HÀNG MỚI</b></a>
            <?php endif; ?>
        </td>
    </tr>
</table>

<br>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th>Mã đơn</th>
        <th>Khách hàng</th>
        <th>Người nhận</th>
        <th>SĐT</th>
        <th>Địa chỉ</th>
        <th>Tổng tiền</th>
        <th>Ngày tạo</th>
        <th>Thao tác</th>
    </tr>
    <?php if (!empty($donhangs)): ?>
        <?php foreach ($donhangs as $dh): ?>
            <tr align="center">
                <td><b><?= $dh['id'] ?></b></td>
                <td><?= htmlspecialchars($dh['ten_khachhang'] ?? 'Khách lẻ') ?></td>
                <td><?= htmlspecialchars($dh['ten_nguoinhan']) ?></td>
                <td><?= htmlspecialchars($dh['sdt_nguoinhan']) ?></td>
                <td align="left"><?= htmlspecialchars($dh['diachi']) ?></td>
                <td align="right"><b><?= number_format($dh['tongtien']) ?> đ</b></td>
                <td><?= date('d/m/Y H:i', strtotime($dh['created_at'])) ?></td>
                <td>
                    <a href="index.php?controller=donhang&action=view&id=<?= $dh['id'] ?>">[Xem chi tiết / In]</a>
                    <?php if ($isAdminOrStaff): ?>
                        | <a href="index.php?controller=donhang&action=delete&id=<?= $dh['id'] ?>">[Xóa]</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" align="center">
                <?= ($userRole === 'customer') ? 'Bạn chưa có đơn hàng nào trong lịch sử mua sắm.' : 'Chưa có đơn hàng nào.' ?>
            </td>
        </tr>
    <?php endif; ?>
</table>


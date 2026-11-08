<?php
/**
 * Danh sách phiếu nhập hàng - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>QUẢN LÝ NHẬP HÀNG VÀO KHO</h2></td>
        <td align="right">
            <a href="index.php?controller=phieunhap&action=create"><b>+ LẬP PHIẾU NHẬP MỚI</b></a>
        </td>
    </tr>
</table>

<?php if (isset($_GET['msg'])): ?>
    <p>
        <?php
        if ($_GET['msg'] === 'created') echo '<font color="green"><b>[Thành công]: Phiếu nhập đã tạo thành công và kho hàng đã được cộng dồn!</b></font>';
        if ($_GET['msg'] === 'deleted') echo '<font color="red"><b>[Thông báo]: Đã xóa phiếu nhập!</b></font>';
        ?>
    </p>
    <hr>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f0f0f0">
        <th align="center">Mã phiếu</th>
        <th align="left">Nhà cung cấp</th>
        <th align="left">Nhân viên lập phiếu</th>
        <th align="center">Ngày nhập</th>
        <th align="right">Tổng tiền nhập</th>
        <th align="center">Thao tác</th>
    </tr>
    <?php if (!empty($phieunhaps)): ?>
        <?php foreach ($phieunhaps as $pn): ?>
            <tr>
                <td align="center"><b>PN-<?= $pn['id'] ?></b></td>
                <td><?= htmlspecialchars($pn['tennhacungcap']) ?></td>
                <td><?= htmlspecialchars($pn['tennhanvien'] ?? 'NV01') ?></td>
                <td align="center"><?= date('d/m/Y H:i', strtotime($pn['ngaynhap'])) ?></td>
                <td align="right"><b><?= number_format($pn['tongtien']) ?> đ</b></td>
                <td align="center">
                    <a href="index.php?controller=phieunhap&action=view&id=<?= $pn['id'] ?>">[Xem chi tiết]</a> |
                    <a href="index.php?controller=phieunhap&action=delete&id=<?= $pn['id'] ?>">[Xóa]</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" align="center">Chưa có phiếu nhập hàng nào.</td>
        </tr>
    <?php endif; ?>
</table>

<?php
/**
 * Chi tiết phiếu nhập hàng - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>CHI TIẾT PHIẾU NHẬP #<?= $phieunhap['id'] ?></h2></td>
        <td align="right">
            <button onclick="window.print()">In phiếu nhập</button> | 
            <a href="index.php?controller=phieunhap&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f0f0f0">
        <th colspan="2" align="left">THÔNG TIN PHIẾU NHẬP</th>
    </tr>
    <tr>
        <td width="30%"><b>Mã phiếu:</b></td>
        <td>PN-<?= $phieunhap['id'] ?></td>
    </tr>
    <tr>
        <td><b>Nhà cung cấp:</b></td>
        <td><?= htmlspecialchars($phieunhap['tennhacungcap']) ?></td>
    </tr>
    <tr>
        <td><b>Người lập phiếu:</b></td>
        <td><?= htmlspecialchars($phieunhap['tennhanvien'] ?? 'NV01') ?></td>
    </tr>
    <tr>
        <td><b>Thời gian lập:</b></td>
        <td><?= date('d/m/Y H:i:s', strtotime($phieunhap['ngaynhap'])) ?></td>
    </tr>
    <tr>
        <td><b>Tổng tiền nhập:</b></td>
        <td><b><?= number_format($phieunhap['tongtien']) ?> đ</b></td>
    </tr>
</table>

<br>

<h3>DANH SÁCH MẶT HÀNG TRONG PHIẾU</h3>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f0f0f0">
        <th align="center" width="50">STT</th>
        <th align="center" width="100">Mã SP</th>
        <th align="left">Tên sản phẩm</th>
        <th align="center" width="100">Đơn vị</th>
        <th align="center" width="150">Số lượng nhập</th>
        <th align="right" width="150">Đơn giá nhập</th>
        <th align="right" width="180">Thành tiền</th>
    </tr>
    <?php 
    $stt = 1;
    $calcTotal = 0;
    if (!empty($chitiet)):
        foreach ($chitiet as $ct): 
            $thanhtien = $ct['soluongnhap'] * $ct['gianhap'];
            $calcTotal += $thanhtien;
    ?>
        <tr>
            <td align="center"><?= $stt++ ?></td>
            <td align="center"><?= $ct['id_sp'] ?></td>
            <td><?= htmlspecialchars($ct['tensp'] ?? $ct['id_sp']) ?></td>
            <td align="center"><?= htmlspecialchars($ct['donvi'] ?? 'Hộp') ?></td>
            <td align="center">+<?= $ct['soluongnhap'] ?></td>
            <td align="right"><?= number_format($ct['gianhap']) ?> đ</td>
            <td align="right"><b><?= number_format($thanhtien) ?> đ</b></td>
        </tr>
    <?php 
        endforeach;
    else: 
    ?>
        <tr>
            <td colspan="7" align="center">Không có chi tiết mặt hàng.</td>
        </tr>
    <?php endif; ?>
    <tr bgcolor="#f0f0f0">
        <td colspan="6" align="right"><b>TỔNG CỘNG TIỀN HÀNG:</b></td>
        <td align="right"><b><?= number_format($calcTotal) ?> đ</b></td>
    </tr>
</table>


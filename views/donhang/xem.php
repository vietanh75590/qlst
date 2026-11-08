<?php
require_once 'views/layout/header.php';
?>

<h2>CHI TIẾT ĐƠN HÀNG #<?= $donhang['id'] ?></h2>
<p>
    <a href="index.php?controller=donhang&action=index">&laquo; Quay lại danh sách đơn hàng</a> |
    <button onclick="window.print()">In hóa đơn này</button>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th colspan="2">THÔNG TIN GIAO DỊCH</th>
    </tr>
    <tr>
        <td width="25%"><b>Mã đơn hàng:</b></td>
        <td><?= $donhang['id'] ?></td>
    </tr>
    <tr>
        <td><b>Khách hàng:</b></td>
        <td><?= htmlspecialchars($donhang['ten_khachhang'] ?? 'Khách vãng lai') ?></td>
    </tr>
    <tr>
        <td><b>Người nhận hàng:</b></td>
        <td><?= htmlspecialchars($donhang['ten_nguoinhan']) ?></td>
    </tr>
    <tr>
        <td><b>Số điện thoại:</b></td>
        <td><?= htmlspecialchars($donhang['sdt_nguoinhan']) ?></td>
    </tr>
    <tr>
        <td><b>Địa chỉ giao nhận:</b></td>
        <td><?= htmlspecialchars($donhang['diachi']) ?></td>
    </tr>
    <tr>
        <td><b>Ngày tạo đơn:</b></td>
        <td><?= date('d/m/Y H:i:s', strtotime($donhang['created_at'])) ?></td>
    </tr>
    <tr>
        <td><b>TỔNG THANH TOÁN:</b></td>
        <td><h3><font color="red"><?= number_format($donhang['tongtien']) ?> VNĐ</font></h3></td>
    </tr>
</table>

<br>

<h3>CÁC SẢN PHẨM TRONG ĐƠN HÀNG</h3>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th>STT</th>
        <th>Mã SP</th>
        <th>Tên sản phẩm</th>
        <th>Đơn vị</th>
        <th>Số lượng mua</th>
        <th>Đơn giá</th>
        <th>Thành tiền</th>
    </tr>
    <?php 
    $stt = 1;
    $total = 0;
    if (!empty($items)):
        foreach ($items as $item):
            $sub = $item['soluong'] * $item['gia'];
            $total += $sub;
    ?>
        <tr align="center">
            <td><?= $stt++ ?></td>
            <td><?= $item['id_sp'] ?></td>
            <td align="left"><b><?= htmlspecialchars($item['tensp'] ?? $item['id_sp']) ?></b></td>
            <td><?= htmlspecialchars($item['donvi'] ?? 'Hộp') ?></td>
            <td><b><?= $item['soluong'] ?></b></td>
            <td align="right"><?= number_format($item['gia']) ?> đ</td>
            <td align="right"><b><?= number_format($sub) ?> đ</b></td>
        </tr>
    <?php 
        endforeach;
    endif;
    ?>
    <tr bgcolor="#f9f9f9">
        <td colspan="6" align="right"><b>TỔNG CỘNG:</b></td>
        <td align="right"><h3><font color="red"><?= number_format($total) ?> đ</font></h3></td>
    </tr>
</table>



<?php
require_once 'views/layout/header.php';
$isCustomer = ($userRole === 'customer');
$selectedSpId = $_GET['id_sp'] ?? '';
?>

<h2><?= $isCustomer ? 'ĐẶT MUA SẢN PHẨM TRỰC TUYẾN' : 'LẬP ĐƠN HÀNG MỚI (BÁN HÀNG TẠI QUẦY)' ?></h2>
<p>
    <a href="index.php?controller=donhang&action=index">&laquo; <?= $isCustomer ? 'Lịch sử đơn hàng của bạn' : 'Danh sách đơn hàng' ?></a> | 
    <a href="index.php?controller=sanpham&action=index">Danh mục sản phẩm</a>
</p>

<form action="index.php?controller=donhang&action=create" method="POST">
    <h3>1. THÔNG TIN KHÁCH HÀNG & GIAO HÀNG</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <?php if (!$isCustomer): ?>
            <tr>
                <td width="25%"><b>Khách hàng thành viên:</b></td>
                <td>
                    <select name="id_nguoidung" id="selectKhach" onchange="chonKhachHang()">
                        <option value="">-- Khách vãng lai / Mua lẻ --</option>
                        <?php foreach ($nguoidungs as $u): ?>
                            <option value="<?= $u['id_khachhang'] ?: $u['id'] ?>" data-ten="<?= htmlspecialchars($u['hoten']) ?>" data-sdt="<?= htmlspecialchars($u['sodienthoai'] ?? '') ?>" data-diachi="<?= htmlspecialchars($u['diachi'] ?? '') ?>">
                                <?= htmlspecialchars($u['hoten']) ?> (<?= $u['email'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td width="25%"><b>Tài khoản mua hàng:</b></td>
                <td>
                    <b><?= htmlspecialchars($thongTinKhach['hoten'] ?? $userName) ?></b> (<?= htmlspecialchars($thongTinKhach['email'] ?? $_SESSION['user_email'] ?? '') ?>)
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><b>Tên người nhận (*):</b></td>
            <td>
                <input type="text" name="ten_nguoinhan" id="tenNhan" size="40" required 
                       value="<?= htmlspecialchars($thongTinKhach['hoten'] ?? ($isCustomer ? $userName : '')) ?>" 
                       placeholder="Nhập họ và tên người nhận">
            </td>
        </tr>
        <tr>
            <td><b>Số điện thoại nhận hàng (*):</b></td>
            <td>
                <input type="text" name="sdt_nguoinhan" id="sdtNhan" size="25" required 
                       value="<?= htmlspecialchars($thongTinKhach['sodienthoai'] ?? '') ?>" 
                       placeholder="VD: 0912345678">
            </td>
        </tr>
        <tr>
            <td><b>Địa chỉ giao hàng (*):</b></td>
            <td>
                <input type="text" name="diachi" id="diachiNhan" size="60" required 
                       value="<?= htmlspecialchars($thongTinKhach['diachi'] ?? ($isCustomer ? '' : 'Mua tại quầy siêu thị')) ?>" 
                       placeholder="Nhập số nhà, đường, quận/huyện...">
            </td>
        </tr>
    </table>

    <br>

    <h3>2. CHỌN MẶT HÀNG CẦN MUA</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" id="bangSanPham">
        <tr bgcolor="#f2f2f2">
            <th width="45%">Sản phẩm</th>
            <th width="15%">Số lượng mua</th>
            <th width="20%">Đơn giá (đ)</th>
            <th width="15%">Thành tiền</th>
            <th width="5%">Xóa</th>
        </tr>
        <tbody id="thanBang">
            <tr>
                <td>
                    <select name="id_sp[]" required onchange="capNhatGia(this)">
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php foreach ($sanphams as $sp): ?>
                            <?php $selected = ($sp['id'] === $selectedSpId) ? 'selected' : ''; ?>
                            <option value="<?= $sp['id'] ?>" data-gia="<?= $sp['giasp'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($sp['tensp']) ?> (Kho: <?= $sp['tonkho'] ?> <?= $sp['donvi'] ?>) - <?= number_format($sp['giasp']) ?> đ
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td align="center">
                    <input type="number" name="soluong[]" value="1" min="1" size="5" required onchange="tinhTongTien()" onkeyup="tinhTongTien()">
                </td>
                <td align="right">
                    <input type="number" name="gia[]" value="0" readonly size="10">
                </td>
                <td align="right" class="oThanhTien"><b>0 đ</b></td>
                <td align="center">
                    <button type="button" onclick="xoaDong(this)">X</button>
                </td>
            </tr>
        </tbody>
    </table>

    <br>
    <button type="button" onclick="themDong()">+ Thêm mặt hàng khác</button>

    <br><br>
    <table border="1" cellpadding="10" cellspacing="0" width="100%" bgcolor="#f9f9f9">
        <tr>
            <td align="right" width="75%"><h3>TỔNG CỘNG TIỀN THANH TOÁN:</h3></td>
            <td align="right"><h2 id="tongTienHienThi"><font color="red">0 đ</font></h2></td>
        </tr>
    </table>

    <br>
    <input type="submit" value="<?= $isCustomer ? 'XÁC NHẬN ĐẶT MUA HÀNG' : 'XÁC NHẬN TẠO ĐƠN & THANH TOÁN' ?>" style="padding: 10px 25px; font-weight: bold; cursor: pointer;">
</form>

<script>
function chonKhachHang() {
    var sel = document.getElementById('selectKhach');
    if (!sel) return;
    var opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('tenNhan').value = opt.getAttribute('data-ten') || '';
        document.getElementById('sdtNhan').value = opt.getAttribute('data-sdt') || '';
        document.getElementById('diachiNhan').value = opt.getAttribute('data-diachi') || 'Mua tại quầy siêu thị';
    }
}

function capNhatGia(sel) {
    var tr = sel.closest('tr');
    var opt = sel.options[sel.selectedIndex];
    var gia = opt ? (parseFloat(opt.getAttribute('data-gia')) || 0) : 0;
    tr.querySelector('input[name="gia[]"]').value = gia;
    tinhTongTien();
}

function themDong() {
    var tbody = document.getElementById('thanBang');
    var dongDau = tbody.querySelector('tr');
    var dongMoi = dongDau.cloneNode(true);
    dongMoi.querySelector('select').selectedIndex = 0;
    dongMoi.querySelector('input[name="soluong[]"]').value = 1;
    dongMoi.querySelector('input[name="gia[]"]').value = 0;
    dongMoi.querySelector('.oThanhTien').innerHTML = '<b>0 đ</b>';
    tbody.appendChild(dongMoi);
    tinhTongTien();
}

function xoaDong(btn) {
    var tbody = document.getElementById('thanBang');
    if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
        tinhTongTien();
    } else {
        alert('Đơn hàng cần có ít nhất 1 sản phẩm!');
    }
}

function tinhTongTien() {
    var total = 0;
    var rows = document.querySelectorAll('#thanBang tr');
    rows.forEach(function(row) {
        var sl = parseFloat(row.querySelector('input[name="soluong[]"]').value) || 0;
        var gia = parseFloat(row.querySelector('input[name="gia[]"]').value) || 0;
        var sub = sl * gia;
        row.querySelector('.oThanhTien').innerHTML = '<b>' + sub.toLocaleString('vi-VN') + ' đ</b>';
        total += sub;
    });
    document.getElementById('tongTienHienThi').innerHTML = '<font color="red">' + total.toLocaleString('vi-VN') + ' đ</font>';
}

// Tự động tính giá ban đầu nếu có sản phẩm được chọn sẵn
document.addEventListener('DOMContentLoaded', function() {
    var firstSelect = document.querySelector('#thanBang select');
    if (firstSelect && firstSelect.selectedIndex > 0) {
        capNhatGia(firstSelect);
    }
});
</script>

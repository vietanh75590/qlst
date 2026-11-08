<?php
/**
 * Lập phiếu nhập hàng - Thuần HTML không CSS
 */
require_once 'views/layout/header.php';
?>

<table border="0" width="100%">
    <tr>
        <td><h2>LẬP PHIẾU NHẬP HÀNG MỚI</h2></td>
        <td align="right">
            <a href="index.php?controller=phieunhap&action=index">[Quay lại danh sách]</a>
        </td>
    </tr>
</table>
<hr>

<form action="index.php?controller=phieunhap&action=create" method="POST" id="importForm">
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr bgcolor="#f0f0f0">
            <th colspan="2" align="left">1. THÔNG TIN CHUNG PHIẾU NHẬP</th>
        </tr>
        <tr>
            <td width="30%"><b>Nhà cung cấp (*):</b></td>
            <td>
                <select name="id_nhacungcap" required>
                    <option value="">-- Chọn nhà cung cấp --</option>
                    <?php foreach ($nhacungcaps as $ncc): ?>
                        <option value="<?= $ncc['id'] ?>"><?= htmlspecialchars($ncc['tennhacungcap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Nhân viên lập phiếu:</b></td>
            <td>
                <input type="text" value="<?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>" readonly>
            </td>
        </tr>
        <tr>
            <td><b>Ngày nhập hàng:</b></td>
            <td>
                <input type="date" name="ngaynhap" value="<?= date('Y-m-d') ?>" required>
            </td>
        </tr>
    </table>

    <br>

    <table border="1" cellpadding="8" cellspacing="0" width="100%" id="importTable">
        <tr bgcolor="#f0f0f0">
            <th colspan="5" align="left">
                2. DANH SÁCH MẶT HÀNG NHẬP VÀO KHO
                <button type="button" onclick="addImportRow()" style="float: right;">+ Thêm hàng</button>
            </th>
        </tr>
        <tr bgcolor="#fafafa">
            <th align="left">Sản phẩm</th>
            <th align="center" width="150">Số lượng nhập</th>
            <th align="right" width="180">Đơn giá nhập (VNĐ)</th>
            <th align="right" width="180">Thành tiền</th>
            <th align="center" width="80">Xóa</th>
        </tr>
        <tbody id="importBody">
            <tr>
                <td>
                    <select name="id_sp[]" required>
                        <option value="">-- Chọn mặt hàng --</option>
                        <?php foreach ($sanphams as $sp): ?>
                            <option value="<?= $sp['id'] ?>">
                                <?= htmlspecialchars($sp['tensp']) ?> (Kho hiện có: <?= $sp['tonkho'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td align="center">
                    <input type="number" name="soluongnhap[]" class="import-qty" value="10" min="1" required onchange="calculateImportTotal()" onkeyup="calculateImportTotal()">
                </td>
                <td align="right">
                    <input type="number" name="gianhap[]" class="import-price" value="50000" min="0" required onchange="calculateImportTotal()" onkeyup="calculateImportTotal()">
                </td>
                <td align="right">
                    <b class="import-subtotal">500,000 đ</b>
                </td>
                <td align="center">
                    <button type="button" onclick="removeImportRow(this)">X</button>
                </td>
            </tr>
        </tbody>
        <tr bgcolor="#f0f0f0">
            <td colspan="3" align="right"><b>TỔNG TIỀN PHIẾU NHẬP:</b></td>
            <td align="right"><b id="totalImportDisplay">500,000 đ</b></td>
            <td></td>
        </tr>
    </table>

    <br>
    <button type="button" onclick="addImportRow()">+ Thêm mặt hàng khác</button>
    <br><br>
    <input type="submit" value="LƯU PHIẾU NHẬP & CẬP NHẬT KHO">
</form>

<script>
function addImportRow() {
    const tbody = document.getElementById('importBody');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelector('select').selectedIndex = 0;
    newRow.querySelector('.import-qty').value = 10;
    newRow.querySelector('.import-price').value = 50000;
    newRow.querySelector('.import-subtotal').textContent = '500,000 đ';
    tbody.appendChild(newRow);
    calculateImportTotal();
}

function removeImportRow(btn) {
    const tbody = document.getElementById('importBody');
    if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
        calculateImportTotal();
    } else {
        alert('Phiếu nhập phải có ít nhất 1 mặt hàng!');
    }
}

function calculateImportTotal() {
    let total = 0;
    const rows = document.querySelectorAll('#importBody tr');
    rows.forEach(row => {
        const qty = parseFloat(row.querySelector('.import-qty').value) || 0;
        const price = parseFloat(row.querySelector('.import-price').value) || 0;
        const sub = qty * price;
        row.querySelector('.import-subtotal').textContent = sub.toLocaleString('vi-VN') + ' đ';
        total += sub;
    });
    document.getElementById('totalImportDisplay').textContent = total.toLocaleString('vi-VN') + ' đ';
}
calculateImportTotal();
</script>

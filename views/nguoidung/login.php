<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Quản Lý Siêu Thị</title>
</head>
<body>
    <h1>ĐĂNG NHẬP HỆ THỐNG QUẢN LÝ SIÊU THỊ</h1>
    <hr>

    <?php if (!empty($error)): ?>
        <p><font color="red"><b>[Lỗi]: <?= htmlspecialchars($error) ?></b></font></p>
        <hr>
    <?php endif; ?>

    <form action="index.php?controller=nguoidung&action=login" method="POST">
        <table border="1" cellpadding="8" cellspacing="0">
            <tr bgcolor="#f0f0f0">
                <th colspan="2" align="left">Thông tin đăng nhập</th>
            </tr>
            <tr>
                <td><b>Email:</b></td>
                <td><input type="email" name="email" placeholder="admin@sieuthi.vn" required autofocus size="35"></td>
            </tr>
            <tr>
                <td><b>Mật khẩu:</b></td>
                <td><input type="password" name="matkhau" placeholder="Nhập mật khẩu" required size="35"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="ĐĂNG NHẬP">
                </td>
            </tr>
        </table>
    </form>

    <br>
    <fieldset style="display: inline-block;">
        <legend><b>Tài khoản mẫu đăng nhập thử nghiệm:</b></legend>
        <ul>
            <li><b>Quản trị viên (Admin):</b> admin@sieuthi.vn | Mật khẩu: 123456</li>
            <li><b>Nhân viên (Staff):</b> banhang@sieuthi.vn | Mật khẩu: 123456</li>
            <li><b>Khách hàng:</b> tuanpham@gmail.com | Mật khẩu: 123456</li>
        </ul>
    </fieldset>

    <br><br>
    <a href="index.php?controller=dashboard&action=index">&larr; Xem trang chủ hệ thống</a>
</body>
</html>

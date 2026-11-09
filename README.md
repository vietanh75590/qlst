# Quản lý siêu thị

Cần cài Docker và Docker Compose. Đặt tệp `databasechinh.sql` trên máy của bạn rồi chạy từ thư mục repository:

```sh
./run.sh /đường/dẫn/databasechinh.sql
```

Mở `http://127.0.0.1:8000/index.php?controller=nguoidung&action=login` trong trình duyệt. Script chỉ nhập SQL khi cơ sở dữ liệu Docker còn trống; các lần chạy sau dùng lại dữ liệu trong Docker volume. Dùng `./stop.sh` để dừng và `./run.sh` để khởi động lại. Tệp SQL nguồn không bị thay đổi.

Ứng dụng dùng mật khẩu MySQL trong `config/Database.php`; `run.sh` chuyển cùng giá trị đó cho container MySQL. Trên máy có dịch vụ khác dùng cổng 8000, hãy đổi cổng bên trái trong `compose.yaml`.

Tài khoản trong dữ liệu mẫu: `TK01` quản trị, `TK02` nhân viên kho, `TK03` nhân viên bán hàng. Thông tin đăng nhập xem trong dữ liệu mẫu. Quyền nhân viên được nhận diện qua `nhanvien.chucvu`; hãy đăng xuất và đăng nhập lại sau khi đổi chức vụ.

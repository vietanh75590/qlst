<?php
/**
 * ==============================================
 * Controller: Người Dùng (NguoiDungController)
 * ==============================================
 * Mô tả: Quản lý CRUD cho người dùng, đăng nhập, đăng xuất.
 *         Bao gồm phân quyền: admin, staff, customer.
 */

require_once 'models/NguoiDungModel.php';

class NguoiDungController
{
    private $nguoiDungModel;

    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
    }

    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     */
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']) || isset($_SESSION['user']['id']);
    }

    /**
     * Kiểm tra người dùng có phải admin không
     */
    private function isAdmin()
    {
        $role = $_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? '');
        return $role === 'admin';
    }

    /**
     * Yêu cầu đăng nhập - chuyển hướng nếu chưa đăng nhập
     */
    private function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: index.php?controller=nguoidung&action=login');
            exit;
        }
    }

    /**
     * Yêu cầu quyền admin
     */
    private function requireAdmin()
    {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            echo '<p><font color="red"><b>[Từ chối truy cập]: Bạn không có quyền truy cập trang này! Chỉ dành cho Quản trị viên (Admin).</b></font></p>';
            echo '<p><a href="index.php?controller=dashboard&action=index">&larr; Quay lại Trang chủ</a></p>';
            exit;
        }
    }

    /**
     * Đăng nhập
     * GET: Hiển thị form đăng nhập
     * POST: Xử lý xác thực
     */
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $matkhau = $_POST['matkhau'] ?? '';

            // Tìm người dùng theo email
            $user = $this->nguoiDungModel->getByEmail($email);

            if ($user && ($user['matkhau'] === $matkhau || password_verify($matkhau, $user['matkhau']))) {
                // Đăng nhập thành công - lưu thông tin vào session
                $_SESSION['user_id']      = $user['id'];
                $_SESSION['user_name']    = $user['hoten'];
                $_SESSION['user_email']   = $user['email'];
                $_SESSION['user_role']    = $user['role'];
                $_SESSION['id_khachhang'] = $user['id_khachhang'] ?? null;
                $_SESSION['id_nhanvien']  = $user['id_nhanvien'] ?? null;

                // Chuyển hướng theo vai trò
                if ($user['role'] === 'admin' || $user['role'] === 'staff') {
                    header('Location: index.php?controller=dashboard&action=index');
                } else {
                    header('Location: index.php?controller=sanpham&action=index');
                }
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng!';
            }
        }

        $title = 'Đăng nhập';
        require_once 'views/nguoidung/login.php';
    }

    /**
     * Đăng xuất - Hủy session và chuyển hướng về trang đăng nhập
     */
    public function logout()
    {
        // Xóa tất cả dữ liệu session
        session_unset();
        session_destroy();

        // Chuyển hướng về trang đăng nhập
        header('Location: index.php?controller=nguoidung&action=login');
        exit;
    }

    /**
     * Hiển thị danh sách người dùng (chỉ admin)
     */
    public function index()
    {
        $this->requireAdmin();

        $nguoidungs = $this->nguoiDungModel->getAll();
        $title = 'Danh sách người dùng';
        require_once 'views/nguoidung/index.php';
    }

    /**
     * Thêm người dùng mới (chỉ admin)
     * GET: Hiển thị form thêm
     * POST: Xử lý lưu người dùng
     */
    public function create()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'hoten'        => trim($_POST['hoten'] ?? ''),
                    'email'        => trim($_POST['email'] ?? ''),
                    'matkhau'      => password_hash($_POST['matkhau'] ?? '', PASSWORD_DEFAULT),
                    'sodienthoai'  => trim($_POST['sodienthoai'] ?? ''),
                    'cccd'         => trim($_POST['cccd'] ?? ''),
                    'diachi'       => trim($_POST['diachi'] ?? ''),
                    'role'         => $_POST['role'] ?? 'customer',
                    'diemtichluy'  => $_POST['diemtichluy'] ?? 0,
                    'chucvu'       => trim($_POST['chucvu'] ?? ''),
                    'luong'        => $_POST['luong'] ?? 0,
                ];

                $this->nguoiDungModel->create($data);
                $_SESSION['message'] = 'Đã thêm người dùng mới thành công!';
                header('Location: index.php?controller=nguoidung&action=index&msg=created');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi thêm người dùng: ' . $e->getMessage();
                header('Location: index.php?controller=nguoidung&action=create');
                exit;
            }
        }

        $title = 'Thêm người dùng mới';
        require_once 'views/nguoidung/them.php';
    }

    public function store()
    {
        $this->create();
    }

    /**
     * Sửa thông tin người dùng (chỉ admin)
     * GET: Hiển thị form sửa
     * POST: Xử lý cập nhật
     */
    public function edit($id = null)
    {
        $this->requireAdmin();

        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=nguoidung&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'hoten'        => trim($_POST['hoten'] ?? ''),
                'email'        => trim($_POST['email'] ?? ''),
                'sodienthoai'  => trim($_POST['sodienthoai'] ?? ''),
                'diachi'       => trim($_POST['diachi'] ?? ''),
                'role'         => $_POST['role'] ?? 'customer',
                'diemtichluy'  => $_POST['diemtichluy'] ?? 0,
                'chucvu'       => trim($_POST['chucvu'] ?? ''),
                'luong'        => $_POST['luong'] ?? 0,
            ];

            // Nếu có nhập mật khẩu mới thì cập nhật
            if (!empty($_POST['matkhau'])) {
                $data['matkhau'] = password_hash($_POST['matkhau'], PASSWORD_DEFAULT);
            }

            $this->nguoiDungModel->update($id, $data);

            header('Location: index.php?controller=nguoidung&action=index&msg=updated');
            exit;
        }

        // Lấy thông tin người dùng hiện tại
        $nguoidung = $this->nguoiDungModel->getById($id);
        if (!$nguoidung) {
            header('Location: index.php?controller=nguoidung&action=index&msg=notfound');
            exit;
        }

        $title = 'Sửa thông tin người dùng';
        require_once 'views/nguoidung/sua.php';
    }

    public function update($id = null)
    {
        $this->edit($id);
    }

    /**
     * Xóa người dùng theo ID (chỉ admin)
     */
    public function delete($id = null)
    {
        $this->requireAdmin();

        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=nguoidung&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->nguoiDungModel->delete($id);
            header('Location: index.php?controller=nguoidung&action=index&msg=deleted');
            exit;
        }

        $nguoidung = $this->nguoiDungModel->getById($id);
        if (!$nguoidung) {
            header('Location: index.php?controller=nguoidung&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa người dùng';
        require_once 'views/nguoidung/xoa.php';
    }
}

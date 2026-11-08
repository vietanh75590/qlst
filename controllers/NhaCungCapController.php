<?php
/**
 * Controller: Nhà Cung Cấp (NhaCungCapController)
 * Quản lý CRUD cho nhà cung cấp.
 * Phân quyền: Mọi người dùng có thể xem; Chỉ Admin mới có quyền Thêm, Sửa, Xóa.
 */

require_once 'models/NhaCungCapModel.php';

class NhaCungCapController
{
    private $nhaCungCapModel;

    public function __construct()
    {
        $this->nhaCungCapModel = new NhaCungCapModel();
    }

    private function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này! Chỉ Quản trị viên (Admin) mới có quyền Thêm / Sửa / Xóa Nhà cung cấp.';
            header('Location: index.php?controller=nhacungcap&action=index');
            exit;
        }
    }

    public function index()
    {
        $role = strtolower($_SESSION['user_role'] ?? 'guest');
        if ($role === 'customer') {
            echo '<p><font color="red"><b>[Từ chối truy cập]: Quyền Khách hàng (Customer) không có quyền truy cập trang Nhà cung cấp!</b></font></p>';
            echo '<p><a href="index.php?controller=sanpham&action=index">&larr; Quay lại danh mục sản phẩm</a></p>';
            exit;
        }

        $nhacungcaps = $this->nhaCungCapModel->getAll();
        $isAdmin = $this->isAdmin();
        $title = 'Danh sách nhà cung cấp';
        require_once 'views/nhacungcap/index.php';
    }

    public function create()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id'            => trim($_POST['id'] ?? ''),
                'tennhacungcap' => trim($_POST['tennhacungcap'] ?? ''),
                'sodienthoai'   => trim($_POST['sodienthoai'] ?? ''),
                'diachi'        => trim($_POST['diachi'] ?? ''),
            ];

            $this->nhaCungCapModel->create($data);
            $_SESSION['message'] = 'Thêm nhà cung cấp mới thành công!';
            header('Location: index.php?controller=nhacungcap&action=index&msg=created');
            exit;
        }

        $title = 'Thêm nhà cung cấp mới';
        require_once 'views/nhacungcap/them.php';
    }

    public function store()
    {
        $this->create();
    }

    public function edit($id = null)
    {
        $this->requireAdmin();

        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=nhacungcap&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tennhacungcap' => trim($_POST['tennhacungcap'] ?? ''),
                'sodienthoai'   => trim($_POST['sodienthoai'] ?? ''),
                'diachi'        => trim($_POST['diachi'] ?? ''),
            ];

            $this->nhaCungCapModel->update($id, $data);
            $_SESSION['message'] = 'Cập nhật nhà cung cấp thành công!';
            header('Location: index.php?controller=nhacungcap&action=index&msg=updated');
            exit;
        }

        $nhacungcap = $this->nhaCungCapModel->getById($id);
        if (!$nhacungcap) {
            $_SESSION['error'] = 'Không tìm thấy nhà cung cấp yêu cầu!';
            header('Location: index.php?controller=nhacungcap&action=index&msg=notfound');
            exit;
        }

        $title = 'Sửa nhà cung cấp';
        require_once 'views/nhacungcap/sua.php';
    }

    public function update($id = null)
    {
        $this->edit($id);
    }

    public function delete($id = null)
    {
        $this->requireAdmin();

        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=nhacungcap&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->nhaCungCapModel->delete($id);
            $_SESSION['message'] = 'Đã xóa nhà cung cấp!';
            header('Location: index.php?controller=nhacungcap&action=index&msg=deleted');
            exit;
        }

        $nhacungcap = $this->nhaCungCapModel->getById($id);
        if (!$nhacungcap) {
            $_SESSION['error'] = 'Không tìm thấy nhà cung cấp yêu cầu!';
            header('Location: index.php?controller=nhacungcap&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa nhà cung cấp';
        require_once 'views/nhacungcap/xoa.php';
    }
}

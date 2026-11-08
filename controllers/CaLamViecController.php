<?php
/**
 * Controller: Ca Làm Việc (CaLamViecController)
 * Phân quyền:
 * - Admin: Quản lý đầy đủ (xem, thêm, sửa, xóa)
 * - Staff: Được xem danh sách
 * - Customer: Không có quyền truy cập
 */

require_once 'models/CaLamViecModel.php';

class CaLamViecController
{
    private $caLamViecModel;

    public function __construct()
    {
        $role = strtolower($_SESSION['user_role'] ?? 'guest');
        if ($role === 'customer' || $role === 'guest') {
            echo '<p><font color="red"><b>[Từ chối truy cập]: Quyền Khách hàng (Customer) không có quyền truy cập trang Ca làm việc!</b></font></p>';
            echo '<p><a href="index.php?controller=sanpham&action=index">&larr; Quay lại danh mục sản phẩm</a></p>';
            exit;
        }

        $this->caLamViecModel = new CaLamViecModel();
    }

    private function requireAdmin()
    {
        $role = strtolower($_SESSION['user_role'] ?? '');
        if ($role !== 'admin') {
            header('Location: index.php?controller=calamviec&action=index&msg=forbidden');
            exit;
        }
    }

    public function index()
    {
        $calamviecs = $this->caLamViecModel->getAll();
        $title = 'Danh sách ca làm việc';
        require_once 'views/calamviec/index.php';
    }

    public function create()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tenca'      => trim($_POST['tenca'] ?? ''),
                'giobatdau'  => trim($_POST['giobatdau'] ?? ''),
                'gioketthuc' => trim($_POST['gioketthuc'] ?? ''),
                'mota'       => trim($_POST['mota'] ?? '')
            ];

            $this->caLamViecModel->create($data);
            header('Location: index.php?controller=calamviec&action=index&msg=created');
            exit;
        }

        $title = 'Thêm ca làm việc';
        require_once 'views/calamviec/them.php';
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
            header('Location: index.php?controller=calamviec&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tenca'      => trim($_POST['tenca'] ?? ''),
                'giobatdau'  => trim($_POST['giobatdau'] ?? ''),
                'gioketthuc' => trim($_POST['gioketthuc'] ?? ''),
                'mota'       => trim($_POST['mota'] ?? '')
            ];

            $this->caLamViecModel->update($id, $data);
            header('Location: index.php?controller=calamviec&action=index&msg=updated');
            exit;
        }

        $calamviec = $this->caLamViecModel->getById($id);
        if (!$calamviec) {
            header('Location: index.php?controller=calamviec&action=index&msg=notfound');
            exit;
        }

        $title = 'Sửa ca làm việc';
        require_once 'views/calamviec/sua.php';
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
            header('Location: index.php?controller=calamviec&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->caLamViecModel->delete($id);
            header('Location: index.php?controller=calamviec&action=index&msg=deleted');
            exit;
        }

        $calamviec = $this->caLamViecModel->getById($id);
        if (!$calamviec) {
            header('Location: index.php?controller=calamviec&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa ca làm việc';
        require_once 'views/calamviec/xoa.php';
    }
}

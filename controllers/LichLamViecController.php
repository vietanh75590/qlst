<?php
/**
 * ==============================================
 * Controller: Lịch Làm Việc (LichLamViecController)
 * ==============================================
 * Mô tả: Quản lý CRUD cho lịch làm việc của nhân viên.
 *         Gắn nhân viên với ca làm việc theo ngày.
 */

require_once 'models/LichLamViecModel.php';
require_once 'models/NguoiDungModel.php';
require_once 'models/CaLamViecModel.php';

class LichLamViecController
{
    private $lichLamViecModel;
    private $nguoiDungModel;
    private $caLamViecModel;

    public function __construct()
    {
        Access::requireRoles(['admin', 'sales', 'warehouse']);

        $this->lichLamViecModel = new LichLamViecModel();
        $this->nguoiDungModel = new NguoiDungModel();
        $this->caLamViecModel = new CaLamViecModel();
    }

    /**
     * Hiển thị danh sách lịch làm việc
     */
    public function index()
    {
        $lichlamviecs = $this->lichLamViecModel->getAll();
        if (Access::role() !== 'admin') {
            $employeeId = $_SESSION['id_nhanvien'] ?? null;
            $lichlamviecs = array_values(array_filter($lichlamviecs, static fn($item) => $employeeId && ($item['id_nhanvien'] ?? null) === $employeeId));
        }
        $title = 'Lịch làm việc';
        require_once 'views/lichlamviec/index.php';
    }

    /**
     * Thêm lịch làm việc mới
     * GET: Hiển thị form thêm
     * POST: Xử lý lưu lịch làm việc
     */
    public function create()
    {
        Access::requireRoles(['admin']);
        // Lấy danh sách nhân viên (role = staff hoặc admin) và ca làm việc
        $nhanviens = $this->nguoiDungModel->getStaff();
        $calamviecs = $this->caLamViecModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_nhanvien' => $_POST['id_nhanvien'] ?? null,
                'id_ca'       => $_POST['id_ca'] ?? null,
                'ngaylam'     => $_POST['ngaylam'] ?? date('Y-m-d'),
                'ghichu'      => trim($_POST['ghichu'] ?? ''),
            ];

            $this->lichLamViecModel->create($data);

            header('Location: index.php?controller=lichlamviec&action=index&msg=created');
            exit;
        }

        $title = 'Thêm lịch làm việc mới';
        require_once 'views/lichlamviec/them.php';
    }

    public function store()
    {
        $this->create();
    }

    /**
     * Sửa lịch làm việc
     * GET: Hiển thị form sửa với dữ liệu hiện tại
     * POST: Xử lý cập nhật
     */
    public function edit($id = null)
    {
        Access::requireRoles(['admin']);
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=lichlamviec&action=index');
            exit;
        }

        // Lấy danh sách nhân viên và ca làm việc cho dropdown
        $nhanviens = $this->nguoiDungModel->getStaff();
        $calamviecs = $this->caLamViecModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_nhanvien' => $_POST['id_nhanvien'] ?? null,
                'id_ca'       => $_POST['id_ca'] ?? null,
                'ngaylam'     => $_POST['ngaylam'] ?? date('Y-m-d'),
                'ghichu'      => trim($_POST['ghichu'] ?? ''),
            ];

            $this->lichLamViecModel->update($id, $data);

            header('Location: index.php?controller=lichlamviec&action=index&msg=updated');
            exit;
        }

        // Lấy thông tin lịch làm việc hiện tại
        $lichlamviec = $this->lichLamViecModel->getById($id);
        if (!$lichlamviec) {
            header('Location: index.php?controller=lichlamviec&action=index&msg=notfound');
            exit;
        }

        $title = 'Sửa lịch làm việc';
        require_once 'views/lichlamviec/sua.php';
    }

    public function update($id = null)
    {
        $this->edit($id);
    }

    /**
     * Xóa lịch làm việc theo ID
     */
    public function delete($id = null)
    {
        Access::requireRoles(['admin']);
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=lichlamviec&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->lichLamViecModel->delete($id);
            header('Location: index.php?controller=lichlamviec&action=index&msg=deleted');
            exit;
        }

        $lichlamviec = $this->lichLamViecModel->getById($id);
        if (!$lichlamviec) {
            header('Location: index.php?controller=lichlamviec&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa lịch làm việc';
        require_once 'views/lichlamviec/xoa.php';
    }
}

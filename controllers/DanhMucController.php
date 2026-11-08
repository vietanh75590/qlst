<?php
/**
 * Controller: Danh Mục Sản Phẩm (DanhMucController)
 * Quản lý CRUD cho danh mục sản phẩm.
 * Phân quyền: Mọi người dùng có thể xem; Chỉ Admin mới có quyền Thêm, Sửa, Xóa.
 */

require_once 'models/DanhMucModel.php';

class DanhMucController
{
    private $danhMucModel;

    public function __construct()
    {
        $this->danhMucModel = new DanhMucModel();
    }

    private function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            echo '<p><font color="red"><b>[Từ chối truy cập]: Bạn không có quyền thực hiện thao tác này! Chỉ Quản trị viên (Admin) mới có quyền Thêm / Sửa / Xóa Danh mục.</b></font></p>';
            echo '<p><a href="index.php?controller=danhmuc&action=index">&larr; Quay lại danh mục sản phẩm</a></p>';
            exit;
        }
    }

    public function index()
    {
        $danhmucs = $this->danhMucModel->getTree();
        $isAdmin = $this->isAdmin();
        $title = 'Danh sách danh mục sản phẩm';
        require_once 'views/danhmuc/index.php';
    }

    public function create()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id'              => trim($_POST['id'] ?? ''),
                'tendanhmuc'      => trim($_POST['tendanhmuc'] ?? ''),
                'mota'            => trim($_POST['mota'] ?? ''),
                'id_danhmuc_cha'  => !empty($_POST['id_danhmuc_cha']) ? $_POST['id_danhmuc_cha'] : null,
            ];

            $this->danhMucModel->create($data);
            $_SESSION['message'] = 'Thêm danh mục mới thành công!';
            header('Location: index.php?controller=danhmuc&action=index&msg=created');
            exit;
        }

        $danhmucs = $this->danhMucModel->getByParent(null);
        $title = 'Thêm danh mục mới';
        require_once 'views/danhmuc/them.php';
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
            header('Location: index.php?controller=danhmuc&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tendanhmuc'      => trim($_POST['tendanhmuc'] ?? ''),
                'mota'            => trim($_POST['mota'] ?? ''),
                'id_danhmuc_cha'  => !empty($_POST['id_danhmuc_cha']) ? $_POST['id_danhmuc_cha'] : null,
            ];

            $this->danhMucModel->update($id, $data);
            $_SESSION['message'] = 'Cập nhật danh mục thành công!';
            header('Location: index.php?controller=danhmuc&action=index&msg=updated');
            exit;
        }

        $danhmuc = $this->danhMucModel->getById($id);
        if (!$danhmuc) {
            $_SESSION['error'] = 'Không tìm thấy danh mục yêu cầu!';
            header('Location: index.php?controller=danhmuc&action=index&msg=notfound');
            exit;
        }

        $danhmucs = $this->danhMucModel->getByParent(null);
        $title = 'Sửa danh mục';
        require_once 'views/danhmuc/sua.php';
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
            header('Location: index.php?controller=danhmuc&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->danhMucModel->delete($id);
            $_SESSION['message'] = 'Đã xóa danh mục!';
            header('Location: index.php?controller=danhmuc&action=index&msg=deleted');
            exit;
        }

        $danhmuc = $this->danhMucModel->getById($id);
        if (!$danhmuc) {
            $_SESSION['error'] = 'Không tìm thấy danh mục yêu cầu!';
            header('Location: index.php?controller=danhmuc&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa danh mục';
        require_once 'views/danhmuc/xoa.php';
    }
}

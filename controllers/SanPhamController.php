<?php
/**
 * Controller: Sản Phẩm (SanPhamController)
 * Quản lý CRUD cho sản phẩm trong siêu thị
 */

require_once 'models/SanPhamModel.php';
require_once 'models/DanhMucModel.php';
require_once 'models/NhaCungCapModel.php';

class SanPhamController
{
    private $sanPhamModel;
    private $danhMucModel;
    private $nhaCungCapModel;

    public function __construct()
    {
        $action = $_GET['action'] ?? 'index';
        if (in_array($action, ['create', 'store', 'edit', 'update'], true)) {
            Access::requireRoles(['admin', 'warehouse']);
        } elseif ($action === 'delete') {
            Access::requireRoles(['admin']);
        }
        $this->sanPhamModel = new SanPhamModel();
        $this->danhMucModel = new DanhMucModel();
        $this->nhaCungCapModel = new NhaCungCapModel();
    }

    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $id_danhmuc = trim($_GET['id_danhmuc'] ?? '');

        if (!empty($keyword)) {
            $sanphams = $this->sanPhamModel->search($keyword);
        } elseif (!empty($id_danhmuc)) {
            $sanphams = $this->sanPhamModel->getByCategory($id_danhmuc);
        } else {
            $sanphams = $this->sanPhamModel->getAll();
        }

        $title = 'Quản lý Sản phẩm';
        require_once 'views/sanpham/index.php';
    }

    public function create()
    {
        Access::requireRoles(['admin', 'warehouse']);
        $danhmucs = $this->danhMucModel->getAll();
        $nhacungcaps = $this->nhaCungCapModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id'            => trim($_POST['id'] ?? ''),
                'id_danhmuc'    => $_POST['id_danhmuc'] ?? null,
                'id_nhacungcap' => $_POST['id_nhacungcap'] ?? null,
                'tensp'         => trim($_POST['tensp'] ?? ''),
                'giasp'         => (float)($_POST['giasp'] ?? 0),
                'donvi'         => trim($_POST['donvi'] ?? 'Hộp'),
                'tonkho'        => (int)($_POST['tonkho'] ?? 0),
                'hsd'           => !empty($_POST['hsd']) ? $_POST['hsd'] : null,
                'image'         => trim($_POST['image'] ?? ''),
                'mota'          => trim($_POST['mota'] ?? '')
            ];

            // Xử lý upload ảnh nếu có file được chọn
            if (!empty($_FILES['image_file']['name'])) {
                $uploadDir = 'assets/img/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['image_file']['name']);
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $fileName)) {
                    $data['image'] = $fileName;
                }
            }

            $this->sanPhamModel->create($data);
            header('Location: index.php?controller=sanpham&action=index&msg=created');
            exit;
        }

        $title = 'Thêm sản phẩm mới';
        require_once 'views/sanpham/them.php';
    }

    public function store()
    {
        $this->create();
    }

    public function edit($id = null)
    {
        Access::requireRoles(['admin', 'warehouse']);
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=sanpham&action=index');
            exit;
        }

        $sanpham = $this->sanPhamModel->getById($id);
        if (!$sanpham) {
            header('Location: index.php?controller=sanpham&action=index&msg=notfound');
            exit;
        }

        $danhmucs = $this->danhMucModel->getAll();
        $nhacungcaps = $this->nhaCungCapModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_danhmuc'    => $_POST['id_danhmuc'] ?? null,
                'id_nhacungcap' => $_POST['id_nhacungcap'] ?? null,
                'tensp'         => trim($_POST['tensp'] ?? ''),
                'giasp'         => (float)($_POST['giasp'] ?? 0),
                'donvi'         => trim($_POST['donvi'] ?? 'Hộp'),
                'tonkho'        => (int)($_POST['tonkho'] ?? 0),
                'hsd'           => !empty($_POST['hsd']) ? $_POST['hsd'] : null,
                'image'         => !empty($_POST['image']) ? trim($_POST['image']) : ($sanpham['image'] ?? ''),
                'mota'          => trim($_POST['mota'] ?? '')
            ];

            // Xử lý upload ảnh nếu có
            if (!empty($_FILES['image_file']['name'])) {
                $uploadDir = 'assets/img/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['image_file']['name']);
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $fileName)) {
                    $data['image'] = $fileName;
                }
            }

            $this->sanPhamModel->update($id, $data);
            header('Location: index.php?controller=sanpham&action=index&msg=updated');
            exit;
        }

        $title = 'Sửa sản phẩm';
        require_once 'views/sanpham/sua.php';
    }

    public function update($id = null)
    {
        $this->edit($id);
    }

    public function delete($id = null)
    {
        Access::requireRoles(['admin']);
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=sanpham&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->sanPhamModel->delete($id);
            header('Location: index.php?controller=sanpham&action=index&msg=deleted');
            exit;
        }

        $sanpham = $this->sanPhamModel->getById($id);
        if (!$sanpham) {
            header('Location: index.php?controller=sanpham&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa sản phẩm';
        require_once 'views/sanpham/xoa.php';
    }

    public function search()
    {
        $this->index();
    }
}

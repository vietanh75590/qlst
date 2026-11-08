<?php
/**
 * ==============================================
 * Controller: Phiếu Nhập (PhieuNhapController)
 * ==============================================
 * Mô tả: Quản lý phiếu nhập hàng: danh sách, xem chi tiết,
 *         tạo phiếu nhập mới và cập nhật tồn kho.
 */

require_once 'models/PhieuNhapModel.php';
require_once 'models/SanPhamModel.php';
require_once 'models/NhaCungCapModel.php';

class PhieuNhapController
{
    private $phieuNhapModel;
    private $sanPhamModel;
    private $nhaCungCapModel;

    public function __construct()
    {
        $role = strtolower($_SESSION['user_role'] ?? 'guest');
        if ($role === 'customer' || $role === 'guest') {
            echo '<p><font color="red"><b>[Từ chối truy cập]: Quyền Khách hàng (Customer) không có quyền truy cập trang Nhập kho!</b></font></p>';
            echo '<p><a href="index.php?controller=sanpham&action=index">&larr; Quay lại danh mục sản phẩm</a></p>';
            exit;
        }

        $this->phieuNhapModel = new PhieuNhapModel();
        $this->sanPhamModel = new SanPhamModel();
        $this->nhaCungCapModel = new NhaCungCapModel();
    }

    /**
     * Hiển thị danh sách tất cả phiếu nhập
     */
    public function index()
    {
        $phieunhaps = $this->phieuNhapModel->getAll();
        $title = 'Danh sách phiếu nhập hàng';
        require_once 'views/phieunhap/index.php';
    }

    /**
     * Xem chi tiết phiếu nhập (bao gồm các sản phẩm nhập)
     */
    public function view($id = null)
    {
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=phieunhap&action=index');
            exit;
        }

        // Lấy thông tin phiếu nhập
        $phieunhap = $this->phieuNhapModel->getById($id);
        if (!$phieunhap) {
            header('Location: index.php?controller=phieunhap&action=index&msg=notfound');
            exit;
        }

        // Lấy chi tiết các sản phẩm trong phiếu nhập
        $chitiet = $this->phieuNhapModel->getChiTiet($id);

        $title = 'Chi tiết phiếu nhập #' . $id;
        require_once 'views/phieunhap/xem.php';
    }

    /**
     * Tạo phiếu nhập mới
     * GET: Hiển thị form tạo phiếu nhập
     * POST: Xử lý lưu phiếu nhập, chi tiết và cập nhật tồn kho
     */
    public function create()
    {
        // Lấy danh sách nhà cung cấp và sản phẩm cho dropdown
        $nhacungcaps = $this->nhaCungCapModel->getAll();
        $sanphams = $this->sanPhamModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xác định mã nhân viên (nhanvien.id)
            $id_nhanvien = $_SESSION['id_nhanvien'] ?? null;
            if (!$id_nhanvien && !empty($_SESSION['user_id'])) {
                require_once 'models/NguoiDungModel.php';
                $ndModel = new NguoiDungModel();
                $u = $ndModel->getById($_SESSION['user_id']);
                $id_nhanvien = $u['id_nhanvien'] ?? null;
                if ($id_nhanvien) {
                    $_SESSION['id_nhanvien'] = $id_nhanvien;
                }
            }
            if (!$id_nhanvien) {
                $id_nhanvien = 'NV01';
            }

            // Thu thập thông tin phiếu nhập
            $phieuData = [
                'id_nhacungcap' => $_POST['id_nhacungcap'] ?? null,
                'id_nhanvien'   => $id_nhanvien,
                'ngaynhap'      => $_POST['ngaynhap'] ?? date('Y-m-d'),
                'tongtien'      => 0, // Sẽ tính sau
            ];

            // Thu thập chi tiết sản phẩm nhập
            $chitiet = [];
            $tongtien = 0;

            if (isset($_POST['id_sp']) && is_array($_POST['id_sp'])) {
                for ($i = 0; $i < count($_POST['id_sp']); $i++) {
                    if (!empty($_POST['id_sp'][$i]) && !empty($_POST['soluongnhap'][$i])) {
                        $soluongnhap = (int)$_POST['soluongnhap'][$i];
                        $gianhap = (float)$_POST['gianhap'][$i];
                        $thanhtien = $soluongnhap * $gianhap;
                        $tongtien += $thanhtien;

                        $chitiet[] = [
                            'id_sp'       => trim($_POST['id_sp'][$i]),
                            'soluongnhap' => $soluongnhap,
                            'gianhap'     => $gianhap,
                        ];
                    }
                }
            }

            // Cập nhật tổng tiền
            $phieuData['tongtien'] = $tongtien;

            // Lưu phiếu nhập, chi tiết và tự động cập nhật tồn kho sản phẩm trong model
            $result = $this->phieuNhapModel->createWithDetails($phieuData, $chitiet);

            if ($result) {
                header('Location: index.php?controller=phieunhap&action=index&msg=created');
            } else {
                $_SESSION['error'] = 'Lỗi: Không thể lưu phiếu nhập vào cơ sở dữ liệu! Vui lòng thử lại.';
                header('Location: index.php?controller=phieunhap&action=create');
            }
            exit;
        }

        $title = 'Tạo phiếu nhập hàng mới';
        require_once 'views/phieunhap/them.php';
    }

    /**
     * Xóa phiếu nhập theo ID
     */
    public function delete($id = null)
    {
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=phieunhap&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->phieuNhapModel->delete($id);
            header('Location: index.php?controller=phieunhap&action=index&msg=deleted');
            exit;
        }

        $phieunhap = $this->phieuNhapModel->getById($id);
        if (!$phieunhap) {
            header('Location: index.php?controller=phieunhap&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa phiếu nhập';
        require_once 'views/phieunhap/xoa.php';
    }
}

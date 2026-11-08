<?php
/**
 * Controller: Đơn Hàng (DonHangController)
 * Quản lý đơn hàng: danh sách, xem chi tiết, tạo đơn hàng, xóa.
 * Phân quyền:
 * - Admin/Staff: Quản lý toàn bộ đơn hàng, lập đơn hàng POS bán hàng, xóa.
 * - Customer: Chỉ được xem danh sách Lịch sử đơn hàng của chính mình và chi tiết đơn hàng của mình.
 */

require_once 'models/DonHangModel.php';
require_once 'models/SanPhamModel.php';
require_once 'models/NguoiDungModel.php';

class DonHangController
{
    private $donHangModel;
    private $sanPhamModel;
    private $nguoiDungModel;

    public function __construct()
    {
        $this->donHangModel = new DonHangModel();
        $this->sanPhamModel = new SanPhamModel();
        $this->nguoiDungModel = new NguoiDungModel();
    }

    private function getCustomerInfo()
    {
        $userRole = strtolower($_SESSION['user_role'] ?? 'guest');
        $userId = $_SESSION['user_id'] ?? null;
        $customerId = $_SESSION['id_khachhang'] ?? null;

        if ($userRole === 'customer' && !$customerId && $userId) {
            $user = $this->nguoiDungModel->getById($userId);
            $customerId = $user['id_khachhang'] ?? null;
            $_SESSION['id_khachhang'] = $customerId;
        }

        return [$userRole, $userId, $customerId];
    }

    /**
     * Hiển thị danh sách đơn hàng
     * Khách hàng: Chỉ xem đơn hàng của mình
     * Admin/Staff: Xem toàn bộ đơn hàng
     */
    public function index()
    {
        list($userRole, $userId, $customerId) = $this->getCustomerInfo();

        if ($userRole === 'customer') {
            $donhangs = $this->donHangModel->getByCustomer($customerId, $userId);
            $title = 'Lịch sử đơn hàng của bạn';
        } else {
            $donhangs = $this->donHangModel->getAll();
            $title = 'Danh sách đơn hàng';
        }

        require_once 'views/donhang/index.php';
    }

    /**
     * Xem chi tiết đơn hàng (bao gồm các sản phẩm trong đơn)
     */
    public function view($id = null)
    {
        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=donhang&action=index');
            exit;
        }

        list($userRole, $userId, $customerId) = $this->getCustomerInfo();

        // Lấy thông tin đơn hàng
        $donhang = $this->donHangModel->getById($id);
        if (!$donhang) {
            header('Location: index.php?controller=donhang&action=index&msg=notfound');
            exit;
        }

        // Khách hàng chỉ được xem chi tiết đơn hàng của chính mình
        if ($userRole === 'customer') {
            $isOwner = false;
            if ($customerId && $donhang['id_khachhang'] === $customerId) {
                $isOwner = true;
            }
            if (!$isOwner) {
                echo '<p><font color="red"><b>[Từ chối truy cập]: Bạn không có quyền xem đơn hàng của người khác!</b></font></p>';
                echo '<p><a href="index.php?controller=donhang&action=index">&larr; Quay lại lịch sử đơn hàng</a></p>';
                exit;
            }
        }

        // Lấy danh sách sản phẩm trong đơn hàng
        $items = $this->donHangModel->getItems($id);

        $title = 'Chi tiết đơn hàng #' . $id;
        require_once 'views/donhang/xem.php';
    }

    /**
     * Tạo đơn hàng mới / Đặt mua sản phẩm
     * Cho phép cả Admin/Staff (bán tại quầy) và Customer (đặt mua hàng online)
     */
    public function create()
    {
        list($userRole, $userId, $customerId) = $this->getCustomerInfo();

        // Lấy danh sách sản phẩm và người dùng cho dropdown
        $sanphams = $this->sanPhamModel->getAll();
        $nguoidungs = ($userRole === 'customer') ? [] : $this->nguoiDungModel->getAll();

        // Nếu là khách hàng, lấy thông tin cá nhân để điền sẵn vào form
        $thongTinKhach = null;
        if ($userRole === 'customer' && $userId) {
            $thongTinKhach = $this->nguoiDungModel->getById($userId);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idKhachHang = ($userRole === 'customer') ? ($customerId ?: 'KH01') : ($_POST['id_nguoidung'] ?? null);

            $donhangData = [
                'id_khachhang'   => $idKhachHang,
                'id_nguoidung'   => $idKhachHang,
                'ten_nguoinhan'  => trim($_POST['ten_nguoinhan'] ?? ''),
                'sdt_nguoinhan'  => trim($_POST['sdt_nguoinhan'] ?? ''),
                'diachi'         => trim($_POST['diachi'] ?? ''),
                'tongtien'       => 0,
            ];

            $items = [];
            $tongtien = 0;

            if (isset($_POST['id_sp']) && is_array($_POST['id_sp'])) {
                for ($i = 0; $i < count($_POST['id_sp']); $i++) {
                    if (!empty($_POST['id_sp'][$i]) && !empty($_POST['soluong'][$i])) {
                        $soluong = (int)$_POST['soluong'][$i];
                        $gia = (float)$_POST['gia'][$i];
                        $thanhtien = $soluong * $gia;
                        $tongtien += $thanhtien;

                        $items[] = [
                            'id_sp'   => trim($_POST['id_sp'][$i]),
                            'soluong' => $soluong,
                            'gia'     => $gia,
                        ];
                    }
                }
            }

            $donhangData['tongtien'] = $tongtien;
            $donHangId = $this->donHangModel->createWithItems($donhangData, $items);

            if ($donHangId) {
                $_SESSION['message'] = ($userRole === 'customer')
                    ? "Đặt hàng thành công! Đơn hàng #$donHangId đã được ghi nhận."
                    : "Đã tạo đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Không thể tạo đơn hàng! Vui lòng thử lại.";
            }

            header('Location: index.php?controller=donhang&action=index&msg=created');
            exit;
        }

        $title = ($userRole === 'customer') ? 'Đặt mua sản phẩm' : 'Tạo đơn hàng mới';
        require_once 'views/donhang/them.php';
    }

    /**
     * Xóa đơn hàng theo ID (Chỉ dành cho Admin/Staff)
     */
    public function delete($id = null)
    {
        $userRole = strtolower($_SESSION['user_role'] ?? 'guest');
        if ($userRole === 'customer') {
            echo '<p><font color="red"><b>[Từ chối truy cập]: Khách hàng không có quyền xóa đơn hàng!</b></font></p>';
            echo '<p><a href="index.php?controller=donhang&action=index">&larr; Quay lại lịch sử đơn hàng</a></p>';
            exit;
        }

        $id = $id ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: index.php?controller=donhang&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->donHangModel->delete($id);
            header('Location: index.php?controller=donhang&action=index&msg=deleted');
            exit;
        }

        $donhang = $this->donHangModel->getById($id);
        if (!$donhang) {
            header('Location: index.php?controller=donhang&action=index&msg=notfound');
            exit;
        }

        $title = 'Xóa đơn hàng';
        require_once 'views/donhang/xoa.php';
    }
}

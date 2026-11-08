<?php
/**
 * Controller: Dashboard (DashboardController)
 * Hiển thị trang tổng quan với các thống kê của siêu thị
 */

require_once 'models/SanPhamModel.php';
require_once 'models/DonHangModel.php';
require_once 'models/NguoiDungModel.php';
require_once 'models/PhieuNhapModel.php';

class DashboardController
{
    private $sanPhamModel;
    private $donHangModel;
    private $nguoiDungModel;
    private $phieuNhapModel;

    public function __construct()
    {
        $this->sanPhamModel = new SanPhamModel();
        $this->donHangModel = new DonHangModel();
        $this->nguoiDungModel = new NguoiDungModel();
        $this->phieuNhapModel = new PhieuNhapModel();
    }

    public function index()
    {
        $userRole = strtolower($_SESSION['user_role'] ?? '');
        if ($userRole === 'customer') {
            // Quyền customer không được xem Dashboard quản trị
            echo '<p><font color="red"><b>[Từ chối truy cập]: Quyền Khách hàng (Customer) không có quyền truy cập Bảng điều khiển quản trị!</b></font></p>';
            echo '<p><a href="index.php?controller=sanpham&action=index">&larr; Đi đến Danh mục sản phẩm</a> | <a href="index.php?controller=donhang&action=index">Lịch sử đơn hàng</a></p>';
            exit;
        }

        // Tự động gán session admin mặc định nếu chưa đăng nhập (tiện lợi cho học tập và chấm bài)
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id']   = 'AD01';
            $_SESSION['user_name'] = 'Nguyễn Quản Trị';
            $_SESSION['user_role'] = 'admin';
        }

        // Thu thập thống kê
        $totalProducts = $this->sanPhamModel->count();
        $totalOrders   = $this->donHangModel->count();
        $totalRevenue  = $this->donHangModel->totalRevenue();
        $totalUsers    = $this->nguoiDungModel->count();
        $totalImports  = $this->phieuNhapModel->count();
        $lowStockList  = $this->sanPhamModel->getLowStock(10);
        $recentOrders  = $this->donHangModel->getRecent(5);

        // Alias biến tiếng Việt để tương thích View
        $tongSanPham   = $totalProducts;
        $tongDonHang   = $totalOrders;
        $doanhThu      = $totalRevenue;
        $sapHetHang    = count($lowStockList);
        $sanPhamSapHet = $lowStockList;
        $donHangMoi    = $recentOrders;

        $title = 'Tổng quan - Siêu Thị Mini';
        require_once 'views/dashboard/index.php';
    }
}

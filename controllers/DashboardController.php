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
        Access::requireRoles(['admin']);
        $this->sanPhamModel = new SanPhamModel();
        $this->donHangModel = new DonHangModel();
        $this->nguoiDungModel = new NguoiDungModel();
        $this->phieuNhapModel = new PhieuNhapModel();
    }

    public function index()
    {
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

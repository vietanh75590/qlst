<?php
/**
 * Model Phiếu nhập hàng (PhieuNhapModel)
 * Quản lý các thao tác với bảng phieunhap và chitietphieunhap
 */

require_once __DIR__ . '/../config/Database.php';

class PhieuNhapModel {
    private $conn;
    private $table = 'phieunhap';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT pn.*, ncc.tennhacungcap, COALESCE(nv.hoten, pn.id_nhanvien) AS tennhanvien 
                FROM {$this->table} pn
                LEFT JOIN nhacungcap ncc ON pn.id_nhacungcap = ncc.id
                LEFT JOIN nhanvien nv ON pn.id_nhanvien = nv.id
                ORDER BY pn.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT pn.*, ncc.tennhacungcap, COALESCE(nv.hoten, pn.id_nhanvien) AS tennhanvien 
                FROM {$this->table} pn
                LEFT JOIN nhacungcap ncc ON pn.id_nhacungcap = ncc.id
                LEFT JOIN nhanvien nv ON pn.id_nhanvien = nv.id
                WHERE pn.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data, $items) {
        try {
            $this->conn->beginTransaction();

            $tongtien = 0;
            foreach ($items as $item) {
                $tongtien += ($item['soluongnhap'] * $item['gianhap']);
            }
            if (isset($data['tongtien']) && $data['tongtien'] > 0) {
                $tongtien = $data['tongtien'];
            }

            // Đảm bảo id_nhanvien hợp lệ trong bảng nhanvien
            $idNv = !empty($data['id_nhanvien']) ? $data['id_nhanvien'] : 'NV01';
            $checkNv = $this->conn->prepare("SELECT id FROM nhanvien WHERE id = :id");
            $checkNv->execute([':id' => $idNv]);
            if (!$checkNv->fetch()) {
                $checkTk = $this->conn->prepare("SELECT id FROM nhanvien WHERE id_taikhoan = :tk");
                $checkTk->execute([':tk' => $idNv]);
                $nvRow = $checkTk->fetch();
                $idNv = $nvRow ? $nvRow['id'] : 'NV01';
            }

            $ngaynhap = !empty($data['ngaynhap']) ? $data['ngaynhap'] . ' ' . date('H:i:s') : date('Y-m-d H:i:s');
            $sql = "INSERT INTO {$this->table} (id_nhacungcap, id_nhanvien, ngaynhap, tongtien)
                    VALUES (:id_nhacungcap, :id_nhanvien, :ngaynhap, :tongtien)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_nhacungcap' => $data['id_nhacungcap'],
                ':id_nhanvien'   => $idNv,
                ':ngaynhap'      => $ngaynhap,
                ':tongtien'      => $tongtien
            ]);

            $phieuId = $this->conn->lastInsertId();

            $sqlChiTiet = "INSERT INTO chitietphieunhap (id_phieu, id_sp, soluongnhap, gianhap, hsd)
                           VALUES (:id_phieu, :id_sp, :soluongnhap, :gianhap, :hsd)";
            $stmtChiTiet = $this->conn->prepare($sqlChiTiet);

            foreach ($items as $item) {
                if (empty($item['id_sp']) || empty($item['soluongnhap'])) continue;

                $hsd = !empty($item['hsd']) ? $item['hsd'] : date('Y-m-d', strtotime('+6 months'));
                $stmtChiTiet->execute([
                    ':id_phieu'       => $phieuId,
                    ':id_sp'          => $item['id_sp'],
                    ':soluongnhap'    => (int)$item['soluongnhap'],
                    ':gianhap'        => $item['gianhap'],
                    ':hsd'            => $hsd
                ]);

                // Cộng dồn vào kho hàng (mã lô duy nhất theo phiếu và sản phẩm)
                $maLo = 'LO-' . $phieuId . '-' . $item['id_sp'];
                $this->conn->prepare("INSERT INTO kho_hang (ma_lo, id_sp, id_phieunhap, gianhap, hsd, sl_kho, sl_quay, trangthai)
                                      VALUES (:ma_lo, :id_sp, :id_phieu, :gia, :hsd, :sl, 0, 'con_hang')")
                    ->execute([
                        ':ma_lo'    => $maLo,
                        ':id_sp'    => $item['id_sp'],
                        ':id_phieu' => $phieuId,
                        ':gia'      => $item['gianhap'],
                        ':hsd'      => $hsd,
                        ':sl'       => (int)$item['soluongnhap']
                    ]);
            }

            $this->conn->commit();
            return $phieuId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi tạo phiếu nhập: " . $e->getMessage());
            return false;
        }
    }

    public function createWithDetails($data, $items) {
        return $this->create($data, $items);
    }

    public function delete($id) {
        $this->conn->prepare("DELETE FROM chitietphieunhap WHERE id_phieu = :id")->execute([':id' => $id]);
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getChiTiet($id_phieu) {
        $sql = "SELECT ct.*, sp.tensp, sp.donvi, sp.image 
                FROM chitietphieunhap ct
                LEFT JOIN sanpham sp ON ct.id_sp = sp.id
                WHERE ct.id_phieu = :id_phieu";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_phieu' => $id_phieu]);
        return $stmt->fetchAll();
    }

    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int)$this->conn->query($sql)->fetchColumn();
    }
}

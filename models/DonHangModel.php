<?php
/**
 * Model Đơn hàng (DonHangModel)
 * Quản lý các thao tác với bảng donhang và items_donhang
 */

require_once __DIR__ . '/../config/Database.php';

class DonHangModel {
    private $conn;
    private $table = 'donhang';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT dh.*, 
                       COALESCE(kh.hoten, dh.ten_nguoinhan) AS ten_khachhang, 
                       COALESCE(kh.sodienthoai, dh.sdt_nguoinhan) AS sodienthoai 
                FROM {$this->table} dh
                LEFT JOIN khachhang kh ON dh.id_khachhang = kh.id
                ORDER BY dh.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCustomer($id_khachhang, $id_taikhoan = null) {
        $sql = "SELECT dh.*, 
                       COALESCE(kh.hoten, dh.ten_nguoinhan) AS ten_khachhang, 
                       COALESCE(kh.sodienthoai, dh.sdt_nguoinhan) AS sodienthoai 
                FROM {$this->table} dh
                LEFT JOIN khachhang kh ON dh.id_khachhang = kh.id
                WHERE dh.id_khachhang = :id_khachhang";
        $params = [':id_khachhang' => $id_khachhang];
        if (!empty($id_taikhoan)) {
            $sql .= " OR kh.id_taikhoan = :id_taikhoan";
            $params[':id_taikhoan'] = $id_taikhoan;
        }
        $sql .= " ORDER BY dh.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT dh.*, 
                       COALESCE(kh.hoten, dh.ten_nguoinhan) AS ten_khachhang, 
                       COALESCE(kh.sodienthoai, dh.sdt_nguoinhan) AS sodienthoai 
                FROM {$this->table} dh
                LEFT JOIN khachhang kh ON dh.id_khachhang = kh.id
                WHERE dh.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function generateId() {
        $sql = "SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 100";
        $stmt = $this->conn->query($sql);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $maxNum = 0;
        foreach ($ids as $id) {
            if (preg_match('/^DH(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'DH' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function create($data, $items) {
        try {
            $this->conn->beginTransaction();

            $id = !empty($data['id']) ? trim($data['id']) : $this->generateId();

            $tongtien = 0;
            foreach ($items as $item) {
                $tongtien += ($item['soluong'] * $item['gia']);
            }
            if (isset($data['tongtien']) && $data['tongtien'] > 0) {
                $tongtien = $data['tongtien'];
            }

            $id_khachhang = !empty($data['id_khachhang']) ? $data['id_khachhang'] : null;
            if (!$id_khachhang && !empty($data['id_nguoidung'])) {
                if (strpos($data['id_nguoidung'], 'KH') === 0) {
                    $id_khachhang = $data['id_nguoidung'];
                } else {
                    $stmtKh = $this->conn->prepare("SELECT id FROM khachhang WHERE id_taikhoan = :tk LIMIT 1");
                    $stmtKh->execute([':tk' => $data['id_nguoidung']]);
                    $id_khachhang = $stmtKh->fetchColumn();
                }
            }
            if (!$id_khachhang) {
                $id_khachhang = 'KH04'; // Khách vãng lai mặc định
            }

            $sql = "INSERT INTO {$this->table} (id, id_khachhang, ten_nguoinhan, sdt_nguoinhan, diachi, tongtien, thucthu, trangthai_donhang, phuongthuc_thanhtoan, trangthai_thanhtoan)
                    VALUES (:id, :id_khachhang, :ten_nguoinhan, :sdt_nguoinhan, :diachi, :tongtien, :thucthu, 'dagiao', 'tienmat', 'dathanhtoan')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id'            => $id,
                ':id_khachhang'  => $id_khachhang,
                ':ten_nguoinhan' => $data['ten_nguoinhan'],
                ':sdt_nguoinhan' => $data['sdt_nguoinhan'],
                ':diachi'        => $data['diachi'],
                ':tongtien'      => $tongtien,
                ':thucthu'       => $tongtien
            ]);

            $sqlItem = "INSERT INTO items_donhang (id_donhang, id_sp, soluong, gia)
                        VALUES (:id_donhang, :id_sp, :soluong, :gia)";
            $stmtItem = $this->conn->prepare($sqlItem);

            foreach ($items as $item) {
                if (empty($item['id_sp']) || empty($item['soluong'])) continue;

                $stmtItem->execute([
                    ':id_donhang' => $id,
                    ':id_sp'      => $item['id_sp'],
                    ':soluong'    => (int)$item['soluong'],
                    ':gia'        => $item['gia']
                ]);

                // Trừ tồn kho tại quầy/kho
                $this->conn->prepare("UPDATE kho_hang SET sl_quay = GREATEST(0, sl_quay - :sl) WHERE id_sp = :id_sp LIMIT 1")
                    ->execute([':sl' => (int)$item['soluong'], ':id_sp' => $item['id_sp']]);
            }

            $this->conn->commit();
            return $id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi tạo đơn hàng: " . $e->getMessage());
            return false;
        }
    }

    public function createWithItems($data, $items) {
        return $this->create($data, $items);
    }

    public function getItems($id) {
        return $this->getItemsByOrder($id);
    }

    public function delete($id) {
        $this->conn->prepare("DELETE FROM items_donhang WHERE id_donhang = :id")->execute([':id' => $id]);
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getItemsByOrder($id_donhang) {
        $sql = "SELECT itm.*, sp.tensp, sp.image, sp.donvi 
                FROM items_donhang itm
                LEFT JOIN sanpham sp ON itm.id_sp = sp.id
                WHERE itm.id_donhang = :id_donhang";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_donhang' => $id_donhang]);
        return $stmt->fetchAll();
    }

    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int)$this->conn->query($sql)->fetchColumn();
    }

    public function totalRevenue() {
        $sql = "SELECT SUM(tongtien) FROM {$this->table}";
        $val = $this->conn->query($sql)->fetchColumn();
        return $val !== false ? (float)$val : 0;
    }

    public function getRecent($limit = 5) {
        $sql = "SELECT dh.*, COALESCE(kh.hoten, dh.ten_nguoinhan) as ten_khachhang 
                FROM {$this->table} dh
                LEFT JOIN khachhang kh ON dh.id_khachhang = kh.id
                ORDER BY dh.created_at DESC
                LIMIT :lim";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

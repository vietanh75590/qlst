<?php
/**
 * Model Sản phẩm (SanPhamModel)
 * Quản lý các thao tác với bảng sanpham và kho_hang
 */

require_once __DIR__ . '/../config/Database.php';

class SanPhamModel {
    private $conn;
    private $table = 'sanpham';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT sp.*, dm.tendanhmuc, ncc.tennhacungcap, 
                       COALESCE(SUM(kh.sl_kho + kh.sl_quay), 0) AS tonkho,
                       MIN(kh.hsd) AS hsd
                FROM {$this->table} sp
                LEFT JOIN danhmucsanpham dm ON sp.id_danhmuc = dm.id
                LEFT JOIN nhacungcap ncc ON sp.id_nhacungcap = ncc.id
                LEFT JOIN kho_hang kh ON sp.id = kh.id_sp
                GROUP BY sp.id
                ORDER BY sp.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT sp.*, dm.tendanhmuc, ncc.tennhacungcap, 
                       COALESCE(SUM(kh.sl_kho + kh.sl_quay), 0) AS tonkho,
                       MIN(kh.hsd) AS hsd
                FROM {$this->table} sp
                LEFT JOIN danhmucsanpham dm ON sp.id_danhmuc = dm.id
                LEFT JOIN nhacungcap ncc ON sp.id_nhacungcap = ncc.id
                LEFT JOIN kho_hang kh ON sp.id = kh.id_sp
                WHERE sp.id = :id
                GROUP BY sp.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByCategory($id_danhmuc) {
        $sql = "SELECT sp.*, dm.tendanhmuc, ncc.tennhacungcap, 
                       COALESCE(SUM(kh.sl_kho + kh.sl_quay), 0) AS tonkho,
                       MIN(kh.hsd) AS hsd
                FROM {$this->table} sp
                LEFT JOIN danhmucsanpham dm ON sp.id_danhmuc = dm.id
                LEFT JOIN nhacungcap ncc ON sp.id_nhacungcap = ncc.id
                LEFT JOIN kho_hang kh ON sp.id = kh.id_sp
                WHERE sp.id_danhmuc = :id_danhmuc 
                   OR sp.id_danhmuc IN (SELECT id FROM danhmucsanpham WHERE id_danhmuc_cha = :id_danhmuc_cha)
                GROUP BY sp.id
                ORDER BY sp.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_danhmuc'     => $id_danhmuc,
            ':id_danhmuc_cha' => $id_danhmuc
        ]);
        return $stmt->fetchAll();
    }

    public function generateId() {
        $sql = "SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 100";
        $stmt = $this->conn->query($sql);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $maxNum = 0;
        foreach ($ids as $id) {
            if (preg_match('/^SP(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'SP' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $id = !empty($data['id']) ? trim($data['id']) : $this->generateId();
        $sql = "INSERT INTO {$this->table} (id, id_danhmuc, id_nhacungcap, mavach, tensp, giasp, donvi, image, mota)
                VALUES (:id, :id_danhmuc, :id_nhacungcap, :mavach, :tensp, :giasp, :donvi, :image, :mota)";
        $stmt = $this->conn->prepare($sql);
        $res = $stmt->execute([
            ':id'            => $id,
            ':id_danhmuc'    => !empty($data['id_danhmuc']) ? $data['id_danhmuc'] : null,
            ':id_nhacungcap' => !empty($data['id_nhacungcap']) ? $data['id_nhacungcap'] : null,
            ':mavach'        => !empty($data['mavach']) ? $data['mavach'] : null,
            ':tensp'         => $data['tensp'],
            ':giasp'         => $data['giasp'],
            ':donvi'         => !empty($data['donvi']) ? $data['donvi'] : 'Hộp',
            ':image'         => !empty($data['image']) ? $data['image'] : null,
            ':mota'          => !empty($data['mota']) ? $data['mota'] : null
        ]);

        if ($res && isset($data['tonkho']) && (int)$data['tonkho'] > 0) {
            $sqlKho = "INSERT INTO kho_hang (ma_lo, id_sp, gianhap, hsd, sl_kho, sl_quay, trangthai)
                       VALUES (:ma_lo, :id_sp, :gianhap, :hsd, :sl_kho, :sl_quay, 'con_hang')";
            $stmtKho = $this->conn->prepare($sqlKho);
            $stmtKho->execute([
                ':ma_lo'   => 'LO-' . $id . '-' . date('Ym'),
                ':id_sp'   => $id,
                ':gianhap' => $data['giasp'] * 0.75,
                ':hsd'     => !empty($data['hsd']) ? $data['hsd'] : date('Y-m-d', strtotime('+6 months')),
                ':sl_kho'  => (int)$data['tonkho'],
                ':sl_quay' => 0
            ]);
        }
        return $res;
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET id_danhmuc = :id_danhmuc, id_nhacungcap = :id_nhacungcap, tensp = :tensp, 
                    giasp = :giasp, donvi = :donvi, image = :image, mota = :mota
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':id_danhmuc'    => !empty($data['id_danhmuc']) ? $data['id_danhmuc'] : null,
            ':id_nhacungcap' => !empty($data['id_nhacungcap']) ? $data['id_nhacungcap'] : null,
            ':tensp'         => $data['tensp'],
            ':giasp'         => $data['giasp'],
            ':donvi'         => !empty($data['donvi']) ? $data['donvi'] : 'Hộp',
            ':image'         => !empty($data['image']) ? $data['image'] : null,
            ':mota'          => !empty($data['mota']) ? $data['mota'] : null
        ]);
    }

    public function delete($id) {
        $this->conn->prepare("DELETE FROM kho_hang WHERE id_sp = :id")->execute([':id' => $id]);
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int)$this->conn->query($sql)->fetchColumn();
    }

    public function getLowStock($threshold = 10) {
        $sql = "SELECT sp.*, dm.tendanhmuc, ncc.tennhacungcap, 
                       COALESCE(SUM(kh.sl_kho + kh.sl_quay), 0) AS tonkho,
                       MIN(kh.hsd) AS hsd
                FROM {$this->table} sp
                LEFT JOIN danhmucsanpham dm ON sp.id_danhmuc = dm.id
                LEFT JOIN nhacungcap ncc ON sp.id_nhacungcap = ncc.id
                LEFT JOIN kho_hang kh ON sp.id = kh.id_sp
                GROUP BY sp.id
                HAVING tonkho <= :threshold
                ORDER BY tonkho ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':threshold', (int)$threshold, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($keyword) {
        $sql = "SELECT sp.*, dm.tendanhmuc, ncc.tennhacungcap, 
                       COALESCE(SUM(kh.sl_kho + kh.sl_quay), 0) AS tonkho,
                       MIN(kh.hsd) AS hsd
                FROM {$this->table} sp
                LEFT JOIN danhmucsanpham dm ON sp.id_danhmuc = dm.id
                LEFT JOIN nhacungcap ncc ON sp.id_nhacungcap = ncc.id
                LEFT JOIN kho_hang kh ON sp.id = kh.id_sp
                WHERE sp.tensp LIKE :kw1 OR sp.mavach LIKE :kw2 OR sp.id LIKE :kw3
                GROUP BY sp.id
                ORDER BY sp.tensp ASC";
        $term = "%{$keyword}%";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':kw1' => $term, ':kw2' => $term, ':kw3' => $term]);
        return $stmt->fetchAll();
    }
}

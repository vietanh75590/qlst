<?php
/**
 * Model Danh mục sản phẩm (DanhMucModel)
 * Quản lý các thao tác CRUD với bảng danhmucsanpham
 */

require_once __DIR__ . '/../config/database.php';

class DanhMucModel {
    private $conn;
    private $table = 'danhmucsanpham';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT dm.*, COUNT(sp.id) as tong_sanpham
                FROM {$this->table} dm
                LEFT JOIN sanpham sp ON dm.id = sp.id_danhmuc
                GROUP BY dm.id
                ORDER BY dm.id_danhmuc_cha ASC, dm.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByParent($idParent = null) {
        $sql = "SELECT dm.*, COUNT(sp.id) as tong_sanpham
                FROM {$this->table} dm
                LEFT JOIN sanpham sp ON dm.id = sp.id_danhmuc
                WHERE dm.id_danhmuc_cha " . ($idParent ? "= :id_parent" : "IS NULL") . "
                GROUP BY dm.id
                ORDER BY dm.id ASC";
        $stmt = $this->conn->prepare($sql);
        if ($idParent) {
            $stmt->execute([':id_parent' => $idParent]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getTree() {
        $danh_mucs = $this->getByParent(null);
        $tree = [];
        foreach ($danh_mucs as $dm) {
            $dm['children'] = $this->getByParent($dm['id']);
            $tree[] = $dm;
        }
        return $tree;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
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
            if (preg_match('/^DM(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'DM' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $id = !empty($data['id']) ? trim($data['id']) : $this->generateId();
        $sql = "INSERT INTO {$this->table} (id, tendanhmuc, mota, id_danhmuc_cha) VALUES (:id, :tendanhmuc, :mota, :id_danhmuc_cha)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'              => $id,
            ':tendanhmuc'      => $data['tendanhmuc'],
            ':mota'            => !empty($data['mota']) ? $data['mota'] : null,
            ':id_danhmuc_cha'  => !empty($data['id_danhmuc_cha']) ? $data['id_danhmuc_cha'] : null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET tendanhmuc = :tendanhmuc, mota = :mota, id_danhmuc_cha = :id_danhmuc_cha WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'              => $id,
            ':tendanhmuc'      => $data['tendanhmuc'],
            ':mota'            => !empty($data['mota']) ? $data['mota'] : null,
            ':id_danhmuc_cha'  => !empty($data['id_danhmuc_cha']) ? $data['id_danhmuc_cha'] : null
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int)$this->conn->query($sql)->fetchColumn();
    }
}

<?php
/**
 * Model Nhà cung cấp (NhaCungCapModel)
 * Quản lý các thao tác CRUD với bảng nhacungcap
 */

require_once __DIR__ . '/../config/Database.php';

class NhaCungCapModel {
    private $conn;
    private $table = 'nhacungcap';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT ncc.*, COUNT(sp.id) as tong_sanpham 
                FROM {$this->table} ncc
                LEFT JOIN sanpham sp ON ncc.id = sp.id_nhacungcap
                GROUP BY ncc.id
                ORDER BY ncc.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
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
            if (preg_match('/^NCC(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'NCC' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $id = !empty($data['id']) ? trim($data['id']) : $this->generateId();
        $sql = "INSERT INTO {$this->table} (id, tennhacungcap, sodienthoai, diachi) 
                VALUES (:id, :tennhacungcap, :sodienthoai, :diachi)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':tennhacungcap' => $data['tennhacungcap'],
            ':sodienthoai'   => !empty($data['sodienthoai']) ? $data['sodienthoai'] : null,
            ':diachi'        => !empty($data['diachi']) ? $data['diachi'] : null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET tennhacungcap = :tennhacungcap, sodienthoai = :sodienthoai, diachi = :diachi 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':tennhacungcap' => $data['tennhacungcap'],
            ':sodienthoai'   => !empty($data['sodienthoai']) ? $data['sodienthoai'] : null,
            ':diachi'        => !empty($data['diachi']) ? $data['diachi'] : null
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

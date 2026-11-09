<?php
/**
 * Model Ca làm việc (CaLamViecModel)
 * Quản lý các thao tác CRUD với bảng calamviec
 */

require_once __DIR__ . '/../config/Database.php';

class CaLamViecModel {
    private $conn;
    private $table = 'calamviec';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY giobatdau ASC";
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
            if (preg_match('/^CA(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'CA' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $id = !empty($data['id']) ? trim($data['id']) : $this->generateId();
        $sql = "INSERT INTO {$this->table} (id, tenca, giobatdau, gioketthuc, mota) 
                VALUES (:id, :tenca, :giobatdau, :gioketthuc, :mota)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'         => $id,
            ':tenca'      => $data['tenca'],
            ':giobatdau'  => $data['giobatdau'],
            ':gioketthuc' => $data['gioketthuc'],
            ':mota'       => !empty($data['mota']) ? $data['mota'] : null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET tenca = :tenca, giobatdau = :giobatdau, gioketthuc = :gioketthuc, mota = :mota 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'         => $id,
            ':tenca'      => $data['tenca'],
            ':giobatdau'  => $data['giobatdau'],
            ':gioketthuc' => $data['gioketthuc'],
            ':mota'       => !empty($data['mota']) ? $data['mota'] : null
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

<?php
/**
 * Model Lịch làm việc (LichLamViecModel)
 * Quản lý các thao tác với bảng lichlamviec
 */

require_once __DIR__ . '/../config/Database.php';

class LichLamViecModel {
    private $conn;
    private $table = 'lichlamviec';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT llv.*, COALESCE(nv.hoten, llv.id_nhanvien) AS tennhanvien, clv.tenca, clv.giobatdau, clv.gioketthuc 
                FROM {$this->table} llv
                LEFT JOIN nhanvien nv ON llv.id_nhanvien = nv.id
                LEFT JOIN calamviec clv ON llv.id_ca = clv.id
                ORDER BY llv.ngaylam DESC, clv.giobatdau ASC";
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

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (id_nhanvien, id_ca, ngaylam, ghichu) 
                VALUES (:id_nhanvien, :id_ca, :ngaylam, :ghichu)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_nhanvien' => $data['id_nhanvien'],
            ':id_ca'       => $data['id_ca'],
            ':ngaylam'     => $data['ngaylam'],
            ':ghichu'      => !empty($data['ghichu']) ? $data['ghichu'] : null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET id_nhanvien = :id_nhanvien, id_ca = :id_ca, ngaylam = :ngaylam, ghichu = :ghichu 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':id_nhanvien' => $data['id_nhanvien'],
            ':id_ca'       => $data['id_ca'],
            ':ngaylam'     => $data['ngaylam'],
            ':ghichu'      => !empty($data['ghichu']) ? $data['ghichu'] : null
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getByNhanVien($id_nhanvien) {
        $sql = "SELECT llv.*, clv.tenca, clv.giobatdau, clv.gioketthuc 
                FROM {$this->table} llv
                LEFT JOIN calamviec clv ON llv.id_ca = clv.id
                WHERE llv.id_nhanvien = :id_nv
                ORDER BY llv.ngaylam DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_nv' => $id_nhanvien]);
        return $stmt->fetchAll();
    }

    public function getByDate($date) {
        $sql = "SELECT llv.*, COALESCE(nv.hoten, llv.id_nhanvien) AS tennhanvien, clv.tenca, clv.giobatdau, clv.gioketthuc 
                FROM {$this->table} llv
                LEFT JOIN nhanvien nv ON llv.id_nhanvien = nv.id
                LEFT JOIN calamviec clv ON llv.id_ca = clv.id
                WHERE llv.ngaylam = :date
                ORDER BY clv.giobatdau ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':date' => $date]);
        return $stmt->fetchAll();
    }

    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int)$this->conn->query($sql)->fetchColumn();
    }
}

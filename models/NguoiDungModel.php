<?php
/**
 * Model Người dùng (NguoiDungModel)
 * Quản lý tài khoản nguoidung, nhanvien, khachhang
 */

require_once __DIR__ . '/../config/Database.php';

class NguoiDungModel {
    private $conn;
    private $table = 'nguoidung';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT nd.*, 
                       kh.id AS id_khachhang,
                       nv.id AS id_nhanvien,
                       COALESCE(nv.hoten, kh.hoten, nd.email) AS hoten,
                       COALESCE(nv.sodienthoai, kh.sodienthoai) AS sodienthoai,
                       COALESCE(nv.diachi, kh.diachi) AS diachi,
                       nv.chucvu, nv.luong_coban AS luong, kh.diemtichluy
                FROM {$this->table} nd
                LEFT JOIN nhanvien nv ON nd.id = nv.id_taikhoan
                LEFT JOIN khachhang kh ON nd.id = kh.id_taikhoan
                ORDER BY nd.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT nd.*, 
                       kh.id AS id_khachhang,
                       nv.id AS id_nhanvien,
                       COALESCE(nv.hoten, kh.hoten, nd.email) AS hoten,
                       COALESCE(nv.sodienthoai, kh.sodienthoai) AS sodienthoai,
                       COALESCE(nv.diachi, kh.diachi) AS diachi,
                       nv.chucvu, nv.luong_coban AS luong, kh.diemtichluy
                FROM {$this->table} nd
                LEFT JOIN nhanvien nv ON nd.id = nv.id_taikhoan
                LEFT JOIN khachhang kh ON nd.id = kh.id_taikhoan
                WHERE nd.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByEmail($email) {
        $sql = "SELECT nd.*, 
                       kh.id AS id_khachhang,
                       nv.id AS id_nhanvien,
                       COALESCE(nv.hoten, kh.hoten, nd.email) AS hoten,
                       COALESCE(nv.sodienthoai, kh.sodienthoai) AS sodienthoai,
                       COALESCE(nv.diachi, kh.diachi) AS diachi,
                       nv.chucvu, nv.luong_coban AS luong, kh.diemtichluy
                FROM {$this->table} nd
                LEFT JOIN nhanvien nv ON nd.id = nv.id_taikhoan
                LEFT JOIN khachhang kh ON nd.id = kh.id_taikhoan
                WHERE nd.email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function generateId() {
        $sql = "SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 100";
        $stmt = $this->conn->query($sql);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $maxNum = 0;
        foreach ($ids as $id) {
            if (preg_match('/^TK(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'TK' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function generateNhanVienId() {
        $sql = "SELECT id FROM nhanvien ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $maxNum = 0;
        foreach ($ids as $id) {
            if (preg_match('/^NV(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'NV' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function generateKhachHangId() {
        $sql = "SELECT id FROM khachhang ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $maxNum = 0;
        foreach ($ids as $id) {
            if (preg_match('/^KH(\d+)$/i', $id, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        return 'KH' . str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        try {
            $this->conn->beginTransaction();

            $id = !empty($data['id']) ? trim($data['id']) : $this->generateId();
            $password = !empty($data['matkhau']) ? $data['matkhau'] : '123456';
            $role = !empty($data['role']) ? $data['role'] : 'staff';
            $email = trim($data['email']);

            $sql = "INSERT INTO {$this->table} (id, email, matkhau, role, trangthai)
                    VALUES (:id, :email, :matkhau, :role, 'hoatdong')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id'      => $id,
                ':email'   => $email,
                ':matkhau' => $password,
                ':role'    => $role
            ]);

            $hoten = !empty($data['hoten']) ? trim($data['hoten']) : 'Người dùng mới';
            $sdt = !empty($data['sodienthoai']) ? trim($data['sodienthoai']) : ('09' . rand(10000000, 99999999));
            $diachi = !empty($data['diachi']) ? trim($data['diachi']) : 'Hà Nội';

            if ($role === 'customer') {
                $idKh = $this->generateKhachHangId();
                $sqlKh = "INSERT INTO khachhang (id, id_taikhoan, hoten, sodienthoai, diachi, diemtichluy, hangthanhvien)
                          VALUES (:id, :id_tk, :hoten, :sdt, :diachi, 0, 'Dong')";
                $stmtKh = $this->conn->prepare($sqlKh);
                $stmtKh->execute([
                    ':id'     => $idKh,
                    ':id_tk'  => $id,
                    ':hoten'  => $hoten,
                    ':sdt'    => $sdt,
                    ':diachi' => $diachi
                ]);
            } else {
                $idNv = $this->generateNhanVienId();
                $cccd = !empty($data['cccd']) ? trim($data['cccd']) : ('001' . rand(100000000, 999999999));
                $ngayVaoLam = !empty($data['ngayvaolam']) ? $data['ngayvaolam'] : date('Y-m-d');
                $chucvu = !empty($data['chucvu']) ? trim($data['chucvu']) : ($role === 'admin' ? 'Quản lý siêu thị' : 'Nhân viên');
                $luong = !empty($data['luong']) ? (float)$data['luong'] : ($role === 'admin' ? 15000000 : 7000000);

                $sqlNv = "INSERT INTO nhanvien (id, id_taikhoan, hoten, sodienthoai, cccd, diachi, chucvu, luong_coban, ngayvaolam, trangthai)
                          VALUES (:id, :id_tk, :hoten, :sdt, :cccd, :diachi, :chucvu, :luong, :ngayvaolam, 'danglam')";
                $stmtNv = $this->conn->prepare($sqlNv);
                $stmtNv->execute([
                    ':id'         => $idNv,
                    ':id_tk'      => $id,
                    ':hoten'      => $hoten,
                    ':sdt'        => $sdt,
                    ':cccd'       => $cccd,
                    ':diachi'     => $diachi,
                    ':chucvu'     => $chucvu,
                    ':luong'      => $luong,
                    ':ngayvaolam' => $ngayVaoLam
                ]);
            }

            $this->conn->commit();
            return $id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi tạo người dùng: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET email = :email, role = :role";
        $params = [
            ':id'    => $id,
            ':email' => $data['email'],
            ':role'  => $data['role'] ?? 'staff'
        ];
        if (!empty($data['matkhau'])) {
            $sql .= ", matkhau = :matkhau";
            $params[':matkhau'] = $data['matkhau'];
        }
        $sql .= " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $res = $stmt->execute($params);

        if ($res && !empty($data['hoten'])) {
            $this->conn->prepare("UPDATE nhanvien SET hoten = :hoten, sodienthoai = :sdt, diachi = :dc, chucvu = :cv, luong_coban = :luong WHERE id_taikhoan = :id")
                ->execute([
                    ':hoten' => $data['hoten'],
                    ':sdt'   => $data['sodienthoai'] ?? '',
                    ':dc'    => $data['diachi'] ?? '',
                    ':cv'    => $data['chucvu'] ?? 'Nhân viên',
                    ':luong' => $data['luong'] ?? 8000000,
                    ':id'    => $id
                ]);
            $this->conn->prepare("UPDATE khachhang SET hoten = :hoten, sodienthoai = :sdt, diachi = :dc WHERE id_taikhoan = :id")
                ->execute([
                    ':hoten' => $data['hoten'],
                    ':sdt'   => $data['sodienthoai'] ?? '',
                    ':dc'    => $data['diachi'] ?? '',
                    ':id'    => $id
                ]);
        }
        return $res;
    }

    public function delete($id) {
        $this->conn->prepare("DELETE FROM nhanvien WHERE id_taikhoan = :id")->execute([':id' => $id]);
        $this->conn->prepare("DELETE FROM khachhang WHERE id_taikhoan = :id")->execute([':id' => $id]);
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function login($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && ($user['matkhau'] === $password || password_verify($password, $user['matkhau']))) {
            return $user;
        }
        return false;
    }

    public function getStaffList() {
        $sql = "SELECT nv.id, nv.hoten, nv.chucvu, nd.role, nd.email 
                FROM nhanvien nv 
                JOIN nguoidung nd ON nv.id_taikhoan = nd.id
                WHERE nd.trangthai = 'hoatdong'
                ORDER BY nv.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStaff() {
        return $this->getStaffList();
    }

    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int)$this->conn->query($sql)->fetchColumn();
    }
}

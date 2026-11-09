<?php
/**
 * Cấu hình kết nối cơ sở dữ liệu
 * Sử dụng PDO để kết nối MySQL
 * Database: quanlysieuthi
 */

if (!class_exists('Database')) {
    class Database {
        // Thông tin kết nối
        private $host = '127.0.0.1';
        private $db_name = 'quanlysieuthi';
        private $username = 'root';
        private $password = '231006';
        
        // Biến lưu kết nối
        private $conn = null;

        /**
         * Tạo và trả về kết nối PDO
         * @return PDO|null Đối tượng PDO hoặc null nếu lỗi
         */
        public function getConnection() {
            try {
                // Tạo kết nối PDO với charset utf8mb4
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password);
                
                // Thiết lập chế độ báo lỗi: ném Exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Thiết lập chế độ fetch mặc định: mảng kết hợp
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // Tắt prepared statement giả lập (dùng native)
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                
            } catch (PDOException $e) {
                // Ghi log lỗi và hiển thị thông báo
                error_log("Lỗi kết nối CSDL: " . $e->getMessage());
                die("Không thể kết nối cơ sở dữ liệu. Vui lòng thử lại sau.");
            }
            
            return $this->conn;
        }
    }
}

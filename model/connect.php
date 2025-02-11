<?php
class connectDB
{
    private $servername = "localhost";
    private $username = "root";
    private $databasename = "backend_web2";
    private $password = "";
    public $conn; // Đây là thuộc tính chứa kết nối

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->databasename);
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    // Return table (records)
    public function query($sql)
    {
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error . "<br>" . $sql);
        }
        return $result;
    }

    // For insert, delete, update
    public function execute($sql)
    {
        $result = $this->conn->query($sql);
        if ($result) { // Sử dụng $result để kiểm tra số lượng dòng ảnh hưởng
            return true;
        }
        return false;
    }

    // Close database
    public function close()
    {
        mysqli_close($this->conn);
    }

    public function selectAll($tableName)
    {
        $sql = "SELECT * FROM $tableName";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error);
        }
        return $result;
    }
}

<?php
require_once 'app/config/database.php';
class Student
{
    private $conn;
    private $table_name = "SinhVien";
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all students
    public function getAll($limit, $offset)
    {
        $query = "SELECT s.MaSV, s.HoTen, s.GioiTinh, s.NgaySinh, s.Hinh, s.MaNganh, n.TenNganh 
                FROM " . $this->table_name . " s
                LEFT JOIN NganhHoc n ON s.MaNganh = n.MaNganh
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create student
    public function create($MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $Password = null)
    {
        // Default password is student ID if none provided
        if ($Password === null) {
            $Password = password_hash($MaSV, PASSWORD_DEFAULT);
        } else {
            $Password = password_hash($Password, PASSWORD_DEFAULT);
        }

        $query = "INSERT INTO " . $this->table_name . " 
                SET MaSV=:MaSV, HoTen=:HoTen, GioiTinh=:GioiTinh, 
                    NgaySinh=:NgaySinh, Hinh=:Hinh, MaNganh=:MaNganh, Password=:Password";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaSV = htmlspecialchars(strip_tags($MaSV));
        $HoTen = htmlspecialchars(strip_tags($HoTen));
        $GioiTinh = htmlspecialchars(strip_tags($GioiTinh));
        $NgaySinh = htmlspecialchars(strip_tags($NgaySinh));
        $Hinh = htmlspecialchars(strip_tags($Hinh));
        $MaNganh = htmlspecialchars(strip_tags($MaNganh));

        // Bind values
        $stmt->bindParam(":MaSV", $MaSV);
        $stmt->bindParam(":HoTen", $HoTen);
        $stmt->bindParam(":GioiTinh", $GioiTinh);
        $stmt->bindParam(":NgaySinh", $NgaySinh);
        $stmt->bindParam(":Hinh", $Hinh);
        $stmt->bindParam(":MaNganh", $MaNganh);
        $stmt->bindParam(":Password", $Password);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read one student
    public function readOne($MaSV)
    {
        $query = "SELECT s.MaSV, s.HoTen, s.GioiTinh, s.NgaySinh, s.Hinh, s.MaNganh, n.TenNganh, s.Password 
                FROM " . $this->table_name . " s
                LEFT JOIN NganhHoc n ON s.MaNganh = n.MaNganh
                WHERE s.MaSV = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaSV);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        }

        return false;
    }

    // Update student
    public function update($MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $Password = null)
    {
        $passwordClause = "";
        $params = [
            ":MaSV" => htmlspecialchars(strip_tags($MaSV)),
            ":HoTen" => htmlspecialchars(strip_tags($HoTen)),
            ":GioiTinh" => htmlspecialchars(strip_tags($GioiTinh)),
            ":NgaySinh" => htmlspecialchars(strip_tags($NgaySinh)),
            ":Hinh" => htmlspecialchars(strip_tags($Hinh)),
            ":MaNganh" => htmlspecialchars(strip_tags($MaNganh))
        ];

        // Add password to query only if provided
        if ($Password !== null) {
            $passwordClause = ", Password = :Password";
            $params[":Password"] = password_hash($Password, PASSWORD_DEFAULT);
        }

        $query = "UPDATE " . $this->table_name . "
                SET HoTen = :HoTen,
                    GioiTinh = :GioiTinh,
                    NgaySinh = :NgaySinh,
                    Hinh = :Hinh,
                    MaNganh = :MaNganh" . $passwordClause . "
                WHERE MaSV = :MaSV";

        $stmt = $this->conn->prepare($query);

        // Bind all parameters
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $params[$key]);
        }

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete student
    public function delete($MaSV)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaSV = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaSV);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Authenticate student
    public function authenticate($MaSV, $password)
    {
        // Get student record
        $student = $this->readOne($MaSV);

        // Check if student exists and password is correct
        if ($student && password_verify($password, $student['Password'])) {
            return $student;
        }

        return false;
    }

    // Count all students
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}

<?php
require_once 'app/config/database.php';

class Course
{
    private $conn;
    private $table_name = "HocPhan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all courses
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one course
    public function readOne($MaHP)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaHP = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaHP);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create course
    public function create($MaHP, $TenHP, $SoTinChi, $SoLuongDuKien)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                SET MaHP=:MaHP, TenHP=:TenHP, SoTinChi=:SoTinChi, SoLuongDuKien=:SoLuongDuKien";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaHP = htmlspecialchars(strip_tags($MaHP));
        $TenHP = htmlspecialchars(strip_tags($TenHP));
        $SoTinChi = htmlspecialchars(strip_tags($SoTinChi));
        $SoLuongDuKien = htmlspecialchars(strip_tags($SoLuongDuKien));

        // Bind values
        $stmt->bindParam(":MaHP", $MaHP);
        $stmt->bindParam(":TenHP", $TenHP);
        $stmt->bindParam(":SoTinChi", $SoTinChi);
        $stmt->bindParam(":SoLuongDuKien", $SoLuongDuKien);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update course
    public function update($MaHP, $TenHP, $SoTinChi, $SoLuongDuKien)
    {
        $query = "UPDATE " . $this->table_name . "
                SET TenHP = :TenHP,
                    SoTinChi = :SoTinChi,
                    SoLuongDuKien = :SoLuongDuKien
                WHERE MaHP = :MaHP";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaHP = htmlspecialchars(strip_tags($MaHP));
        $TenHP = htmlspecialchars(strip_tags($TenHP));
        $SoTinChi = htmlspecialchars(strip_tags($SoTinChi));
        $SoLuongDuKien = htmlspecialchars(strip_tags($SoLuongDuKien));

        // Bind values
        $stmt->bindParam(":MaHP", $MaHP);
        $stmt->bindParam(":TenHP", $TenHP);
        $stmt->bindParam(":SoTinChi", $SoTinChi);
        $stmt->bindParam(":SoLuongDuKien", $SoLuongDuKien);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update course quantity after registration
    public function decreaseQuantity($MaHP)
    {
        $query = "UPDATE " . $this->table_name . "
                SET SoLuongDuKien = SoLuongDuKien - 1
                WHERE MaHP = :MaHP AND SoLuongDuKien > 0";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $MaHP = htmlspecialchars(strip_tags($MaHP));

        // Bind value
        $stmt->bindParam(":MaHP", $MaHP);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete course
    public function delete($MaHP)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaHP = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaHP);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Check if course has available slots
    public function hasAvailableSlots($MaHP)
    {
        $query = "SELECT SoLuongDuKien FROM " . $this->table_name . " WHERE MaHP = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaHP);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($row && $row['SoLuongDuKien'] > 0);
    }
}

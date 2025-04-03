<?php
require_once 'app/config/database.php';

class Registration
{
    private $conn;
    private $table_name = "DangKy";
    private $detail_table = "ChiTietDangKy";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all registrations for a student
    public function getByStudent($MaSV)
    {
        $query = "SELECT d.MaDK, d.NgayDK, d.MaSV, sv.HoTen
                FROM " . $this->table_name . " d
                LEFT JOIN SinhVien sv ON d.MaSV = sv.MaSV
                WHERE d.MaSV = ?
                ORDER BY d.NgayDK DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaSV);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new registration
    public function create($MaSV)
    {
        // Get current date
        $NgayDK = date('Y-m-d');

        $query = "INSERT INTO " . $this->table_name . " 
                SET NgayDK=:NgayDK, MaSV=:MaSV";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaSV = htmlspecialchars(strip_tags($MaSV));

        // Bind values
        $stmt->bindParam(":NgayDK", $NgayDK);
        $stmt->bindParam(":MaSV", $MaSV);

        // Execute query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Add course to registration
    public function addCourse($MaDK, $MaHP)
    {
        $query = "INSERT INTO " . $this->detail_table . " 
                SET MaDK=:MaDK, MaHP=:MaHP";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaDK = htmlspecialchars(strip_tags($MaDK));
        $MaHP = htmlspecialchars(strip_tags($MaHP));

        // Bind values
        $stmt->bindParam(":MaDK", $MaDK);
        $stmt->bindParam(":MaHP", $MaHP);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Get registration details
    public function getRegistrationDetails($MaDK)
    {
        $query = "SELECT c.MaDK, c.MaHP, h.TenHP, h.SoTinChi, h.SoLuongDuKien 
                FROM " . $this->detail_table . " c
                JOIN HocPhan h ON c.MaHP = h.MaHP
                WHERE c.MaDK = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaDK);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete registration
    public function delete($MaDK)
    {
        // First get all courses in the registration to increase their available slots
        $courses = $this->getRegistrationDetails($MaDK);

        // Start transaction
        $this->conn->beginTransaction();

        try {
            // Increase available slots for each course
            foreach ($courses as $course) {
                $query = "UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien + 1 WHERE MaHP = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $course['MaHP']);
                $stmt->execute();
            }

            // Delete related details
            $detail_query = "DELETE FROM " . $this->detail_table . " WHERE MaDK = ?";
            $detail_stmt = $this->conn->prepare($detail_query);
            $detail_stmt->bindParam(1, $MaDK);
            $detail_stmt->execute();

            // Delete main registration
            $query = "DELETE FROM " . $this->table_name . " WHERE MaDK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $MaDK);

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Delete a single course from registration
    public function deleteCourse($MaDK, $MaHP)
    {
        // Start transaction
        $this->conn->beginTransaction();

        try {
            // Increase available slots for the course
            $query = "UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien + 1 WHERE MaHP = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $MaHP);
            $stmt->execute();

            // Delete the course from registration
            $query = "DELETE FROM " . $this->detail_table . " WHERE MaDK = ? AND MaHP = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $MaDK);
            $stmt->bindParam(2, $MaHP);

            if ($stmt->execute()) {
                // Check if there are any courses left in the registration
                $query = "SELECT COUNT(*) as count FROM " . $this->detail_table . " WHERE MaDK = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $MaDK);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // If no courses left, delete the registration
                if ($row['count'] == 0) {
                    $query = "DELETE FROM " . $this->table_name . " WHERE MaDK = ?";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(1, $MaDK);
                    $stmt->execute();
                }

                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Check if course is already registered
    public function isCourseRegistered($MaSV, $MaHP)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " d
                JOIN " . $this->detail_table . " c ON d.MaDK = c.MaDK
                WHERE d.MaSV = ? AND c.MaHP = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaSV);
        $stmt->bindParam(2, $MaHP);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($row['count'] > 0);
    }

    // Get one registration
    public function readOne($MaDK)
    {
        $query = "SELECT d.MaDK, d.NgayDK, d.MaSV, sv.HoTen
                FROM " . $this->table_name . " d
                LEFT JOIN SinhVien sv ON d.MaSV = sv.MaSV
                WHERE d.MaDK = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaDK);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

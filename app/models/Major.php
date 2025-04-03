<?php
class Major
{
    private $conn;
    private $table_name = "NganhHoc";


    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all majors
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY MaNganh";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create major
    public function create($MaNganh, $TenNganh)
    {
        $query = "INSERT INTO " . $this->table_name . " SET MaNganh=:MaNganh, TenNganh=:TenNganh";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaNganh = htmlspecialchars(strip_tags($MaNganh));
        $TenNganh = htmlspecialchars(strip_tags($TenNganh));

        // Bind values
        $stmt->bindParam(":MaNganh", $MaNganh);
        $stmt->bindParam(":TenNganh", $TenNganh);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read one major
    public function readOne($MaNganh)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNganh = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaNganh);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $MaNganh = $row['MaNganh'];
            $TenNganh = $row['TenNganh'];
            return true;
        }

        return false;
    }

    // Update major
    public function update($MaNganh, $TenNganh)
    {
        $query = "UPDATE " . $this->table_name . " SET TenNganh = :TenNganh WHERE MaNganh = :MaNganh";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $MaNganh = htmlspecialchars(strip_tags($MaNganh));
        $TenNganh = htmlspecialchars(strip_tags($TenNganh));

        // Bind values
        $stmt->bindParam(":MaNganh", $MaNganh);
        $stmt->bindParam(":TenNganh", $TenNganh);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete major
    public function delete($MaNganh)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaNganh = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $MaNganh);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

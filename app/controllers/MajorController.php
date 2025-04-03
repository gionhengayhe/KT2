<?php
// Include database and model files
require_once 'app/config/database.php';
require_once 'app/models/Major.php';

class MajorController
{
    private $major;
    private $conn;

    public function __construct()
    {
        // Get database connection
        $database = new Database();
        $this->conn = $database->getConnection();

        // Initialize object
        $this->major = new Major($this->conn);
    }

    // Display all majors
    public function index()
    {
        // Get all majors
        $majors = $this->major->getAll();

        // Define the statement helper object correctly
        $stmt = new class($majors) {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function fetchAll()
            {
                return $this->data;
            }

            public function rowCount()
            {
                return count($this->data);
            }
        };

        // Include view
        include_once 'app/views/major/index.php';
    }

    // Display major creation form
    public function create()
    {
        // Include view
        include_once 'app/views/major/create.php';
    }

    // Handle major creation form submission
    public function store()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Set major property values
            $MaNganh = $_POST['MaNganh'];
            $TenNganh = $_POST['TenNganh'];

            // Create the major
            if ($this->major->create($MaNganh, $TenNganh)) {
                header("Location: index.php?action=majors");
            } else {
                // Include view with error
                include_once 'app/views/major/create.php';
            }
        }
    }

    // Display major edit form
    public function edit($id)
    {
        // Read the details of major
        $major_data = $this->major->readOne($id);

        // Check if major exists
        if (!$major_data) {
            // Redirect to majors list if major not found
            header("Location: index.php?action=majors&error=major_not_found");
            exit;
        }

        // Prepare data for view with proper property checking
        $this->major = (object)[
            'MaNganh' => $major_data['MaNganh'] ?? '',
            'TenNganh' => $major_data['TenNganh'] ?? ''
        ];

        // Include view
        include_once 'app/views/major/edit.php';
    }

    // Handle major update form submission
    public function update()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Set major property values
            $MaNganh = $_POST['MaNganh'];
            $TenNganh = $_POST['TenNganh'];

            // Update the major
            if ($this->major->update($MaNganh, $TenNganh)) {
                header("Location: index.php?action=majors");
            } else {
                // Prepare data for view
                $this->major = (object)[
                    'MaNganh' => $MaNganh,
                    'TenNganh' => $TenNganh
                ];

                // Include view with error
                include_once 'app/views/major/edit.php';
            }
        }
    }

    // Handle major deletion
    public function delete($id)
    {
        // Delete the major
        if ($this->major->delete($id)) {
            header("Location: index.php?action=majors");
        } else {
            // Redirect with error
            header("Location: index.php?action=majors&error=delete_failed");
        }
    }
}

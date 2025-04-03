<?php
// Include database and model files
require_once 'app/config/database.php';
require_once 'app/models/Course.php';

class CourseController
{
    private $course;
    private $conn;

    public function __construct()
    {
        // Get database connection
        $database = new Database();
        $this->conn = $database->getConnection();

        // Initialize objects
        $this->course = new Course($this->conn);
    }

    // Display all courses
    public function index()
    {
        // Get all courses
        $courses = $this->course->getAll();

        // Define the statement helper object
        $stmt = new class($courses) {
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
        include_once 'app/views/course/index.php';
    }

    // Display course creation form
    public function create()
    {
        // Include view
        include_once 'app/views/course/create.php';
    }

    // Handle course creation form submission
    public function store()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Set course property values
            $MaHP = $_POST['MaHP'];
            $TenHP = $_POST['TenHP'];
            $SoTinChi = $_POST['SoTinChi'];
            $SoLuongDuKien = $_POST['SoLuongDuKien'];

            // Create the course
            if ($this->course->create($MaHP, $TenHP, $SoTinChi, $SoLuongDuKien)) {
                header("Location: index.php?action=courses");
                exit;
            } else {
                // Include view with error
                $error = "Không thể tạo học phần. Vui lòng thử lại.";
                include_once 'app/views/course/create.php';
            }
        }
    }

    // Display course edit form
    public function edit($id)
    {
        // Read the details of course
        $course_data = $this->course->readOne($id);

        // Check if course exists
        if (!$course_data) {
            // Redirect to courses list if course not found
            header("Location: index.php?action=courses&error=course_not_found");
            exit;
        }

        // Prepare data for view
        $this->course = (object)[
            'MaHP' => $course_data['MaHP'] ?? '',
            'TenHP' => $course_data['TenHP'] ?? '',
            'SoTinChi' => $course_data['SoTinChi'] ?? '',
            'SoLuongDuKien' => $course_data['SoLuongDuKien'] ?? ''
        ];

        // Include view
        include_once 'app/views/course/edit.php';
    }

    // Handle course update form submission
    public function update()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Set course property values
            $MaHP = $_POST['MaHP'];
            $TenHP = $_POST['TenHP'];
            $SoTinChi = $_POST['SoTinChi'];
            $SoLuongDuKien = $_POST['SoLuongDuKien'];

            // Update the course
            if ($this->course->update($MaHP, $TenHP, $SoTinChi, $SoLuongDuKien)) {
                header("Location: index.php?action=courses");
                exit;
            } else {
                // Prepare data for view
                $this->course = (object)[
                    'MaHP' => $MaHP,
                    'TenHP' => $TenHP,
                    'SoTinChi' => $SoTinChi,
                    'SoLuongDuKien' => $SoLuongDuKien
                ];

                // Include view with error
                $error = "Không thể cập nhật học phần. Vui lòng thử lại.";
                include_once 'app/views/course/edit.php';
            }
        }
    }

    // Handle course deletion
    public function delete($id)
    {
        // Delete the course
        if ($this->course->delete($id)) {
            header("Location: index.php?action=courses");
            exit;
        } else {
            // Redirect with error
            header("Location: index.php?action=courses&error=delete_failed");
            exit;
        }
    }
}

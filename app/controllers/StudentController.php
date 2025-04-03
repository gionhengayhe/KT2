<?php
// Include database and model files
require_once 'app/config/database.php';
require_once 'app/models/Student.php';
require_once 'app/models/Major.php';

class StudentController
{
    private $student;
    private $major;
    private $conn;

    public function __construct()
    {
        // Get database connection
        $database = new Database();
        $this->conn = $database->getConnection();

        // Initialize objects
        $this->student = new Student($this->conn);
        $this->major = new Major($this->conn);
    }

    // Display all students
    public function index()
    {
        // Items per page
        $limit = 4;

        // Get the current page from the URL
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calculate the offset
        $offset = ($page - 1) * $limit;

        // Get students for the current page
        $students = $this->student->getAll($limit, $offset);

        // Get the total number of students
        $total_students = $this->student->countAll();

        // Calculate total pages
        $total_pages = ceil($total_students / $limit);

        $stmt = new class($students) {
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

        // Get major list for dropdown
        $majors = $this->major->getAll();
        $major_stmt = new class($majors) {
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
        include_once 'app/views/student/index.php';
    }

    // Display student creation form
    public function create()
    {
        // Get all majors for dropdown
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
        include_once 'app/views/student/create.php';
    }

    // Handle student creation form submission
    public function store()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Set student property values
            $MaSV = $_POST['MaSV'];
            $HoTen = $_POST['HoTen'];
            $GioiTinh = $_POST['GioiTinh'];
            $NgaySinh = $_POST['NgaySinh'];
            $MaNganh = $_POST['MaNganh'];
            $Hinh = "";

            // Handle image upload
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (!empty($_FILES["Hinh"]["name"])) {
                $fileName = basename($_FILES["Hinh"]["name"]);
                $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = $MaSV . "." . $imageFileType;
                $target_file = $target_dir . $newFileName;

                // Check if image file is an actual image
                $check = getimagesize($_FILES["Hinh"]["tmp_name"]);
                if ($check !== false) {
                    // Try to upload file
                    if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                        $Hinh = $target_file; // Store full path
                    }
                }
            }

            // Create the student
            if ($this->student->create($MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh)) {
                header("Location: index.php?action=students");
            } else {
                // Get all majors for dropdown to redisplay form
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

                // Include view with error
                include_once 'app/views/student/create.php';
            }
        }
    }

    // Display student edit form
    public function edit($id)
    {
        // Read the details of student
        $student_data = $this->student->readOne($id);

        // Check if student exists
        if (!$student_data) {
            // Redirect to students list if student not found
            header("Location: index.php?action=students&error=student_not_found");
            exit;
        }

        // Prepare data for view by ensuring all required properties exist
        $this->student = (object)[
            'MaSV' => $student_data['MaSV'] ?? '',
            'HoTen' => $student_data['HoTen'] ?? '',
            'GioiTinh' => $student_data['GioiTinh'] ?? '',
            'NgaySinh' => $student_data['NgaySinh'] ?? '',
            'Hinh' => $student_data['Hinh'] ?? '',
            'MaNganh' => $student_data['MaNganh'] ?? '',
            'TenNganh' => $student_data['TenNganh'] ?? ''
        ];

        // Get all majors for dropdown
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
        include_once 'app/views/student/edit.php';
    }

    // Handle student update form submission
    public function update()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Set student property values
            $MaSV = $_POST['MaSV'];
            $HoTen = $_POST['HoTen'];
            $GioiTinh = $_POST['GioiTinh'];
            $NgaySinh = $_POST['NgaySinh'];
            $MaNganh = $_POST['MaNganh'];
            $Password = !empty($_POST['password']) ? $_POST['password'] : null;

            // Get current student data to check for existing image
            $currentStudent = $this->student->readOne($MaSV);
            $Hinh = $currentStudent['Hinh'] ?? '';

            // Handle image upload
            if (!empty($_FILES["Hinh"]["name"])) {
                $target_dir = "uploads/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $fileName = basename($_FILES["Hinh"]["name"]);
                $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = $MaSV . "." . $imageFileType;
                $target_file = $target_dir . $newFileName;

                // Check if image file is an actual image
                $check = getimagesize($_FILES["Hinh"]["tmp_name"]);
                if ($check !== false) {
                    // Delete old image if exists
                    if (!empty($currentStudent['Hinh']) && file_exists($currentStudent['Hinh'])) {
                        unlink($currentStudent['Hinh']);
                    }

                    // Try to upload file
                    if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                        $Hinh = $target_file; // Store full path
                    }
                }
            }

            // Update the student
            if ($this->student->update($MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $Password)) {
                header("Location: index.php?action=students");
            } else {
                // Get all majors for dropdown
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

                // Prepare data for view
                $this->student = (object)[
                    'MaSV' => $MaSV,
                    'HoTen' => $HoTen,
                    'GioiTinh' => $GioiTinh,
                    'NgaySinh' => $NgaySinh,
                    'Hinh' => $Hinh,
                    'MaNganh' => $MaNganh
                ];

                // Include view with error
                include_once 'app/views/student/edit.php';
            }
        }
    }

    // Handle student deletion
    public function delete($id)
    {
        // Get student data to delete image file
        $student_data = $this->student->readOne($id);

        // Delete the student
        if ($this->student->delete($id)) {
            // Delete image file if exists
            if (!empty($student_data['Hinh']) && file_exists($student_data['Hinh'])) {
                unlink($student_data['Hinh']);
            }

            header("Location: index.php?action=students");
        } else {
            // Redirect with error
            header("Location: index.php?action=students&error=delete_failed");
        }
    }

    // Display student details
    public function show($id)
    {
        // Read the details of student
        $student_data = $this->student->readOne($id);

        // Check if student exists
        if (!$student_data) {
            // Redirect to students list if student not found
            header("Location: index.php?action=students&error=student_not_found");
            exit;
        }

        // Prepare data for view by ensuring all required properties exist
        $this->student = (object)[
            'MaSV' => $student_data['MaSV'] ?? '',
            'HoTen' => $student_data['HoTen'] ?? '',
            'GioiTinh' => $student_data['GioiTinh'] ?? '',
            'NgaySinh' => $student_data['NgaySinh'] ?? '',
            'Hinh' => $student_data['Hinh'] ?? '',
            'MaNganh' => $student_data['MaNganh'] ?? '',
            'TenNganh' => $student_data['TenNganh'] ?? ''
        ];

        // Include view
        include_once 'app/views/student/show.php';
    }

    // Display login form
    public function loginForm()
    {
        // Check if already logged in
        if (isset($_SESSION['student_id'])) {
            header("Location: index.php?action=students");
            exit;
        }

        // Include login view
        include_once 'app/views/auth/login.php';
    }

    // Process login form
    public function login()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $MaSV = $_POST['MaSV'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate credentials
            $student = $this->student->authenticate($MaSV, $password);

            if ($student) {
                // Start session and store student data
                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['student_id'] = $student['MaSV'];
                $_SESSION['student_name'] = $student['HoTen'];
                $_SESSION['student_image'] = $student['Hinh'];

                // Redirect to students list
                header("Location: index.php?action=students");
                exit;
            } else {
                // Authentication failed
                $error = "Mã sinh viên hoặc mật khẩu không đúng";
                include_once 'app/views/auth/login.php';
            }
        }
    }

    // Logout user
    public function logout()
    {
        // Start session if not already started
        if (!isset($_SESSION)) {
            session_start();
        }

        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header("Location: index.php?action=login");
        exit;
    }
}

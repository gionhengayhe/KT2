<?php
// Include database and model files
require_once 'app/config/database.php';
require_once 'app/models/Registration.php';
require_once 'app/models/Course.php';
require_once 'app/models/Student.php';

class RegistrationController
{
    private $registration;
    private $course;
    private $student;
    private $conn;

    public function __construct()
    {
        // Get database connection
        $database = new Database();
        $this->conn = $database->getConnection();

        // Initialize objects
        $this->registration = new Registration($this->conn);
        $this->course = new Course($this->conn);
        $this->student = new Student($this->conn);
    }

    // Display registration form
    public function create()
    {
        // Get all available courses
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
        include_once 'app/views/registration/create.php';
    }

    // Handle registration form submission
    public function store()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get student ID from session
            $MaSV = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

            if (!$MaSV) {
                header("Location: index.php?action=login");
                exit;
            }

            // Check if courses were selected
            if (!isset($_POST['courses']) || empty($_POST['courses'])) {
                header("Location: index.php?action=courses&error=no_course_selected");
                exit;
            }

            // Create a new registration
            $MaDK = $this->registration->create($MaSV);

            if (!$MaDK) {
                header("Location: index.php?action=courses&error=registration_failed");
                exit;
            }

            // Process selected courses
            $selectedCourses = $_POST['courses'];
            $successCount = 0;
            $failedCourses = [];

            // Start transaction
            $this->conn->beginTransaction();

            try {
                foreach ($selectedCourses as $MaHP) {
                    // Check if course is already registered
                    if ($this->registration->isCourseRegistered($MaSV, $MaHP)) {
                        $failedCourses[] = $MaHP;
                        continue;
                    }

                    // Check if course has available slots
                    if (!$this->course->hasAvailableSlots($MaHP)) {
                        $failedCourses[] = $MaHP;
                        continue;
                    }

                    // Add course to registration
                    if ($this->registration->addCourse($MaDK, $MaHP)) {
                        // Decrease available slots
                        $this->course->decreaseQuantity($MaHP);
                        $successCount++;
                    } else {
                        $failedCourses[] = $MaHP;
                    }
                }

                // If no courses were registered successfully, rollback
                if ($successCount == 0) {
                    $this->conn->rollBack();
                    header("Location: index.php?action=courses&error=registration_failed");
                    exit;
                }

                // Commit transaction
                $this->conn->commit();

                // If this was a single course registration from course listing
                if (count($selectedCourses) == 1) {
                    header("Location: index.php?action=courses&success=registered");
                } else {
                    // Redirect to registration details
                    header("Location: index.php?action=registration_show&id=" . $MaDK);
                }
                exit;
            } catch (Exception $e) {
                $this->conn->rollBack();
                header("Location: index.php?action=courses&error=registration_error");
                exit;
            }
        }
    }

    // Display all registrations for current student
    public function index()
    {
        // Get student ID from session
        $MaSV = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

        if (!$MaSV) {
            header("Location: index.php?action=login");
            exit;
        }

        // Get all registrations for the student
        $registrations = $this->registration->getByStudent($MaSV);

        // Define the statement helper object
        $stmt = new class($registrations) {
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
        include_once 'app/views/registration/index.php';
    }

    // Display registration details
    public function show($id)
    {
        // Get registration details
        $registration_data = $this->registration->readOne($id);

        // Check if registration exists
        if (!$registration_data) {
            header("Location: index.php?action=registrations&error=registration_not_found");
            exit;
        }

        // Get registration courses
        $courses = $this->registration->getRegistrationDetails($id);

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
        include_once 'app/views/registration/show.php';
    }

    // Handle registration deletion
    public function delete($id)
    {
        // Delete the registration
        if ($this->registration->delete($id)) {
            header("Location: index.php?action=registrations");
            exit;
        } else {
            // Redirect with error
            header("Location: index.php?action=registrations&error=delete_failed");
            exit;
        }
    }

    // Handle deletion of a single course from registration
    public function deleteCourse($registrationId, $courseId)
    {
        // Delete the course from registration
        if ($this->registration->deleteCourse($registrationId, $courseId)) {
            // Check if any courses remain in the registration
            $registration = $this->registration->readOne($registrationId);

            if ($registration) {
                // Redirect back to the registration details
                header("Location: index.php?action=registration_show&id=" . $registrationId);
            } else {
                // If registration was automatically deleted (no courses left), redirect to the list
                header("Location: index.php?action=registrations");
            }
            exit;
        } else {
            // Redirect with error
            header("Location: index.php?action=registration_show&id=" . $registrationId . "&error=delete_course_failed");
            exit;
        }
    }
}

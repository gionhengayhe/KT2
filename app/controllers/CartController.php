<?php
// Include required files
require_once 'app/config/database.php';
require_once 'app/models/Cart.php';
require_once 'app/models/Course.php';
require_once 'app/models/Registration.php';

class CartController
{
    private $course;
    private $registration;
    private $conn;

    public function __construct()
    {
        // Get database connection
        $database = new Database();
        $this->conn = $database->getConnection();

        // Initialize objects
        $this->course = new Course($this->conn);
        $this->registration = new Registration($this->conn);
    }

    // Add a course to the cart
    public function addToCart()
    {
        // Check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if user is logged in
            if (!isset($_SESSION['student_id'])) {
                header("Location: index.php?action=login");
                exit;
            }

            // Get course ID
            if (!isset($_POST['MaHP'])) {
                header("Location: index.php?action=courses&error=missing_course");
                exit;
            }

            $MaHP = $_POST['MaHP'];

            // Check if student has already registered this course
            if ($this->registration->isCourseRegistered($_SESSION['student_id'], $MaHP)) {
                header("Location: index.php?action=courses&error=already_registered");
                exit;
            }

            // Get course details
            $course = $this->course->readOne($MaHP);

            if (!$course) {
                header("Location: index.php?action=courses&error=course_not_found");
                exit;
            }

            // Add to cart
            if (Cart::addCourse($course['MaHP'], $course['TenHP'], $course['SoTinChi'])) {
                header("Location: index.php?action=courses&success=added_to_cart");
            } else {
                header("Location: index.php?action=courses&info=already_in_cart");
            }
            exit;
        }
    }

    // Remove a course from the cart
    public function removeFromCart()
    {
        // Check if form was submitted or GET parameter exists
        if (isset($_GET['MaHP'])) {
            $MaHP = $_GET['MaHP'];

            // Remove from cart
            Cart::removeCourse($MaHP);

            // Redirect back to cart page
            header("Location: index.php?action=cart");
            exit;
        }
    }

    // View cart contents
    public function viewCart()
    {
        // Check if user is logged in
        if (!isset($_SESSION['student_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // Get cart courses
        $courses = Cart::getCourses();

        // Include view
        include_once 'app/views/cart/index.php';
    }

    // Checkout (save registration)
    public function checkout()
    {
        // Check if user is logged in
        if (!isset($_SESSION['student_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // Get cart courses
        $cartCourses = Cart::getCourses();

        // Check if cart is empty
        if (empty($cartCourses)) {
            header("Location: index.php?action=cart&error=empty_cart");
            exit;
        }

        // Create a new registration
        $MaDK = $this->registration->create($_SESSION['student_id']);

        if (!$MaDK) {
            header("Location: index.php?action=cart&error=registration_failed");
            exit;
        }

        // Start transaction
        $this->conn->beginTransaction();

        try {
            $successCount = 0;
            $failedCourses = [];

            foreach ($cartCourses as $course) {
                $MaHP = $course['MaHP'];

                // Check if course is already registered (double check)
                if ($this->registration->isCourseRegistered($_SESSION['student_id'], $MaHP)) {
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
                header("Location: index.php?action=cart&error=registration_failed");
                exit;
            }

            // Commit transaction
            $this->conn->commit();

            // Clear the cart
            Cart::clear();

            // Redirect to registration details
            header("Location: index.php?action=registration_show&id=" . $MaDK . "&success=registration_complete");
            exit;
        } catch (Exception $e) {
            $this->conn->rollBack();
            header("Location: index.php?action=cart&error=registration_error");
            exit;
        }
    }

    // Clear the cart
    public function clearCart()
    {
        Cart::clear();
        header("Location: index.php?action=cart&info=cart_cleared");
        exit;
    }
}

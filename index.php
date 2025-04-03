<?php
// Start session
session_start();

// Include controller files
include_once 'app/controllers/StudentController.php';
include_once 'app/controllers/MajorController.php';
include_once 'app/controllers/CourseController.php';
include_once 'app/controllers/RegistrationController.php';
include_once 'app/controllers/CartController.php';

// Get action from URL parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Initialize controllers
$studentController = new StudentController();
$majorController = new MajorController();
$courseController = new CourseController();
$registrationController = new RegistrationController();
$cartController = new CartController();

// Authentication middleware for protected routes
function requireLogin()
{
    if (!isset($_SESSION['student_id'])) {
        header("Location: index.php?action=login");
        exit;
    }
}

// Route the request to the appropriate controller method
switch ($action) {
    // Auth routes
    case 'login':
        $studentController->loginForm();
        break;

    case 'login_process':
        $studentController->login();
        break;

    case 'logout':
        $studentController->logout();
        break;

    // Student routes
    case 'students':
        requireLogin(); // Protect this route
        $studentController->index();
        break;

    case 'student_create':
        requireLogin(); // Protect this route
        $studentController->create();
        break;

    case 'student_store':
        requireLogin(); // Protect this route
        $studentController->store();
        break;

    case 'student_edit':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $studentController->edit($id);
        break;

    case 'student_update':
        requireLogin(); // Protect this route
        $studentController->update();
        break;

    case 'student_delete':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $studentController->delete($id);
        break;

    case 'student_show':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $studentController->show($id);
        break;

    // Major routes
    case 'majors':
        requireLogin(); // Protect this route
        $majorController->index();
        break;

    case 'major_create':
        requireLogin(); // Protect this route
        $majorController->create();
        break;

    case 'major_store':
        requireLogin(); // Protect this route
        $majorController->store();
        break;

    case 'major_edit':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $majorController->edit($id);
        break;

    case 'major_update':
        requireLogin(); // Protect this route
        $majorController->update();
        break;

    case 'major_delete':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $majorController->delete($id);
        break;

    // Course routes
    case 'courses':
        requireLogin(); // Protect this route
        $courseController->index();
        break;

    // Admin-only course management routes
    case 'course_create':
    case 'course_store':
    case 'course_edit':
    case 'course_update':
    case 'course_delete':
        // These routes are disabled for students
        header("Location: index.php?action=courses");
        exit;
        break;

    // Registration routes
    case 'registrations':
        requireLogin(); // Protect this route
        $registrationController->index();
        break;

    case 'registration_create':
        requireLogin(); // Protect this route
        $registrationController->create();
        break;

    case 'registration_store':
        requireLogin(); // Protect this route
        $registrationController->store();
        break;

    case 'registration_show':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $registrationController->show($id);
        break;

    case 'registration_delete':
        requireLogin(); // Protect this route
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $registrationController->delete($id);
        break;

    case 'registration_delete_course':
        requireLogin(); // Protect this route
        $registrationId = isset($_GET['registration_id']) ? $_GET['registration_id'] : die('ERROR: Missing Registration ID.');
        $courseId = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: Missing Course ID.');
        $registrationController->deleteCourse($registrationId, $courseId);
        break;

    // Cart routes
    case 'cart':
        requireLogin(); // Protect this route
        $cartController->viewCart();
        break;

    case 'cart_add':
        requireLogin(); // Protect this route
        $cartController->addToCart();
        break;

    case 'cart_remove':
        requireLogin(); // Protect this route
        $cartController->removeFromCart();
        break;

    case 'cart_clear':
        requireLogin(); // Protect this route
        $cartController->clearCart();
        break;

    case 'cart_checkout':
        requireLogin(); // Protect this route
        $cartController->checkout();
        break;

    // Default route
    default:
        $studentController->loginForm();
        break;
}

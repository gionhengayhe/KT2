<?php
// Only start the session if one isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Cart
{
    // Add a course to the cart
    public static function addCourse($MaHP, $TenHP, $SoTinChi)
    {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if course is already in cart
        if (!isset($_SESSION['cart'][$MaHP])) {
            $_SESSION['cart'][$MaHP] = array(
                'MaHP' => $MaHP,
                'TenHP' => $TenHP,
                'SoTinChi' => $SoTinChi
            );
            return true;
        }

        return false; // Already in cart
    }

    // Remove a course from the cart
    public static function removeCourse($MaHP)
    {
        if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$MaHP])) {
            unset($_SESSION['cart'][$MaHP]);
            return true;
        }

        return false;
    }

    // Get all courses in the cart
    public static function getCourses()
    {
        if (isset($_SESSION['cart'])) {
            return $_SESSION['cart'];
        }

        return array();
    }

    // Check if a course is in the cart
    public static function hasCourse($MaHP)
    {
        return isset($_SESSION['cart']) && isset($_SESSION['cart'][$MaHP]);
    }

    // Count courses in the cart
    public static function count()
    {
        if (isset($_SESSION['cart'])) {
            return count($_SESSION['cart']);
        }

        return 0;
    }

    // Clear the cart
    public static function clear()
    {
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }
}

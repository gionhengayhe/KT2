<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 20px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .btn-action {
            margin: 2px;
        }

        .student-image {
            max-width: 100px;
            max-height: 100px;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <?php
    // Start session if not already started
    if (!isset($_SESSION)) {
        session_start();
    }

    // Include Cart model for cart count
    if (file_exists('app/models/Cart.php')) {
        require_once 'app/models/Cart.php';
    }
    ?>
    <div class="container">
        <header class="mb-4">
            <h1 class="text-center">Hệ Thống Quản Lý Sinh Viên</h1>
        </header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=students">Danh Sách Sinh Viên</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=majors">Danh Sách Ngành Học</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=courses">Danh Sách Học Phần</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=registrations">Đăng Ký Của Tôi</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['student_id'])): ?>
                            <?php if (class_exists('Cart') && Cart::count() > 0): ?>
                                <li class="nav-item">
                                    <a class="nav-link position-relative" href="index.php?action=cart">
                                        <i class="bi bi-cart"></i> Đăng Ký
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            <?php echo Cart::count(); ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php if (!empty($_SESSION['student_image']) && file_exists($_SESSION['student_image'])): ?>
                                        <img src="<?php echo $_SESSION['student_image']; ?>" alt="Avatar" class="user-avatar">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle me-1"></i>
                                    <?php endif; ?>
                                    <?php echo $_SESSION['student_name']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="index.php?action=student_show&id=<?php echo $_SESSION['student_id']; ?>">Hồ sơ</a></li>
                                    <li><a class="dropdown-item" href="index.php?action=registrations">Đăng Ký Của Tôi</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="index.php?action=logout">Đăng xuất</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=login">
                                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
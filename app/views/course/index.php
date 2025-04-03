<?php include_once 'app/views/layout/header.php'; ?>
<?php require_once 'app/models/Cart.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>DANH SÁCH HỌC PHẦN</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=cart" class="btn btn-primary position-relative">
            <i class="bi bi-cart"></i> Đăng Ký
            <?php if (Cart::count() > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo Cart::count(); ?>
                </span>
            <?php endif; ?>
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        $success_message = "Thao tác thành công!";

        if ($_GET['success'] == 'registered') {
            $success_message = "Đăng ký học phần thành công!";
        } elseif ($_GET['success'] == 'added_to_cart') {
            $success_message = "Đã thêm học phần vào giỏ đăng ký!";
        }

        echo $success_message;
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php
        $error_message = "Đã xảy ra lỗi. Vui lòng thử lại.";

        if ($_GET['error'] == 'no_course_selected') {
            $error_message = "Vui lòng chọn ít nhất một học phần.";
        } elseif ($_GET['error'] == 'registration_failed') {
            $error_message = "Không thể đăng ký học phần. Vui lòng thử lại.";
        } elseif ($_GET['error'] == 'registration_error') {
            $error_message = "Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.";
        } elseif ($_GET['error'] == 'already_registered') {
            $error_message = "Bạn đã đăng ký học phần này rồi.";
        } elseif ($_GET['error'] == 'course_not_found') {
            $error_message = "Không tìm thấy học phần.";
        } elseif ($_GET['error'] == 'missing_course') {
            $error_message = "Thiếu thông tin học phần.";
        }

        echo $error_message;
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['info'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php
        $info_message = "Thông báo.";

        if ($_GET['info'] == 'already_in_cart') {
            $info_message = "Học phần này đã có trong giỏ đăng ký của bạn.";
        }

        echo $info_message;
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mã Học Phần</th>
                        <th>Tên Học Phần</th>
                        <th>Số Tín Chỉ</th>
                        <th>Số Lượng Dự Kiến</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        $courses = $stmt->fetchAll();
                        foreach ($courses as $course):
                            // Disable button if course has no available slots
                            $disabled = ($course['SoLuongDuKien'] <= 0) ? 'disabled' : '';

                            // Disable and show different text if course is already in cart
                            $inCart = Cart::hasCourse($course['MaHP']);
                            if ($inCart) {
                                $disabled = 'disabled';
                            }
                    ?>
                            <tr>
                                <td><?php echo $course['MaHP']; ?></td>
                                <td><?php echo $course['TenHP']; ?></td>
                                <td><?php echo $course['SoTinChi']; ?></td>
                                <td>
                                    <?php if ($course['SoLuongDuKien'] <= 0): ?>
                                        <span class="text-danger">Đã hết</span>
                                    <?php else: ?>
                                        <?php echo $course['SoLuongDuKien']; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($inCart): ?>
                                        <button class="btn btn-outline-success btn-sm" disabled>
                                            <i class="bi bi-check"></i> Đã trong giỏ
                                        </button>
                                    <?php else: ?>
                                        <form action="index.php?action=cart_add" method="post">
                                            <input type="hidden" name="MaHP" value="<?php echo $course['MaHP']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm" <?php echo $disabled; ?>>
                                                Đăng ký
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class="text-center">Không có học phần nào</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (Cart::count() > 0): ?>
    <div class="mt-4 text-center">
        <a href="index.php?action=cart" class="btn btn-primary">
            <i class="bi bi-cart"></i> Xem giỏ đăng ký (<?php echo Cart::count(); ?> học phần)
        </a>
    </div>
<?php endif; ?>

<?php include_once 'app/views/layout/footer.php'; ?>
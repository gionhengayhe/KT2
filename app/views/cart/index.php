<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Đăng Ký Học Phần</h2>
    </div>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php
        $error_message = "Đã xảy ra lỗi. Vui lòng thử lại.";

        if ($_GET['error'] == 'empty_cart') {
            $error_message = "Đăng ký trống. Vui lòng thêm học phần trước khi đăng ký.";
        } elseif ($_GET['error'] == 'registration_failed') {
            $error_message = "Không thể hoàn tất đăng ký. Vui lòng thử lại.";
        } elseif ($_GET['error'] == 'registration_error') {
            $error_message = "Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.";
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

        if ($_GET['info'] == 'cart_cleared') {
            $info_message = "Giỏ đã được xóa.";
        }

        echo $info_message;
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <?php if (!empty($courses)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Mã Học Phần</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalCredits = 0;
                        foreach ($courses as $course):
                            $totalCredits += $course['SoTinChi'];
                        ?>
                            <tr>
                                <td><?php echo $course['MaHP']; ?></td>
                                <td><?php echo $course['TenHP']; ?></td>
                                <td><?php echo $course['SoTinChi']; ?></td>
                                <td>
                                    <a href="index.php?action=cart_remove&MaHP=<?php echo $course['MaHP']; ?>" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary">
                            <td colspan="2" class="text-end"><strong>Tổng số tín chỉ:</strong></td>
                            <td><strong><?php echo $totalCredits; ?></strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="index.php?action=courses" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Tiếp tục chọn học phần
                </a>
                <div>
                    <a href="index.php?action=cart_clear" class="btn btn-warning me-2" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả học phần trong giỏ không?')">
                        <i class="bi bi-x-circle"></i> Xóa giỏ đăng ký
                    </a>
                    <a href="index.php?action=cart_checkout" class="btn btn-success" onclick="return confirm('Xác nhận đăng ký các học phần đã chọn?')">
                        <i class="bi bi-check-circle"></i> Lưu đăng ký
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <p class="mb-0">Giỏ đăng ký của bạn đang trống.</p>
            </div>
            <div class="text-center">
                <a href="index.php?action=courses" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Chọn học phần
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>
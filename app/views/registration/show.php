<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Chi Tiết Đăng Ký Học Phần</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=registrations" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'delete_course_failed'): ?>
    <div class="alert alert-danger">
        Không thể xóa học phần từ đăng ký. Vui lòng thử lại.
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h5>Thông Tin Đăng Ký</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Mã Đăng Ký:</strong> <?php echo $registration_data['MaDK']; ?></p>
                <p><strong>Mã Sinh Viên:</strong> <?php echo $registration_data['MaSV']; ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Họ Tên:</strong> <?php echo $registration_data['HoTen']; ?></p>
                <p><strong>Ngày Đăng Ký:</strong> <?php echo date('d/m/Y', strtotime($registration_data['NgayDK'])); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Danh Sách Học Phần Đã Đăng Ký</h5>
    </div>
    <div class="card-body">
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
                    if ($stmt->rowCount() > 0) {
                        $totalCredits = 0;
                        $courses = $stmt->fetchAll();
                        foreach ($courses as $course):
                            $totalCredits += $course['SoTinChi'];
                    ?>
                            <tr>
                                <td><?php echo $course['MaHP']; ?></td>
                                <td><?php echo $course['TenHP']; ?></td>
                                <td><?php echo $course['SoTinChi']; ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger"
                                        onclick="confirmDeleteCourse('<?php echo $registration_data['MaDK']; ?>', '<?php echo $course['MaHP']; ?>')">
                                        <i class="bi bi-trash"></i> Hủy Đăng Ký
                                    </a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                        ?>
                        <tr class="table-primary">
                            <td colspan="2" class="text-end"><strong>Tổng số tín chỉ:</strong></td>
                            <td><strong><?php echo $totalCredits; ?></strong></td>
                            <td></td>
                        </tr>
                    <?php
                    } else {
                    ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có học phần nào trong đăng ký này</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 text-end">
    <a href="javascript:void(0);" class="btn btn-danger"
        onclick="confirmDelete('<?php echo $registration_data['MaDK']; ?>')">
        <i class="bi bi-trash"></i> Hủy Tất Cả Đăng Ký
    </a>
    <a href="index.php?action=registrations" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay Lại
    </a>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn hủy đăng ký này không? Tất cả học phần sẽ bị hủy.")) {
            window.location.href = "index.php?action=registration_delete&id=" + id;
        }
    }

    function confirmDeleteCourse(registrationId, courseId) {
        if (confirm("Bạn có chắc chắn muốn hủy đăng ký học phần này không?")) {
            window.location.href = "index.php?action=registration_delete_course&registration_id=" + registrationId + "&course_id=" + courseId;
        }
    }
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
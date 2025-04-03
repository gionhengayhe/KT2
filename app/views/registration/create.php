<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Đăng Ký Học Phần</h2>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=registration_store" method="post">
            <div class="mb-3">
                <h5 class="card-title">Danh Sách Học Phần</h5>
                <p class="text-muted">Chọn các học phần bạn muốn đăng ký</p>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">Chọn</th>
                                <th>Mã HP</th>
                                <th>Tên Học Phần</th>
                                <th>Số Tín Chỉ</th>
                                <th>Còn Lại</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($stmt->rowCount() > 0) {
                                $courses = $stmt->fetchAll();
                                foreach ($courses as $course):
                                    // Disable if no slots available
                                    $disabled = ($course['SoLuongDuKien'] <= 0) ? 'disabled' : '';
                            ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="courses[]"
                                                    value="<?php echo $course['MaHP']; ?>" <?php echo $disabled; ?>>
                                            </div>
                                        </td>
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

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Lưu Đăng Ký
                </button>
                <a href="index.php?action=registrations" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>
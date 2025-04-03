<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Thêm Học Phần Mới</h2>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=course_store" method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="MaHP" class="form-label">Mã Học Phần:</label>
                        <input type="text" class="form-control" id="MaHP" name="MaHP" required maxlength="6">
                        <small class="text-muted">Tối đa 6 ký tự</small>
                    </div>

                    <div class="mb-3">
                        <label for="TenHP" class="form-label">Tên Học Phần:</label>
                        <input type="text" class="form-control" id="TenHP" name="TenHP" required maxlength="30">
                        <small class="text-muted">Tối đa 30 ký tự</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="SoTinChi" class="form-label">Số Tín Chỉ:</label>
                        <input type="number" class="form-control" id="SoTinChi" name="SoTinChi" required min="1" max="10">
                    </div>

                    <div class="mb-3">
                        <label for="SoLuongDuKien" class="form-label">Số Lượng Dự Kiến:</label>
                        <input type="number" class="form-control" id="SoLuongDuKien" name="SoLuongDuKien" required min="0">
                    </div>
                </div>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Lưu
                </button>
                <a href="index.php?action=courses" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>
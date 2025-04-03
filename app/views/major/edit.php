<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Chỉnh Sửa Ngành Học</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="index.php?action=major_update" method="post">
                    <div class="mb-3">
                        <label for="MaNganh" class="form-label">Mã Ngành:</label>
                        <input type="text" class="form-control" id="MaNganh" name="MaNganh" value="<?php echo $this->major->MaNganh; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="TenNganh" class="form-label">Tên Ngành:</label>
                        <input type="text" class="form-control" id="TenNganh" name="TenNganh" value="<?php echo $this->major->TenNganh; ?>" maxlength="30" required>
                        <small class="text-muted">Tối đa 30 ký tự</small>
                    </div>

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Cập Nhật
                        </button>
                        <a href="index.php?action=majors" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>
<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Thêm Ngành Học Mới</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="index.php?action=major_store" method="post">
                    <div class="mb-3">
                        <label for="MaNganh" class="form-label">Mã Ngành:</label>
                        <input type="text" class="form-control" id="MaNganh" name="MaNganh" maxlength="4" required>
                        <small class="text-muted">Tối đa 4 ký tự</small>
                    </div>

                    <div class="mb-3">
                        <label for="TenNganh" class="form-label">Tên Ngành:</label>
                        <input type="text" class="form-control" id="TenNganh" name="TenNganh" maxlength="30" required>
                        <small class="text-muted">Tối đa 30 ký tự</small>
                    </div>

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
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
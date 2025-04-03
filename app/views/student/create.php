<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Thêm Sinh Viên Mới</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="index.php?action=student_store" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="MaSV" class="form-label">Mã Sinh Viên:</label>
                        <input type="text" class="form-control" id="MaSV" name="MaSV" required>
                    </div>

                    <div class="mb-3">
                        <label for="HoTen" class="form-label">Họ Tên:</label>
                        <input type="text" class="form-control" id="HoTen" name="HoTen" required>
                    </div>

                    <div class="mb-3">
                        <label for="GioiTinh" class="form-label">Giới Tính:</label>
                        <select class="form-select" id="GioiTinh" name="GioiTinh">
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="NgaySinh" class="form-label">Ngày Sinh:</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
                    </div>

                    <div class="mb-3">
                        <label for="Hinh" class="form-label">Hình:</label>
                        <input type="file" class="form-control" id="Hinh" name="Hinh">
                    </div>

                    <div class="mb-3">
                        <label for="MaNganh" class="form-label">Ngành Học:</label>
                        <select class="form-select" id="MaNganh" name="MaNganh" required>
                            <option value="">Chọn ngành học</option>
                            <?php
                            $majors = $stmt->fetchAll();
                            foreach ($majors as $row):
                            ?>
                                <option value="<?php echo $row['MaNganh']; ?>"><?php echo $row['TenNganh']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Lưu
                </button>
                <a href="index.php?action=students" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>
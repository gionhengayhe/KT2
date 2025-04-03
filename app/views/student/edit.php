<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Chỉnh Sửa Sinh Viên</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="index.php?action=student_update" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="MaSV" class="form-label">Mã Sinh Viên:</label>
                        <input type="text" class="form-control" id="MaSV" name="MaSV" value="<?php echo $this->student->MaSV; ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="HoTen" class="form-label">Họ Tên:</label>
                        <input type="text" class="form-control" id="HoTen" name="HoTen" value="<?php echo $this->student->HoTen; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="GioiTinh" class="form-label">Giới Tính:</label>
                        <select class="form-select" id="GioiTinh" name="GioiTinh">
                            <option value="Nam" <?php echo ($this->student->GioiTinh === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo ($this->student->GioiTinh === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="NgaySinh" class="form-label">Ngày Sinh:</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" value="<?php echo $this->student->NgaySinh; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="Hinh" class="form-label">Hình:</label>
                        <?php if (!empty($this->student->Hinh) && file_exists($this->student->Hinh)): ?>
                            <div class="mb-2">
                                <img src="<?php echo $this->student->Hinh; ?>" alt="Student Image" class="student-image img-thumbnail">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="Hinh" name="Hinh">
                        <small class="text-muted">Để trống nếu không muốn thay đổi hình</small>
                    </div>

                    <div class="mb-3">
                        <label for="MaNganh" class="form-label">Ngành Học:</label>
                        <select class="form-select" id="MaNganh" name="MaNganh" required>
                            <option value="">Chọn ngành học</option>
                            <?php
                            $majors = $stmt->fetchAll();
                            foreach ($majors as $major):
                            ?>
                                <option value="<?php echo $major['MaNganh']; ?>" <?php echo ($this->student->MaNganh === $major['MaNganh']) ? 'selected' : ''; ?>>
                                    <?php echo $major['TenNganh']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu mới (để trống nếu không thay đổi)">
                        <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu.</div>
                    </div>
                </div>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Cập Nhật
                </button>
                <a href="index.php?action=students" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>
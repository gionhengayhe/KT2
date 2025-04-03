<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Chi Tiết Sinh Viên</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=students" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 text-center">
        <?php if (!empty($this->student->Hinh) && file_exists($this->student->Hinh)): ?>
            <img src="<?php echo $this->student->Hinh; ?>" alt="Student Image" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
        <?php else: ?>
            <img src="uploads/default.jpg" alt="No Image" class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
        <?php endif; ?>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0"><?php echo $this->student->HoTen; ?></h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 30%;">Mã Sinh Viên:</th>
                        <td><?php echo $this->student->MaSV; ?></td>
                    </tr>
                    <tr>
                        <th>Giới Tính:</th>
                        <td><?php echo $this->student->GioiTinh; ?></td>
                    </tr>
                    <tr>
                        <th>Ngày Sinh:</th>
                        <td><?php echo date("d/m/Y", strtotime($this->student->NgaySinh)); ?></td>
                    </tr>
                    <tr>
                        <th>Ngành Học:</th>
                        <td>
                            <?php
                            // Display major name from the TenNganh field in the student object
                            echo $this->student->TenNganh ?? 'Không có thông tin';
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="index.php?action=student_edit&id=<?php echo $this->student->MaSV; ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Chỉnh Sửa
                    </a>
                    <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $this->student->MaSV; ?>')" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sinh viên này?")) {
            window.location.href = "index.php?action=student_delete&id=" + id;
        }
    }
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
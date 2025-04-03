<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Danh Sách Sinh Viên</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=student_create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Sinh Viên Mới
        </a>
    </div>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] === 'delete_failed'): ?>
    <div class="alert alert-danger" role="alert">
        Không thể xóa sinh viên. Vui lòng thử lại sau!
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã SV</th>
                <th>Hình</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Ngành Học</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Get all students using the fetchAll method of our custom object
            $students = $stmt->fetchAll();
            foreach ($students as $row):
            ?>
                <tr>
                    <td><?php echo $row['MaSV']; ?></td>
                    <td>
                        <?php if (!empty($row['Hinh']) && file_exists($row['Hinh'])): ?>
                            <img src="<?php echo $row['Hinh']; ?>" alt="Student Image" class="student-image img-thumbnail">
                        <?php else: ?>
                            <img src="/uploads/default.jpg" alt="No Image" class="student-image img-thumbnail">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['HoTen']; ?></td>
                    <td><?php echo $row['GioiTinh']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row['NgaySinh'])); ?></td>
                    <td><?php echo $row['TenNganh']; ?></td>
                    <td>
                        <a href="index.php?action=student_show&id=<?php echo $row['MaSV']; ?>" class="btn btn-info btn-sm btn-action">
                            <i class="bi bi-eye"></i> Chi Tiết
                        </a>
                        <a href="index.php?action=student_edit&id=<?php echo $row['MaSV']; ?>" class="btn btn-warning btn-sm btn-action">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $row['MaSV']; ?>')" class="btn btn-danger btn-sm btn-action">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($students) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Không có dữ liệu sinh viên</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?action=students&page=<?php echo ($page - 1); ?>">
                        <i class="bi bi-chevron-left"></i> Trước
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?action=students&page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="index.php?action=students&page=<?php echo ($page + 1); ?>">
                        Tiếp <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sinh viên này?")) {
            window.location.href = "index.php?action=student_delete&id=" + id;
        }
    }
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
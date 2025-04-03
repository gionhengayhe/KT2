<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Danh Sách Ngành Học</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=major_create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Ngành Học Mới
        </a>
    </div>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] === 'delete_failed'): ?>
    <div class="alert alert-danger" role="alert">
        Không thể xóa ngành học. Có thể có sinh viên thuộc ngành này!
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Mã Ngành</th>
                <th>Tên Ngành</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Get all majors using the fetchAll method of our custom object
            $majors = $stmt->fetchAll();
            foreach ($majors as $row):
            ?>
                <tr>
                    <td><?php echo $row['MaNganh']; ?></td>
                    <td><?php echo $row['TenNganh']; ?></td>
                    <td>
                        <a href="index.php?action=major_edit&id=<?php echo $row['MaNganh']; ?>" class="btn btn-warning btn-sm btn-action">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $row['MaNganh']; ?>')" class="btn btn-danger btn-sm btn-action">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (count($majors) === 0): ?>
                <tr>
                    <td colspan="3" class="text-center">Không có dữ liệu ngành học</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa ngành học này?")) {
            window.location.href = "index.php?action=major_delete&id=" + id;
        }
    }
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
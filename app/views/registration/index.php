<?php include_once 'app/views/layout/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Danh Sách Đăng Ký Học Phần</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="index.php?action=registration_create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Đăng Ký Học Phần Mới
        </a>
    </div>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
    <div class="alert alert-danger">
        Không thể hủy đăng ký. Vui lòng thử lại.
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mã Đăng Ký</th>
                        <th>Ngày Đăng Ký</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        $registrations = $stmt->fetchAll();
                        foreach ($registrations as $registration):
                    ?>
                            <tr>
                                <td><?php echo $registration['MaDK']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($registration['NgayDK'])); ?></td>
                                <td>
                                    <a href="index.php?action=registration_show&id=<?php echo $registration['MaDK']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Chi Tiết
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('<?php echo $registration['MaDK']; ?>')">
                                        <i class="bi bi-trash"></i> Hủy Đăng Ký
                                    </a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    } else {
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">Bạn chưa đăng ký học phần nào</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn hủy đăng ký này không?")) {
            window.location.href = "index.php?action=registration_delete&id=" + id;
        }
    }
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
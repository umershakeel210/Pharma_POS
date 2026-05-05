<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

$result = $conn->query("SELECT * FROM medicines ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Medicines</h3>
        <p class="text-muted mb-0">Manage all medicines, stock, batch and expiry details</p>
    </div>

    <a href="add.php" class="btn btn-primary">
        + Add Medicine
    </a>
</div>

<div class="card shadow">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Expiry</th>
                        <th>Purchase</th>
                        <th>Sale</th>
                        <th>Stock</th>
                        <th>Rx</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>

                            <?php
                            $stock_badge = "bg-success";
                            if ($row['stock_quantity'] <= 10) {
                                $stock_badge = "bg-warning text-dark";
                            }
                            if ($row['stock_quantity'] <= 0) {
                                $stock_badge = "bg-danger";
                            }

                            $expiry_badge = "bg-success";
                            $expiry_text = "OK";

                            if ($row['expiry_date'] < date("Y-m-d")) {
                                $expiry_badge = "bg-danger";
                                $expiry_text = "Expired";
                            } elseif ($row['expiry_date'] <= date("Y-m-d", strtotime("+30 days"))) {
                                $expiry_badge = "bg-warning text-dark";
                                $expiry_text = "Near Expiry";
                            }
                            ?>

                            <tr>
                                <td><?= $row['id']; ?></td>

                                <td>
                                    <strong><?= $row['medicine_name']; ?></strong><br>
                                    <small class="text-muted">
                                        <?= $row['generic_name']; ?> 
                                        <?= !empty($row['company_name']) ? " | " . $row['company_name'] : ""; ?>
                                    </small>
                                </td>

                                <td><?= $row['batch_no']; ?></td>

                                <td>
                                    <?= $row['expiry_date']; ?><br>
                                    <span class="badge <?= $expiry_badge; ?>">
                                        <?= $expiry_text; ?>
                                    </span>
                                </td>

                                <td><?= number_format($row['purchase_price'], 2); ?></td>

                                <td>
                                    <strong><?= number_format($row['sale_price'], 2); ?></strong>
                                </td>

                                <td>
                                    <span class="badge <?= $stock_badge; ?>">
                                        <?= $row['stock_quantity']; ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $row['prescription_required'] == 1 
                                        ? '<span class="badge bg-info text-dark">Yes</span>' 
                                        : '<span class="badge bg-secondary">No</span>'; ?>
                                </td>

                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <a href="delete.php?id=<?= $row['id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this medicine?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No medicines found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php require_once "../layouts/footer.php"; ?>
<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

$result = $conn->query("SELECT * FROM medicines ORDER BY medicine_name ASC");

$total_stock_value = 0;
?>

<h3 class="mb-4">Stock Report</h3>

<div class="card shadow">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Expiry Date</th>
                    <th>Stock Qty</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Stock Value</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>

                        <?php
                        $stock_value = $row['stock_quantity'] * $row['purchase_price'];
                        $total_stock_value += $stock_value;

                        // Expiry check
                        $status = "OK";
                        if ($row['expiry_date'] < date("Y-m-d")) {
                            $status = "<span class='text-danger'>Expired</span>";
                        } elseif ($row['expiry_date'] <= date("Y-m-d", strtotime("+30 days"))) {
                            $status = "<span class='text-warning'>Near Expiry</span>";
                        }
                        ?>

                        <tr>
                            <td><?= $row['medicine_name']; ?></td>
                            <td><?= $row['batch_no']; ?></td>
                            <td><?= $row['expiry_date']; ?></td>
                            <td><?= $row['stock_quantity']; ?></td>
                            <td><?= number_format($row['purchase_price'], 2); ?></td>
                            <td><?= number_format($row['sale_price'], 2); ?></td>
                            <td><?= number_format($stock_value, 2); ?></td>
                            <td><?= $status; ?></td>
                        </tr>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No data found</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <tfoot class="table-dark">
                <tr>
                    <th colspan="6">Total Stock Value</th>
                    <th><?= number_format($total_stock_value, 2); ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>

<?php require_once "../layouts/footer.php"; ?>
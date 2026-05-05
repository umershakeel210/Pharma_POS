<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

$where = "";
$from_date = "";
$to_date = "";

if (isset($_GET['filter'])) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];

    if (!empty($from_date) && !empty($to_date)) {
        $from_date_safe = $conn->real_escape_string($from_date);
        $to_date_safe = $conn->real_escape_string($to_date);

        $where = "WHERE DATE(s.sale_date) BETWEEN '$from_date_safe' AND '$to_date_safe'";
    }
}

$sql = "SELECT 
            si.*,
            s.sale_date,
            s.subtotal,
            s.discount,
            m.medicine_name,
            m.batch_no,
            m.purchase_price AS medicine_purchase_price,

            CASE
                WHEN si.purchase_price IS NULL OR si.purchase_price = 0
                THEN m.purchase_price
                ELSE si.purchase_price
            END AS report_purchase_price

        FROM sale_items si
        JOIN sales s ON si.sale_id = s.id
        JOIN medicines m ON si.medicine_id = m.id
        $where
        ORDER BY s.sale_date DESC, si.id DESC";

$result = $conn->query($sql);

$total_cost = 0;
$total_sale_total = 0;
$total_discount_share = 0;
$total_final_sale = 0;
$total_profit = 0;
?>

<h3 class="mb-4">Profit Report</h3>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" class="row">

            <div class="col-md-4">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($from_date); ?>">
            </div>

            <div class="col-md-4">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($to_date); ?>">
            </div>

            <div class="col-md-4 mt-4">
                <button type="submit" name="filter" class="btn btn-primary">
                    Filter
                </button>

                <a href="profit.php" class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Qty</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Sale Total</th>
                    <th>Discount Share</th>
                    <th>Final Sale</th>
                    <th>Cost</th>
                    <th>Profit</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>

                        <?php
                        $qty = (int)$row['quantity'];

                        $purchase_price = (float)$row['report_purchase_price'];
                        $sale_price = (float)$row['sale_price'];

                        $sale_total = $sale_price * $qty;
                        $cost_total = $purchase_price * $qty;

                        $discount_share = 0;

                        if ((float)$row['subtotal'] > 0) {
                            $discount_share = ($sale_total / (float)$row['subtotal']) * (float)$row['discount'];
                        }

                        $final_sale = $sale_total - $discount_share;
                        $profit = $final_sale - $cost_total;

                        $total_sale_total += $sale_total;
                        $total_discount_share += $discount_share;
                        $total_cost += $cost_total;
                        $total_final_sale += $final_sale;
                        $total_profit += $profit;
                        ?>

                        <tr>
                            <td><?= date('d-m-Y', strtotime($row['sale_date'])); ?></td>
                            <td><?= htmlspecialchars($row['medicine_name']); ?></td>
                            <td><?= htmlspecialchars($row['batch_no']); ?></td>
                            <td><?= $qty; ?></td>
                            <td><?= number_format($purchase_price, 2); ?></td>
                            <td><?= number_format($sale_price, 2); ?></td>
                            <td><?= number_format($sale_total, 2); ?></td>
                            <td><?= number_format($discount_share, 2); ?></td>
                            <td><?= number_format($final_sale, 2); ?></td>
                            <td><?= number_format($cost_total, 2); ?></td>
                            <td>
                                <strong><?= number_format($profit, 2); ?></strong>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No sales found</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <tfoot class="table-dark">
                <tr>
                    <th colspan="6">Grand Total</th>
                    <th><?= number_format($total_sale_total, 2); ?></th>
                    <th><?= number_format($total_discount_share, 2); ?></th>
                    <th><?= number_format($total_final_sale, 2); ?></th>
                    <th><?= number_format($total_cost, 2); ?></th>
                    <th><?= number_format($total_profit, 2); ?></th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>

<?php require_once "../layouts/footer.php"; ?>
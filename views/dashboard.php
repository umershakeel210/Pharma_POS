<?php
require_once "../config/auth.php";
require_once "../config/database.php";
require_once "layouts/header.php";

$total_medicines = $conn->query("SELECT COUNT(*) AS total FROM medicines")->fetch_assoc()['total'];

$low_stock = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE stock_quantity <= 10")->fetch_assoc()['total'];

$expired = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE expiry_date < CURDATE()")->fetch_assoc()['total'];

$total_sales = $conn->query("SELECT IFNULL(SUM(total_amount), 0) AS total FROM sales")->fetch_assoc()['total'];

$low_stock_list = $conn->query("SELECT * FROM medicines WHERE stock_quantity <= 10 ORDER BY stock_quantity ASC");

$expiry_list = $conn->query("SELECT * FROM medicines 
    WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    ORDER BY expiry_date ASC");

/* ===================== SALES CHART ===================== */
$sales_chart_query = $conn->query("
    SELECT sale_date, SUM(total_amount) AS daily_total
    FROM sales
    GROUP BY sale_date
    ORDER BY sale_date ASC
");

$chart_dates = [];
$chart_sales = [];

while ($row = $sales_chart_query->fetch_assoc()) {
    $chart_dates[] = $row['sale_date'];
    $chart_sales[] = $row['daily_total'];
}
?>

<h3 class="mb-4">Dashboard</h3>

<!-- ===================== CARDS ===================== -->
<div class="row">

    <div class="col-md-3 mb-3">
        <div class="card shadow text-white bg-primary">
            <div class="card-body">
                <h6>Total Medicines</h6>
                <h3><?= $total_medicines; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow text-white bg-warning">
            <div class="card-body">
                <h6>Low Stock</h6>
                <h3><?= $low_stock; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow text-white bg-danger">
            <div class="card-body">
                <h6>Expired</h6>
                <h3><?= $expired; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow text-white bg-success">
            <div class="card-body">
                <h6>Total Sales</h6>
                <h3><?= number_format($total_sales, 2); ?></h3>
            </div>
        </div>
    </div>

</div>

<!-- ===================== SALES CHART ===================== -->
<div class="card shadow mt-4 mb-4">
    <div class="card-header bg-primary text-white">
        <h5>Daily Sales Chart</h5>
    </div>

    <div class="card-body">
        <canvas id="salesChart" height="100"></canvas>
    </div>
</div>

<!-- ===================== TABLES ===================== -->
<div class="row">

    <!-- Low Stock -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h5>Low Stock Medicines</h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Stock</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $low_stock_list->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['medicine_name']; ?></td>
                                <td><?= $row['batch_no']; ?></td>
                                <td><?= $row['stock_quantity']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Expiry -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h5>Near Expiry Medicines</h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $expiry_list->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['medicine_name']; ?></td>
                                <td><?= $row['batch_no']; ?></td>
                                <td><?= $row['expiry_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ===================== CHART JS ===================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_dates); ?>,
        datasets: [{
            label: 'Daily Sales',
            data: <?= json_encode($chart_sales); ?>,
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php require_once "layouts/footer.php"; ?>
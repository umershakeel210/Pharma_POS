<?php
require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";
$result = $conn->query("SELECT * FROM sales ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-3">
        <h3>Sales Report</h3>
        <a href="pos.php" class="btn btn-success">New Sale</a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice No</th>
                        <th>Sale Date</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Change</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['sale_date']; ?></td>
                                <td><?= $row['subtotal']; ?></td>
                                <td><?= $row['discount']; ?></td>
                                <td><?= $row['total_amount']; ?></td>
                                <td><?= $row['paid_amount']; ?></td>
                                <td><?= $row['change_amount']; ?></td>
                                <td>
                                    <a href="invoice.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">
                                        View Invoice
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No sales found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
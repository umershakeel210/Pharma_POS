<?php
require_once "../../config/auth.php";
require_once "../../config/database.php";

$sale_id = $_GET['id'];

$sale = $conn->query("SELECT * FROM sales WHERE id = $sale_id")->fetch_assoc();

$items = $conn->query("
    SELECT si.*, m.medicine_name 
    FROM sale_items si
    JOIN medicines m ON si.medicine_id = m.id
    WHERE si.sale_id = $sale_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>

    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: auto;
        }

        h3, p {
            text-align: center;
            margin: 5px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 3px;
        }

        .total {
            border-top: 1px dashed black;
            margin-top: 5px;
            padding-top: 5px;
        }

        .center {
            text-align: center;
        }

        .btn-print {
            margin-top: 10px;
            width: 100%;
            padding: 8px;
            background: black;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<h3>Pharma POS</h3>
<p>Medical Store</p>
<p>--------------------------------</p>

<p>Invoice #: <?= $sale['id']; ?></p>
<p>Date: <?= $sale['sale_date']; ?></p>

<p>--------------------------------</p>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?= substr($item['medicine_name'], 0, 10); ?></td>
                <td><?= $item['quantity']; ?></td>
                <td><?= number_format($item['total'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<p>--------------------------------</p>

<div class="total">
    <p>Subtotal: <?= number_format($sale['subtotal'], 2); ?></p>
    <p>Discount: <?= number_format($sale['discount'], 2); ?></p>
    <p><strong>Total: <?= number_format($sale['total_amount'], 2); ?></strong></p>
    <p>Paid: <?= number_format($sale['paid_amount'], 2); ?></p>
    <p>Change: <?= number_format($sale['change_amount'], 2); ?></p>
</div>

<p>--------------------------------</p>

<p class="center">Thank You!</p>

<button class="btn-print" onclick="window.print()">Print</button>

<script>
window.onload = function() {
    window.print();
};
</script>

</body>
</html>
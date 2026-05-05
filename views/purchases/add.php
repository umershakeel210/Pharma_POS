<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name ASC");
$medicines = $conn->query("SELECT * FROM medicines ORDER BY medicine_name ASC");

if (isset($_POST['save_purchase'])) {
    $supplier_id = $_POST['supplier_id'];
    $purchase_date = $_POST['purchase_date'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $purchase_price = $_POST['purchase_price'];
    $sale_price = $_POST['sale_price'];

    $total = $quantity * $purchase_price;

    $purchase_sql = "INSERT INTO purchases (supplier_id, purchase_date, total_amount)
                     VALUES ('$supplier_id', '$purchase_date', '$total')";

    if ($conn->query($purchase_sql)) {
        $purchase_id = $conn->insert_id;

        $item_sql = "INSERT INTO purchase_items 
        (purchase_id, medicine_id, quantity, purchase_price, sale_price, total)
        VALUES 
        ('$purchase_id', '$medicine_id', '$quantity', '$purchase_price', '$sale_price', '$total')";

        if ($conn->query($item_sql)) {
            $update_stock_sql = "UPDATE medicines 
                                 SET stock_quantity = stock_quantity + $quantity,
                                     purchase_price = '$purchase_price',
                                     sale_price = '$sale_price'
                                 WHERE id = $medicine_id";

            if ($conn->query($update_stock_sql)) {
                echo "<script>alert('Purchase saved and stock updated successfully'); window.location.href='list.php';</script>";
            } else {
                echo "Stock Update Error: " . $conn->error;
            }
        } else {
            echo "Purchase Item Error: " . $conn->error;
        }
    } else {
        echo "Purchase Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Add Purchase / Stock Entry</h4>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">Select Supplier</option>
                            <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                                <option value="<?= $supplier['id']; ?>">
                                    <?= $supplier['supplier_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Medicine</label>
                        <select name="medicine_id" class="form-control" required>
                            <option value="">Select Medicine</option>
                            <?php while ($medicine = $medicines->fetch_assoc()): ?>
                                <option value="<?= $medicine['id']; ?>">
                                    <?= $medicine['medicine_name']; ?> - Batch: <?= $medicine['batch_no']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Purchase Price</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" required>
                    </div>

                </div>

                <button type="submit" name="save_purchase" class="btn btn-success">
                    Save Purchase
                </button>

                <a href="list.php" class="btn btn-secondary">Back</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
<?php
require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// REMOVE ITEM
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// CLEAR CART
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
}

// COMPLETE SALE
if (isset($_POST['complete_sale'])) {

    $discount = (float) $_POST['discount'];
    $paid_amount = (float) $_POST['paid_amount'];
    $sale_date = date("Y-m-d");

    $subtotal = 0;

    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['total'];
    }

    $total_amount = $subtotal - $discount;
    $change_amount = $paid_amount - $total_amount;

    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Cart is empty');</script>";
    } elseif ($paid_amount < $total_amount) {
        echo "<script>alert('Paid amount is less');</script>";
    } else {

        // INSERT SALE
        $conn->query("INSERT INTO sales 
        (sale_date, subtotal, discount, total_amount, paid_amount, change_amount)
        VALUES 
        ('$sale_date', '$subtotal', '$discount', '$total_amount', '$paid_amount', '$change_amount')");

        $sale_id = $conn->insert_id;

        // INSERT ITEMS + UPDATE STOCK
        foreach ($_SESSION['cart'] as $item) {

            $medicine_id = $item['medicine_id'];
            $quantity = $item['quantity'];
            $sale_price = $item['sale_price'];
            $purchase_price = $item['purchase_price']; // ✅ IMPORTANT
            $total = $item['total'];

           $conn->query("INSERT INTO sale_items 
(sale_id, medicine_id, quantity, sale_price, purchase_price, total)
VALUES 
('$sale_id','$medicine_id','$quantity','$sale_price','$purchase_price','$total')");

            $conn->query("UPDATE medicines 
            SET stock_quantity = stock_quantity - $quantity 
            WHERE id = $medicine_id");
        }

        $_SESSION['cart'] = [];

        echo "<script>
            alert('Sale Completed');
            window.location.href='invoice.php?id=$sale_id';
        </script>";
    }
}
?>

<h3 class="mb-4">Cart</h3>

<div class="card shadow">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Qty</th>
                    <th>Sale Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $subtotal = 0;

                if (!empty($_SESSION['cart'])):
                    foreach ($_SESSION['cart'] as $index => $item):
                        $subtotal += $item['total'];
                ?>
                    <tr>
                        <td><?= $item['medicine_name']; ?></td>
                        <td><?= $item['batch_no']; ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= number_format($item['sale_price'], 2); ?></td>
                        <td><?= number_format($item['total'], 2); ?></td>
                        <td>
                            <a href="cart.php?remove=<?= $index; ?>" 
                               class="btn btn-danger btn-sm">
                                Remove
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Cart is empty</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- TOTAL SECTION -->
        <form method="POST">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Subtotal</label>
                    <input type="number" id="subtotal" 
                           class="form-control" 
                           value="<?= $subtotal; ?>" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Discount</label>
                    <input type="number" name="discount" id="discount" 
                           class="form-control" value="0" 
                           onkeyup="calc()">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Total</label>
                    <input type="number" id="total" 
                           class="form-control" 
                           value="<?= $subtotal; ?>" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Paid</label>
                    <input type="number" name="paid_amount" id="paid" 
                           class="form-control" 
                           onkeyup="calc()" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Change</label>
                    <input type="number" id="change" 
                           class="form-control" readonly>
                </div>

            </div>

            <button type="submit" name="complete_sale" 
                    class="btn btn-success">
                Complete Sale
            </button>

            <a href="cart.php?clear=1" class="btn btn-danger">
                Clear Cart
            </a>

        </form>

    </div>
</div>

<script>
function calc(){
    let sub = parseFloat(document.getElementById("subtotal").value) || 0;
    let dis = parseFloat(document.getElementById("discount").value) || 0;
    let paid = parseFloat(document.getElementById("paid").value) || 0;

    let total = sub - dis;
    let change = paid - total;

    document.getElementById("total").value = total.toFixed(2);
    document.getElementById("change").value = change.toFixed(2);
}
</script>

<?php require_once "../layouts/footer.php"; ?>
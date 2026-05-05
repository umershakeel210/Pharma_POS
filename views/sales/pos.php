<?php
require_once "../../config/auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$medicines = $conn->query("SELECT * FROM medicines WHERE stock_quantity > 0 AND expiry_date >= CURDATE() ORDER BY medicine_name ASC");

if (isset($_POST['add_to_cart'])) {
    $medicine_id = $_POST['medicine_id'];
    $quantity = (int) $_POST['quantity'];

    $result = $conn->query("SELECT * FROM medicines WHERE id = $medicine_id");
    $medicine = $result->fetch_assoc();

    if ($medicine && $quantity > 0 && $quantity <= $medicine['stock_quantity']) {
        $_SESSION['cart'][] = [
    'medicine_id' => $medicine['id'],
    'medicine_name' => $medicine['medicine_name'],
    'batch_no' => $medicine['batch_no'],
    'quantity' => $quantity,
    'purchase_price' => $medicine['purchase_price'],
    'sale_price' => $medicine['sale_price'],
    'total' => $quantity * $medicine['sale_price']
];
    } else {
        echo "<script>alert('Invalid quantity or stock not available');</script>";
    }
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    echo "<script>window.location.href='pos.php';</script>";
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    echo "<script>window.location.href='pos.php';</script>";
}

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
        echo "<script>alert('Paid amount is less than total');</script>";
    } else {
        $sale_sql = "INSERT INTO sales 
        (sale_date, subtotal, discount, total_amount, paid_amount, change_amount)
        VALUES 
        ('$sale_date', '$subtotal', '$discount', '$total_amount', '$paid_amount', '$change_amount')";

        if ($conn->query($sale_sql)) {
            $sale_id = $conn->insert_id;

            foreach ($_SESSION['cart'] as $item) {
                $medicine_id = $item['medicine_id'];
                $quantity = $item['quantity'];
                $sale_price = $item['sale_price'];
                $total = $item['total'];

                $conn->query("INSERT INTO sale_items 
                (sale_id, medicine_id, quantity, sale_price, total)
                VALUES
                ('$sale_id', '$medicine_id', '$quantity', '$sale_price', '$total')");

                $conn->query("UPDATE medicines 
                SET stock_quantity = stock_quantity - $quantity 
                WHERE id = $medicine_id");
            }

            $_SESSION['cart'] = [];

            echo "<script>
                alert('Sale completed successfully');
                window.location.href='invoice.php?id=$sale_id';
            </script>";
        }
    }
}
?>

<h3 class="mb-4">POS Sale</h3>

<div class="row">

    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5>Add Medicine to Cart</h5>
            </div>

            <div class="card-body">
                <form method="POST">

                    <div class="mb-3 position-relative">
    <label>Search Medicine</label>

    <input type="text"
           id="medicineSearch"
           class="form-control"
           placeholder="Type medicine name, batch, or barcode"
           autocomplete="off">

    <input type="hidden" name="medicine_id" id="medicine_id">

    <div id="medicineOptions"
         class="list-group position-absolute w-100"
         style="z-index:1000; display:none; max-height:250px; overflow-y:auto;">
         
        <?php while ($medicine = $medicines->fetch_assoc()): ?>
            <button type="button"
                    class="list-group-item list-group-item-action medicine-option"
                    data-id="<?= $medicine['id']; ?>"
                    data-name="<?= $medicine['medicine_name']; ?>"
                    data-search="<?= strtolower($medicine['medicine_name'] . ' ' . $medicine['batch_no'] . ' ' . $medicine['barcode']); ?>">
                <?= $medicine['medicine_name']; ?>
                | Batch: <?= $medicine['batch_no']; ?>
                | Stock: <?= $medicine['stock_quantity']; ?>
                | Price: <?= $medicine['sale_price']; ?>
            </button>
        <?php endwhile; ?>

    </div>
</div>

                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>

                    <button type="submit" name="add_to_cart" class="btn btn-success">
                        Add to Cart
                    </button>

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5>Cart Items</h5>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Price</th>
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
                                <td><?= $item['sale_price']; ?></td>
                                <td><?= $item['total']; ?></td>
                                <td>
                                    <a href="pos.php?remove=<?= $index; ?>" class="btn btn-sm btn-danger">
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

                <form method="POST">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Subtotal</label>
                            <input type="number" id="subtotal" class="form-control" value="<?= $subtotal; ?>" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Discount</label>
                            <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="0" onkeyup="calculateTotal()">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Total Amount</label>
                            <input type="number" id="total_amount" class="form-control" value="<?= $subtotal; ?>" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Paid Amount</label>
                            <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" onkeyup="calculateTotal()" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Change</label>
                            <input type="number" id="change_amount" class="form-control" readonly>
                        </div>
                    </div>

                    <button type="submit" name="complete_sale" class="btn btn-primary">
                        Complete Sale
                    </button>

                    <a href="pos.php?clear=1" class="btn btn-danger">
                        Clear Cart
                    </a>

                </form>

            </div>
        </div>
    </div>

</div>

<script>
function calculateTotal() {
    let subtotal = parseFloat(document.getElementById("subtotal").value) || 0;
    let discount = parseFloat(document.getElementById("discount").value) || 0;
    let paid = parseFloat(document.getElementById("paid_amount").value) || 0;

    let total = subtotal - discount;
    let change = paid - total;

    document.getElementById("total_amount").value = total.toFixed(2);
    document.getElementById("change_amount").value = change.toFixed(2);
}
</script>
<script>
const searchInput = document.getElementById("medicineSearch");
const medicineIdInput = document.getElementById("medicine_id");
const optionsBox = document.getElementById("medicineOptions");
const options = document.querySelectorAll(".medicine-option");

searchInput.addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let found = false;

    medicineIdInput.value = "";

    options.forEach(option => {
        let searchText = option.getAttribute("data-search");

        if (value !== "" && searchText.includes(value)) {
            option.style.display = "block";
            found = true;
        } else {
            option.style.display = "none";
        }
    });

    optionsBox.style.display = found ? "block" : "none";
});

options.forEach(option => {
    option.addEventListener("click", function () {
        searchInput.value = this.getAttribute("data-name");
        medicineIdInput.value = this.getAttribute("data-id");
        optionsBox.style.display = "none";
    });
});
</script>
<?php require_once "../layouts/footer.php"; ?>
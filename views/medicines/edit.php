<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM medicines WHERE id = $id");

if ($result->num_rows == 0) {
    echo "Medicine not found";
    exit;
}

$medicine = $result->fetch_assoc();

if (isset($_POST['update_medicine'])) {
    $medicine_name = $_POST['medicine_name'];
    $generic_name = $_POST['generic_name'];
    $company_name = $_POST['company_name'];
    $category = $_POST['category'];
    $batch_no = $_POST['batch_no'];
    $expiry_date = $_POST['expiry_date'];
    $purchase_price = $_POST['purchase_price'];
    $sale_price = $_POST['sale_price'];
    $stock_quantity = $_POST['stock_quantity'];
    $barcode = $_POST['barcode'];
    $prescription_required = isset($_POST['prescription_required']) ? 1 : 0;

    $sql = "UPDATE medicines SET
        medicine_name = '$medicine_name',
        generic_name = '$generic_name',
        company_name = '$company_name',
        category = '$category',
        batch_no = '$batch_no',
        expiry_date = '$expiry_date',
        purchase_price = '$purchase_price',
        sale_price = '$sale_price',
        stock_quantity = '$stock_quantity',
        barcode = '$barcode',
        prescription_required = '$prescription_required'
        WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: list.php");
        exit;
    } else {
        echo "Error updating medicine: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4>Edit Medicine</h4>
        </div>

        <div class="card-body">
            <form method="POST">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Medicine Name</label>
                        <input type="text" name="medicine_name" class="form-control" value="<?= $medicine['medicine_name']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Generic Name</label>
                        <input type="text" name="generic_name" class="form-control" value="<?= $medicine['generic_name']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="<?= $medicine['company_name']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control" value="<?= $medicine['category']; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Batch No</label>
                        <input type="text" name="batch_no" class="form-control" value="<?= $medicine['batch_no']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" value="<?= $medicine['expiry_date']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Purchase Price</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control" value="<?= $medicine['purchase_price']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" value="<?= $medicine['sale_price']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?= $medicine['stock_quantity']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control" value="<?= $medicine['barcode']; ?>">
                    </div>

                    <div class="col-md-12 mb-3">
                        <input type="checkbox" name="prescription_required" <?= $medicine['prescription_required'] == 1 ? 'checked' : ''; ?>>
                        <label>Prescription Required</label>
                    </div>
                </div>

                <button type="submit" name="update_medicine" class="btn btn-success">
                    Update Medicine
                </button>

                <a href="list.php" class="btn btn-secondary">
                    Back
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
<?php require_once "../layouts/footer.php"; ?>
<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

if (isset($_POST['save_medicine'])) {
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

    $sql = "INSERT INTO medicines 
    (medicine_name, generic_name, company_name, category, batch_no, expiry_date, purchase_price, sale_price, stock_quantity, barcode, prescription_required)
    VALUES 
    ('$medicine_name', '$generic_name', '$company_name', '$category', '$batch_no', '$expiry_date', '$purchase_price', '$sale_price', '$stock_quantity', '$barcode', '$prescription_required')";

    if ($conn->query($sql)) {
        echo "<script>alert('Medicine added successfully');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Add Medicine</h4>
        </div>

        <div class="card-body">
            <form method="POST">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Medicine Name</label>
                        <input type="text" name="medicine_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Generic Name</label>
                        <input type="text" name="generic_name" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Batch No</label>
                        <input type="text" name="batch_no" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Purchase Price</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <input type="checkbox" name="prescription_required">
                        <label>Prescription Required</label>
                    </div>
                </div>

                <button type="submit" name="save_medicine" class="btn btn-success">
                    Save Medicine
                </button>

            </form>
        </div>
    </div>
</div>

</body>
</html>
<?php require_once "../layouts/footer.php"; ?>
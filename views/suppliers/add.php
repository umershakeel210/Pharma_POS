<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";

if (isset($_POST['save_supplier'])) {
    $supplier_name = $_POST['supplier_name'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "INSERT INTO suppliers 
    (supplier_name, contact_person, phone, email, address)
    VALUES 
    ('$supplier_name', '$contact_person', '$phone', '$email', '$address')";

    if ($conn->query($sql)) {
        echo "<script>alert('Supplier added successfully'); window.location.href='list.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Add Supplier</h4>
        </div>

        <div class="card-body">
            <form method="POST">

                <div class="mb-3">
                    <label>Supplier Name</label>
                    <input type="text" name="supplier_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>

                <button type="submit" name="save_supplier" class="btn btn-success">
                    Save Supplier
                </button>

                <a href="list.php" class="btn btn-secondary">Back</a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
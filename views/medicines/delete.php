<?php
require_once "../../config/admin_auth.php";
require_once "../../config/database.php";
require_once "../layouts/header.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM medicines WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: list.php");
        exit;
    } else {
        echo "Error deleting medicine: " . $conn->error;
    }
} else {
    header("Location: list.php");
    exit;
}
?>
<?php require_once "../layouts/footer.php"; ?>
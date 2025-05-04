<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sdid = $_POST['sdid'];
    $action = $_POST['action'];

    $status = ($action === 'verify') ? 'Verified' : 'Refuted';
    $sql = "UPDATE SeasonalDetails SET verification_status = '$status' WHERE SDID = $sdid";

    if ($con->query($sql)) {
        header("Location: verify_seasonal_details.php");
        exit;
    } else {
        echo "Error: " . $con->error;
    }
}
?>

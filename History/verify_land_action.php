<?php
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fid = $_POST['fid'];
    $lid = $_POST['lid'];
    $action = $_POST['action'];

    if ($action === 'verify') {
        $sql = "UPDATE Land SET is_verify = 1 WHERE FID = $fid AND LID = $lid";
    } elseif ($action === 'reject') {
        $sql = "UPDATE Land SET is_verify = 0 WHERE FID = $fid AND LID = $lid";
    }

    if ($con->query($sql)) {
        header("Location: verify_land.php");
        exit;
    } else {
        echo "Error: " . $con->error;
    }
}
?>

<?php
include 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $id = intval($_POST['officer_id']);
  $role = $_POST['role'];

  if ($role === 'fo') {
    $sql = "UPDATE FieldOfficer SET verification_status = 'Verified' WHERE FOID = $id";
  } elseif ($role === 'wo') {
    $sql = "UPDATE WarehouseOfficer SET verification_status = 'Verified' WHERE WOID = $id";
  } else {
    die("Invalid role.");
  }

  if ($con->query($sql)) {
    header("Location: DeputyOfficerDashboard.php?verified=1");
  } else {
    echo "Error: " . $con->error;
  }

  $con->close();
}
?>

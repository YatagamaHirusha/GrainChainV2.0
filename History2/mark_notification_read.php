<?php
include 'database.php';
if (isset($_POST['notif_id'])) {
  $id = $_POST['notif_id'];
  mysqli_query($con, "UPDATE Notify SET is_read = 1 WHERE id = '$id'");
}
header("Location: FieldOfficerDashboard.php");
exit;
?>

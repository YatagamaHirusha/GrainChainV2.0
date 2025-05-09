<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
include 'database.php';

if (isset($_POST['submit'])) {
    $farmer_id = $_SESSION['UID'];
    $land_reg_no = $_POST['land_reg_no'];
    $land_id = (int) $_POST['land_id'];
    $season_id = (int) $_POST['season_id'];
    $cultivated_area = (float) $_POST['cultivated_area'];

    $sql = "INSERT INTO FertilizeSubsidy (FID, LID, SDID, amount_of_cultivated_land, status)
            VALUES ('$farmer_id', '$land_id', '$season_id', '$cultivated_area', 'Pending')";

    $stmt = $con->prepare("SELECT FOID FROM Land WHERE LID = ?");
    $stmt->bind_param("i", $land_id);
    $stmt->execute();
    $stmt->bind_result($foid);
    $stmt->fetch();
    $stmt->close();

    if (mysqli_query($con, $sql)) {
        $notification_message = "New fertilizer subsidy request from Farmer ID: $farmer_id for Land: $land_reg_no";

        mysqli_query($con, "INSERT INTO Notify (FID, FOID, message, is_read) 
          VALUES ('$farmer_id', '$foid', '$notification_message', false)");

        echo "<script>alert('Apcation submitted successfully.'); window.location.href='FarmerDashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

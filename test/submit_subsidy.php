<?php
session_start();
include 'database.php';

if (isset($_POST['submit'])) {
    $farmer_id = $_SESSION['farmer_id'] ?? 1;
    $land_id = $_POST['land_id'];
    $cultivated_area = $_POST['cultivated_area'];

    echo "Testing done: $land_id";

    // Add to FertilizerSubsidy table
    $sql = "INSERT INTO fertilizersubsidy (FID, LID,  amount_of_cultivated_land, status)
            VALUES ('$farmer_id', '$land_id', '$cultivated_area', 'Pending')";

    if (mysqli_query($con, $sql)) {
        echo "Subsidy application submitted successfully.";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

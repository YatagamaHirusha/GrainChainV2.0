<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $LID = $_POST['LID'];
    $FOID = null; // We'll leave FOID as NULL for now. It will be updated after verification by the Field Officer.
    $season_name = $_POST['season_name'];
    $year = $_POST['year'];
    $paddy_type = $_POST['paddy_type'];
    $plant_date = $_POST['plant_date'];
    $expected_harvest_in_KG = $_POST['expected_harvest_in_KG'];

    // Set default values
    $harvest_date = null;
    $harvest_paddy_quantity = null;
    $paddy_status = 'Ongoing';
    $verification_status = null;
    $note = null;

    $sql = "INSERT INTO SeasonalDetails (
                LID, FOID, season_name, year, paddy_type, plant_date, expected_harvest_in_KG,
                harvest_date, harvest_paddy_quantity, paddy_status, verification_status, note
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param(
        "iisisssdssss",
        $LID,
        $FOID,
        $season_name,
        $year,
        $paddy_type,
        $plant_date,
        $expected_harvest_in_KG,
        $harvest_date,
        $harvest_paddy_quantity,
        $paddy_status,
        $verification_status,
        $note
    );

    if ($stmt->execute()) {
        echo "<script>alert('Seasonal details added successfully!'); window.location.href='view_seasonal_details.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Invalid request.";
}

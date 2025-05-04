<?php
session_start();
require 'database.php';

// Collect form data
$full_name = $_POST['full_name'];
$nic = $_POST['nic'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$address = $_POST['address'];
$contact_number1 = $_POST['contact_number1'];
$contact_number2 = $_POST['contact_number2'];
$username = $_POST['username'];
$password = $_POST['password']; // secure password hash
$register_date = date('Y-m-d');
$role = $_POST['role'];

// Insert into User table
$sql_user = "INSERT INTO User (full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date, role)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql_user);
$stmt->bind_param("ssssisssssss", $full_name, $nic, $email, $gender, $age, $address, $contact_number1, $contact_number2, $username, $password, $register_date, $role);

if ($stmt->execute()) {
    $last_uid = $conn->insert_id;

    if ($role == "Farmer") {
        // Insert into Farmer table
        $years_of_experience = $_POST['years_of_experience'];
        $sql_farmer = "INSERT INTO Farmer (FID, years_of_experience) VALUES (?, ?)";
        $stmt_farmer = $conn->prepare($sql_farmer);
        $stmt_farmer->bind_param("ii", $last_uid, $years_of_experience);
        $stmt_farmer->execute();

        // Insert into Land table
        $land_reg_no = $_POST['land_reg_no'];
        $mahawali_region = $_POST['mahawali_region'];
        $district = $_POST['land_district'];
        $agricultural_service_area = $_POST['agricultural_service_area'];
        $regional_secretariat_division = $_POST['regional_secretariat_division'];
        $location = $_POST['location'];
        $land_type = $_POST['land_type'];
        $irrigation_type = $_POST['irrigation_type'];
        $land_size_in_acres = $_POST['land_size_in_acres'];

        $sql_land = "INSERT INTO Land (FID, land_reg_no, mahawali_region, district, aggricultural_service_area, regional_secretariat_division, location, land_type, irrigation_type, land_size_in_acres, is_verify)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

        $stmt_land = $conn->prepare($sql_land);
        $stmt_land->bind_param("issssssssd", $last_uid, $land_reg_no, $mahawali_region, $district, $agricultural_service_area, $regional_secretariat_division, $location, $land_type, $irrigation_type, $land_size_in_acres);
        $stmt_land->execute();

    } elseif ($role == "Field Officer") {
        $assign_district = $_POST['assign_district'];
        $assign_division = $_POST['assign_division'];
        $sub_division = $_POST['sub_division'];
        $doid = $_POST['doid']; // You should collect deputy officer ID also if needed

        $sql_fieldofficer = "INSERT INTO FieldOfficer (FOID, assign_division, assign_district, sub_division, DOID) VALUES (?, ?, ?, ?, ?)";
        $stmt_fo = $conn->prepare($sql_fieldofficer);
        $stmt_fo->bind_param("isssi", $last_uid, $assign_division, $assign_district, $sub_division, $doid);
        $stmt_fo->execute();

    } elseif ($role == "Warehouse Officer") {
        $assign_district = $_POST['assign_district'];
        $assign_division = $_POST['assign_division'];
        $doid = $_POST['doid'];

        $sql_warehouseofficer = "INSERT INTO WarehouseOfficer (WOID, assign_district, assign_division, DOID) VALUES (?, ?, ?, ?)";
        $stmt_wo = $conn->prepare($sql_warehouseofficer);
        $stmt_wo->bind_param("issi", $last_uid, $assign_district, $assign_division, $doid);
        $stmt_wo->execute();

    } elseif ($role == "Deputy Officer") {
        $assign_district = $_POST['assign_district'];

        $sql_deputyofficer = "INSERT INTO DeputyOfficer (DOID, assign_district) VALUES (?, ?)";
        $stmt_do = $conn->prepare($sql_deputyofficer);
        $stmt_do->bind_param("is", $last_uid, $assign_district);
        $stmt_do->execute();
    }

    echo "Registration Successful!";
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>

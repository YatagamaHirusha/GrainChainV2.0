<?php
if (isset($_POST['btnSubmit'])) {
    include('dbconnect.php'); // database connection

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $nic = $_POST['nic'];
    $full_name = $_POST['full_name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['mobile'];
    $home_number = $_POST['home'] ?? NULL;
    $address = $_POST['address'];

    $id_proof = NULL;
    if (!empty($_FILES['id_proof']['tmp_name'])) {
        $id_proof = addslashes(file_get_contents($_FILES['id_proof']['tmp_name']));
    }

    // Check if username already exists
    $check_query = "SELECT * FROM Users WHERE username = '$username'";
    $check_result = mysqli_query($con, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Username already exists!'); window.location.href='Register.html';</script>";
        exit();
    }

    // Insert into Users table
    $user_insert = "INSERT INTO Users (username, password, role) VALUES ('$username', '$password', '$role')";
    $user_result = mysqli_query($con, $user_insert);

    if (!$user_result) {
        echo "User insert failed: " . mysqli_error($con);
        exit();
    }

    $user_id = mysqli_insert_id($con); // get auto-generated UID

    // Handle based on role
    $success = false;

    if ($role == 'Farmer') {
        // Insert into Farmer table
        $farmer_insert = "INSERT INTO Farmer (UID, full_name, nic, birthdate, gender, contact_number, home_number, address, id_proof)
                          VALUES ('$user_id', '$full_name', '$nic', '$birthdate', '$gender', '$contact_number', '$home_number', '$address', '$id_proof')";
        $farmer_result = mysqli_query($con, $farmer_insert);

        if ($farmer_result) {
            $farmer_id = mysqli_insert_id($con); // get FID

            // Insert into Land table
            $land_reg_no = $_POST['land_reg_no'];
            $mahawali_region = $_POST['mahawali_region'];
            $district = $_POST['district'];
            $agricultural_service_area = $_POST['agricultural_service_area'];
            $regional_secretariat_division = $_POST['regional_secretariat_division'];
            $location = $_POST['location'];
            $land_type = $_POST['land_type'];
            $irrigation_type = $_POST['irrigation_type'];
            $land_size_in_acres = $_POST['land_size_in_acres'];

            $land_insert = "INSERT INTO Land (FID, land_reg_no, mahawali_region, district, aggricultural_service_area, regional_secretariat_division, location, land_type, irrigation_type, land_size_in_acres, is_verify)
                            VALUES ('$farmer_id', '$land_reg_no', '$mahawali_region', '$district', '$agricultural_service_area', '$regional_secretariat_division', '$location', '$land_type', '$irrigation_type', '$land_size_in_acres', 0)";
            $land_result = mysqli_query($con, $land_insert);

            $success = $land_result;
        }
    } elseif ($role == 'Field Officer') {
        $assigned_district = $_POST['assigned_district'];
        $assigned_division = $_POST['assigned_division'];
        $assigned_subdivision = $_POST['assigned_subdivision'];

        $field_officer_insert = "INSERT INTO FieldOfficer (UID, full_name, nic, birthdate, gender, contact_number, home_number, address, id_proof, assigned_district, assigned_division, assigned_subdivision)
                                 VALUES ('$user_id', '$full_name', '$nic', '$birthdate', '$gender', '$contact_number', '$home_number', '$address', '$id_proof', '$assigned_district', '$assigned_division', '$assigned_subdivision')";
        $success = mysqli_query($con, $field_officer_insert);
    } elseif ($role == 'Warehouse Officer') {
        $assigned_district = $_POST['assigned_district'];
        $assigned_division = $_POST['assigned_division'];
        $assigned_subdivision = $_POST['assigned_subdivision'];

        $warehouse_officer_insert = "INSERT INTO WarehouseOfficer (UID, full_name, nic, birthdate, gender, contact_number, home_number, address, id_proof, assigned_district, assigned_division, assigned_subdivision)
                                     VALUES ('$user_id', '$full_name', '$nic', '$birthdate', '$gender', '$contact_number', '$home_number', '$address', '$id_proof', '$assigned_district', '$assigned_division', '$assigned_subdivision')";
        $success = mysqli_query($con, $warehouse_officer_insert);
    } elseif ($role == 'Deputy Officer') {
        $assigned_district = $_POST['assigned_district'];
        $assigned_division = $_POST['assigned_division'];
        $assigned_subdivision = $_POST['assigned_subdivision'];

        $deputy_officer_insert = "INSERT INTO DeputyOfficer (UID, full_name, nic, birthdate, gender, contact_number, home_number, address, id_proof, assigned_district, assigned_division, assigned_subdivision)
                                  VALUES ('$user_id', '$full_name', '$nic', '$birthdate', '$gender', '$contact_number', '$home_number', '$address', '$id_proof', '$assigned_district', '$assigned_division', '$assigned_subdivision')";
        $success = mysqli_query($con, $deputy_officer_insert);
    }

    if ($success) {
        echo "<script>alert('Registration Successful!'); window.location.href='Login.html';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>

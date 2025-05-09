<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database.php');

if (isset($_POST['btnSubmit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $nic = $_POST['nic'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age']; // If you collect DOB separately
    $gender = $_POST['gender'];
    $contact_number1 = $_POST['contact_number1'];
    $contact_number2 = $_POST['contact_number2'] ?? NULL;
    $address = $_POST['address'];

    $id_proof = NULL;
    if (!empty($_FILES['id_proof']['tmp_name'])) {
        $id_proof = addslashes(file_get_contents($_FILES['id_proof']['tmp_name']));
    }

    // Check if username already exists
    $sql_check = "SELECT * FROM user WHERE username = '$username'";
    $res_check = mysqli_query($con, $sql_check);
    if (mysqli_num_rows($res_check) > 0) {
        echo "<script>alert('Username already exists!'); window.location.href='Register.html';</script>";
        exit;
    }

    //check if user already exists
    $sql_check_user = "SELECT * FROM User WHERE nic = '$nic'";
    $res_check_user = mysqli_query($con, $sql_check_user);
    if(mysqli_num_rows($res_check_user) > 0){
        echo "<script>alert('User already exists!'); window.location.href='Register.html';</script>";
        exit;
    }

    // Insert into user table
    $password = $_POST['password'];
    $sql_user = "INSERT INTO user (full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date, role, nic_photo) VALUES ('$full_name', '$nic', '$email', '$gender', '$age', '$address', '$contact_number1', '$contact_number2', '$username', '$password', CURDATE(), '$role', '$id_proof')";

    $success = false;

    if ($role === 'Farmer') {
        $years_of_experience = $_POST['years_of_experience'];
        $land_reg_no = $_POST['land_reg_no'];
        $district = $_POST['land_district'];
        $agricultural_service_area = $_POST['agricultural_service_area'];
        $location = $_POST['location'];
        $land_type = $_POST['land_type'];
        $irrigation_type = $_POST['irrigation_type'];
        $land_size_in_acres = $_POST['land_size_in_acres'];
        $division = $_POST['land_division'];

        $success = mysqli_query($con, $sql_user);

        if ($success) {
            $uid = mysqli_insert_id($con);
            $sql_farmer = "INSERT INTO farmer (FID, years_of_experience) VALUES ('$uid', '$years_of_experience')";
            $success = mysqli_query($con, $sql_farmer);

            if ($success){
                $sql_land = "INSERT INTO land (FID, land_reg_no, district, agricultural_service_area, location, land_type, irrigation_type, land_size_in_acres, is_verify, division) VALUES ('$uid' , '$land_reg_no' , '$district' , '$agricultural_service_area' , '$location' , '$land_type' , '$irrigation_type' , '$land_size_in_acres' , false , '$division')";
                $success = mysqli_query($con, $sql_land);
            }
        }
    }

    elseif ($role === 'Field Officer') {
        $assign_division = $_POST['assign_division'];
        $assign_district = $_POST['assign_district'];
        $sub_division = $_POST['sub_division'] ;

        $success = mysqli_query($con, $sql_user);

        if ($success) {
            $uid = mysqli_insert_id($con);

            // First, fetch DOID from DeputyOfficer table based on assign_district
            $query_get_doid = "SELECT DOID FROM DeputyOfficer WHERE assign_district = '$assign_district' LIMIT 1";
            $result_get_doid = mysqli_query($con, $query_get_doid);

            if ($result_get_doid && mysqli_num_rows($result_get_doid) > 0) {
                $row = mysqli_fetch_assoc($result_get_doid);
                $DOID = $row['DOID'];

                // Now insert into FieldOfficer
                $sql_fieldofficer = "INSERT INTO FieldOfficer (FOID, assign_division, assign_district, sub_divison, DOID)
                                     VALUES ('$uid', '$assign_division', '$assign_district', '$sub_division', '$DOID')";
                $success = mysqli_query($con, $sql_fieldofficer);
            }
        }
    }

    elseif ($role === 'Warehouse Officer') {
        $assign_district = $_POST['assign_district'];
        $assign_division = $_POST['assign_division'];
        $warehouse_name = $_POST['warehouse_name'];
        $success = mysqli_query($con, $sql_user);

        if ($success) {
            $uid = mysqli_insert_id($con);

            $query_get_doid = "SELECT DOID FROM DeputyOfficer WHERE assign_district = '$assign_district' LIMIT 1";
            $query_get_WID = "SELECT WID FROM Warehouse WHERE warehouse_name = '$warehouse_name'";
            $result_get_doid = mysqli_query($con, $query_get_doid);
            $result_get_WID = mysqli_query($con, $query_get_WID);

            if($result_get_doid && mysqli_num_rows($result_get_doid) > 0 && $result_get_WID && mysqli_num_rows($result_get_WID) > 0){
                $row1 = mysqli_fetch_assoc($result_get_doid);
                $row2 = mysqli_fetch_assoc($result_get_WID);
                $DOID = $row1['DOID'];
                $WID = $row2['WID'];

                $sql_warehouseofficer = "INSERT INTO warehouseofficer (WOID, assign_district, assign_division, DOID, WID)
                                         VALUES ('$uid', '$assign_district', '$assign_division', '$DOID', '$WID')";
                

                echo "<pre>$sql_warehouseofficer</pre>";

                $success = mysqli_query($con, $sql_warehouseofficer);

            }
            else{
                echo "Missing DOID or WID. Please check the warehouse name and district.";
            }
        }
        else {
            echo "Insert into warehouseofficer failed: " . mysqli_error($con);
        }

    }

    elseif ($role === 'Deputy Officer') {
        $assign_district = $_POST['assign_district'];

        $success = mysqli_query($con, $sql_user);

        if ($success) {
            $uid = mysqli_insert_id($con);
            $sql_deputyofficer = "INSERT INTO deputyofficer (DOID, assign_district) VALUES ('$uid', '$assign_district')";
            $success = mysqli_query($con, $sql_deputyofficer);
        }
    }

    if ($success) {
        echo "<script>alert('Registration successful!'); window.location.href='FarmerDashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>

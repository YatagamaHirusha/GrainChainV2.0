<?php
session_start();
include('database.php');

// Get POST data
$officer_id = $_POST['officer_id'] ?? null;
$role = $_POST['role'] ?? null;
$new_district = $_POST['district'] ?? null; // optional, not in form; fallback handled
$new_division = $_POST['new_division'] ?? null;
$new_subdivision = $_POST['new_subdivision'] ?? null;
$new_warehouse = $_POST['new_warehouse'] ?? null;

if (!$officer_id || !$role || !$new_division) {
    die("Invalid data provided.");
}

try {
    if ($role === 'fo') {
        if($new_district){
            $new_district = $row['district'] ?? null;
            $get_new_ditrict_doid = $con->prepare("SELECT DOID FROM DeputyOfficer WHERE assign_district = ?");
            $get_new_ditrict_doid->bind_param("s", $new_district);
            $get_new_ditrict_doid->execute();
            $res = $get_new_ditrict_doid->get_result();
            $roww = $res->fetch_assoc();
            $new_district_doid = $roww['DOID'] ?? null;
        }
        if(!$new_district_doid){
            die("Deputy Officer not found in selected district. Reassign declined.");
        }
        // Update FieldOfficer
        $stmt = $con->prepare("UPDATE FieldOfficer SET assign_division = ?, assign_district = ?, sub_divison = ?, DOID = ? WHERE FOID = ?");
        $stmt->bind_param("sssii", $new_division, $new_district, $new_subdivision, $new_district_doid, $officer_id);

    } elseif ($role === 'wo') {
        // Look up WID from warehouse name
        $wid = null;
        if ($new_warehouse) {
            $stmt_lookup = $con->prepare("SELECT WID, district FROM Warehouse WHERE warehouse_name = ? LIMIT 1");
            $stmt_lookup->bind_param("s", $new_warehouse);
            $stmt_lookup->execute();
            $result = $stmt_lookup->get_result();
            $row = $result->fetch_assoc();
            $wid = $row['WID'] ?? null;
            $new_district = $row['district'] ?? null;
            $get_new_ditrict_doid = $con->prepare("SELECT DOID FROM DeputyOfficer WHERE assign_district = ?");
            $get_new_ditrict_doid->bind_param("s", $new_district);
            $get_new_ditrict_doid->execute();
            $res = $get_new_ditrict_doid->get_result();
            $roww = $res->fetch_assoc();
            $new_district_doid = $roww['DOID'] ?? null;
        }

        if (!$wid) {
            die("Warehouse not found.");
        }
        if(!$new_district_doid){
            die("Deputy Officer not found in selected district. Reassign declined.");
        }

        // Update WarehouseOfficer
        $stmt = $con->prepare("UPDATE WarehouseOfficer SET assign_division = ?, assign_district = ?, WID = ?, DOID = ? WHERE WOID = ?");
        $stmt->bind_param("ssiii", $new_division, $new_district, $wid, $new_district_doid, $officer_id);
    } else {
        die("Invalid role.");
    }

    if ($stmt->execute()) {
        header("Location: DeputyOfficerDashboard.php?success=1");
        exit();
    } else {
        throw new Exception("Update failed.");
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

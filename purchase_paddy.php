<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
require 'database.php';

$foid = $_SESSION['UID']; // Replace with real FO login session later

// Get field officer district
$districtQuery = "SELECT assign_district FROM FieldOfficer WHERE FOID = $foid";
$districtResult = $con->query($districtQuery);
$field_district = $districtResult->fetch_assoc()['assign_district'] ?? '';

// Get warehouse list for this district
$warehouses = [];
$whQuery = "
    SELECT W.WID, W.warehouse_name 
    FROM Warehouse W
    JOIN WarehouseOfficer WO ON WO.WID = W.WID
    WHERE WO.assign_district = '$field_district'
";
$whResult = $con->query($whQuery);
while ($row = $whResult->fetch_assoc()) {
    $warehouses[] = $row;
}

// Get lands and related farmer info in this district
$lands = [];
$landQuery = "
    SELECT 
        L.LID, L.location, L.land_size_in_acres,
        U.full_name AS farmer_name, F.FID
    FROM Land L
    JOIN Farmer F ON F.FID = L.FID
    JOIN User U ON U.UID = F.FID
    WHERE L.district = '$field_district'
";
$landResult = $con->query($landQuery);
while ($row = $landResult->fetch_assoc()) {
    $lands[] = $row;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fid = $_POST['fid'];
    $lid = $_POST['lid'];
    $wid = $_POST['warehouse'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $date = $_POST['purchase_date'];
    $paddy_type = $_POST['paddy_type'];
    $note = $_POST['note'];

    $sql = "
        INSERT INTO PurchasePaddy (FOID, FID, WID, purchase_paddy_type, purchase_paddy_quantity, price_per_kg, purchase_date, note)
        VALUES ($foid, $fid, $wid, '$paddy_type', $quantity, $price, '$date', '$note')
    ";


    /*  For now let's remove land from purchasing paddy
    $sql = "
        INSERT INTO PurchasePaddy (FOID, FID, LID, WID, quantity_in_kg, price_per_kg, purchase_date)
        VALUES ($foid, $fid, $lid, $wid, $quantity, $price, '$date')
    ";
    */

    if ($con->query($sql)) {
        // for now let's update whole warehouse current stock for all the paddy types. Later we'll update this to manage stock by paddy type
        $updateWarehouse = "UPDATE Warehouse SET current_stock_KG = current_stock_KG + $quantity WHERE WID = $wid";
        $con->query($updateWarehouse);
        echo "<script>alert('Paddy purchased successfully!'); window.location='purchase_paddy.php';</script>";
    } else {
        echo "<script>alert('Error: " . $con->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Paddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>🌾 Purchase Paddy from Farmers in <?= htmlspecialchars($field_district) ?> District</h3>

    <?php if (count($lands) > 0): ?>
        <?php foreach ($lands as $land): ?>
            <div class="card my-4">
                <div class="card-header bg-success text-white">
                    <?= htmlspecialchars($land['farmer_name']) ?> – <?= $land['location'] ?> (<?= $land['land_size_in_acres'] ?> acres)
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="fid" value="<?= $land['FID'] ?>">
                        <input type="hidden" name="lid" value="<?= $land['LID'] ?>">

                        <div class="row g-3">
                            <div class="col-md-2">
                                <label>Paddy Type</label>
                                <select name="paddy_type" class="form-select" required>
                                    <option value="">-- Choose --</option>
                                    <option value="Nadu">Nadu</option>
                                    <option value="Keeri Samba">Keeri Samba</option>
                                    <option value="Samba">Samba</option>
                                    <option value="Suwadal">Suwadal</option>
                                    <option value="Kekulu">Kekulu</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Quantity (kg)</label>
                                <input type="number" name="quantity" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label>Price per kg (Rs)</label>
                                <input type="number" step="0.1" name="price" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Purchase Date</label>
                                <input type="date" name="purchase_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Select Warehouse</label>
                                <select name="warehouse" class="form-select" required>
                                    <option value="">-- Choose --</option>
                                    <?php foreach ($warehouses as $wh): ?>
                                        <option value="<?= $wh['WID'] ?>"><?= htmlspecialchars($wh['warehouse_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label>Note (Optional)</label>
                                <input type="text" name="note" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3" type="submit">Purchase & Assign</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No lands found in your district.</p>
    <?php endif; ?>
</div>
</body>
</html>

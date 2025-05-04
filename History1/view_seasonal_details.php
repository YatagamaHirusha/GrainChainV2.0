<?php
session_start();
include 'database.php';

// You can use session UID or FID depending on how you're storing farmer login
$farmerID = $_SESSION['user_id'] ?? 2; // assume this is FID (Farmer ID)

// Get farmer lands
$getLands = "SELECT LID FROM Land WHERE FID = ?";
$stmtLands = $con->prepare($getLands);
$stmtLands->bind_param("i", $farmerID);
$stmtLands->execute();
$landResult = $stmtLands->get_result();

$lands = [];
while ($row = $landResult->fetch_assoc()) {
    $lands[] = $row['LID'];
}
$stmtLands->close();

$seasonalRecords = [];

if (!empty($lands)) {
    $in = implode(',', array_fill(0, count($lands), '?'));
    $sql = "SELECT * FROM SeasonalDetails WHERE LID IN ($in) ORDER BY SDID DESC";
    $stmt = $con->prepare($sql);

    $stmt->bind_param(str_repeat('i', count($lands)), ...$lands);
    $stmt->execute();
    $seasonalRecords = $stmt->get_result();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $sdid = $_POST['sdid'];
    $harvest_date = $_POST['harvest_date'];
    $harvest_qty = $_POST['harvest_qty'];
    $note = $_POST['note'];
    $status = $_POST['paddy_status'];

    $updateSQL = "UPDATE SeasonalDetails SET 
        harvest_date = ?, 
        harvest_paddy_quantity = ?, 
        note = ?, 
        paddy_status = ? 
        WHERE SDID = ?";

    $stmt = $con->prepare($updateSQL);
    $stmt->bind_param("sdssi", $harvest_date, $harvest_qty, $note, $status, $sdid);
    
    if ($stmt->execute()) {
        header("Location: view_seasonal_details.php"); // Refresh
        exit;
    } else {
        echo "<div class='alert alert-danger'>Failed to update record.</div>";
    }

    $stmt->close();
}


$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Seasonal Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/minty/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4">My Seasonal Details</h2>
    <a href="add_seasonal_details.php" class="btn btn-primary mb-3">Add New Seasonal Detail</a>

    <?php if ($seasonalRecords && $seasonalRecords->num_rows > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Season</th>
                    <th>Year</th>
                    <th>Land ID</th>
                    <th>Paddy Type</th>
                    <th>Plant Date</th>
                    <th>Expected Harvest (KG)</th>
                    <th>Harvest Date</th>
                    <th>Harvest Qty</th>
                    <th>Status</th>
                    <th>Verification</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $seasonalRecords->fetch_assoc()): ?>
                    <tr>
                        <form method="post" action="">
                            <input type="hidden" name="sdid" value="<?= $row['SDID'] ?>">

                            <td><?= $row['season_name'] ?></td>
                            <td><?= $row['year'] ?></td>
                            <td><?= $row['LID'] ?></td>
                            <td><?= $row['paddy_type'] ?></td>
                            <td><?= $row['plant_date'] ?></td>
                            <td><?= $row['expected_harvest_in_KG'] ?></td>

                            <?php if ($row['paddy_status'] === 'Ongoing'): ?>
                                <td><input type="date" name="harvest_date" class="form-control" value="<?= $row['harvest_date'] ?>"></td>
                                <td><input type="number" step="0.01" name="harvest_qty" class="form-control" value="<?= $row['harvest_paddy_quantity'] ?>"></td>
                                <td>
                                    <select name="paddy_status" class="form-select">
                                        <option value="Ongoing" <?= $row['paddy_status'] == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                        <option value="Completed" <?= $row['paddy_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </td>
                                <td><?= $row['verification_status'] ?? '-' ?></td>
                                <td><input type="text" name="note" class="form-control" value="<?= $row['note'] ?>"></td>
                                <td><button type="submit" name="update" class="btn btn-sm btn-success">Save</button></td>
                            <?php else: ?>
                                <td><?= $row['harvest_date'] ?? '-' ?></td>
                                <td><?= $row['harvest_paddy_quantity'] ?? '-' ?></td>
                                <td><?= $row['paddy_status'] ?></td>
                                <td><?= $row['verification_status'] ?? '-' ?></td>
                                <td><?= $row['note'] ?? '-' ?></td>
                                <td><span class="text-muted">Finalized</span></td>
                            <?php endif; ?>
                        </form>
                    </tr>
                <?php endwhile ?>

            </tbody>
        </table>
    <?php else: ?>
        <p>No seasonal records found. <a href="add_seasonal_details.php">Add one here</a>.</p>
    <?php endif ?>
</div>

</body>
</html>

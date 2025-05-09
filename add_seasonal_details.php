<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
include 'database.php';

// Assuming farmer is logged in and their UID is stored in session
$farmer_id = $_SESSION['UID'];

if (!$farmer_id) {
    echo "You must be logged in as a farmer.";
    exit;
}

// Get lands for the farmer
$lands = [];
$sql = "SELECT LID, land_reg_no, location FROM Land WHERE FID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $lands[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Seasonal Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Add Seasonal Details</h2>

    <form action="insert_seasonal_details.php" method="POST">
        <div class="mb-3">
            <label for="LID" class="form-label">Select Land</label>
            <select name="LID" id="LID" class="form-select" required>
                <option value="">-- Select Land --</option>
                <?php foreach ($lands as $land): ?>
                    <option value="<?= $land['LID'] ?>">
                        <?= $land['land_reg_no'] ?> - <?= $land['location'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="season_name" class="form-label">Season</label>
            <select name="season_name" class="form-select" required>
                <option value="Yala">Yala</option>
                <option value="Maha">Maha</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="paddy_type" class="form-label">Paddy Type</label>
            <input type="text" name="paddy_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="plant_date" class="form-label">Plant Date</label>
            <input type="date" name="plant_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="expected_harvest_in_KG" class="form-label">Expected Harvest (KG)</label>
            <input type="number" step="0.01" name="expected_harvest_in_KG" class="form-control" required>
        </div>

        <input type="hidden" name="FID" value="<?= $farmer_id ?>">

        <button type="submit" class="btn btn-success">Add Seasonal Details</button>
    </form>
</div>

</body>
</html>

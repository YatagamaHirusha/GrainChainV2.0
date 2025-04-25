<!-- apply_subsidy.php -->
<?php
session_start();
require 'database.php';

$farmer_id = $_SESSION['FID'] ?? 1;
$seasonDetails = []; // This will hold ongoing seasonal details fetched from DB

// Fetch ongoing seasonal details for the farmer
// Updated query to reflect the new structure
$sql = "SELECT SDID, season_name, year, paddy_type, land_size_in_acres, paddy_status
        FROM SeasonalDetails 
        JOIN Land ON SeasonalDetails.LID = Land.LID
        WHERE Land.FID = $farmer_id AND SeasonalDetails.paddy_status = 'Completed'
        ORDER BY SDID DESC";

$result = $con->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $seasonDetails[] = $row;
    }
}

// If no ongoing season, show alert
if (empty($seasonDetails)) {
    echo "<div class='alert alert-warning'>You need to add seasonal details before applying for a subsidy.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply for Fertilizer Subsidy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Apply for Fertilizer Subsidy</h3>

    <?php if (!empty($seasonDetails)): ?>
    <form action="submit_subsidy.php" method="POST">
        <div class="mb-3">
            <label for="season" class="form-label">Select Seasonal Details</label>
            <select name="SDID" id="season" class="form-select" required>
                <?php foreach ($seasonDetails as $season): ?>
                    <option value="<?= $season['SDID'] ?>">
                        <?= "{$season['season_name']} {$season['year']} - {$season['paddy_type']}" ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Reason for Subsidy Request</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Apply for Subsidy</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>

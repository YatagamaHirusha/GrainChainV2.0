<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
require 'database.php';

$foid = $_SESSION['UID'];

// Get officer's agri division
$sql = "SELECT assign_division FROM FieldOfficer WHERE FOID = $foid";
$res = $con->query($sql);
$division = "";
if ($res && $row = $res->fetch_assoc()) {
    $division = $row['assign_division'];
}
$sql = "
SELECT SD.SDID, SD.season_name, SD.year, SD.paddy_type, SD.plant_date, SD.expected_harvest_in_KG,
       SD.harvest_date, SD.harvest_paddy_quantity, L.land_reg_no, L.location, F.FID, U.full_name
FROM SeasonalDetails SD
JOIN Land L ON SD.LID = L.LID
JOIN Farmer F ON L.FID = F.FID
JOIN User U ON U.UID = F.FID
WHERE SD.paddy_status = 'Completed' 
  AND SD.verification_status IS NULL 
  AND L.agricultural_service_area = '$division'";
$result = $con->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Seasonal Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>🌾 Verify Completed Seasonal Details (Division: <?= htmlspecialchars($division) ?>)</h3>
  <?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Farmer</th>
          <th>Land</th>
          <th>Season</th>
          <th>Year</th>
          <th>Type</th>
          <th>Planted</th>
          <th>Harvested</th>
          <th>Qty (KG)</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['land_reg_no']) ?></td>
          <td><?= $row['season_name'] ?></td>
          <td><?= $row['year'] ?></td>
          <td><?= htmlspecialchars($row['paddy_type']) ?></td>
          <td><?= $row['plant_date'] ?></td>
          <td><?= $row['harvest_date'] ?></td>
          <td><?= $row['harvest_paddy_quantity'] ?></td>
          <td>
            <form method="POST" action="verify_seasonal_details_action.php">
              <input type="hidden" name="sdid" value="<?= $row['SDID'] ?>">
              <button name="action" value="verify" class="btn btn-success btn-sm">✅ Verify</button>
              <button name="action" value="refute" class="btn btn-danger btn-sm">❌ Refute</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No seasonal details to verify yet.</div>
  <?php endif; ?>
</div>
</body>
</html>

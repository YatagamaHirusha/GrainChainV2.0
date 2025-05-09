<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
require 'database.php';

$foid = $_SESSION['UID'];

// get officer division
$sql = "SELECT assign_division FROM FieldOfficer WHERE FOID = $foid";
$res = $con->query($sql);
$division = "";
if ($res && $row = $res->fetch_assoc()) {
    $division = $row['assign_division'];
}

// fetch unverified farmers who have land in that division
$sql = "
SELECT 
    U.full_name, 
    U.contact_number1,
    U.contact_number2, 
    U.email, 
    F.FID,
    L.LID,
    L.land_reg_no,
    L.location AS land_location,
    L.land_size_in_acres,
    L.land_type,
    L.irrigation_type,
    L.is_verify
FROM FieldOfficer FO
JOIN Land L ON L.agricultural_service_area = FO.assign_division
JOIN Farmer F ON L.FID = F.FID
JOIN User U ON F.FID = U.UID
WHERE FO.FOID = $foid AND L.is_verify = 0
";


$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Farmers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3 class="mb-4">🌾 Lands to Verify (Division: <?= htmlspecialchars($division) ?>)</h3>

    <?php if ($result && $result->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Farmer Name</th>
            <th>Contact Number</th>
            <th>Contact Number (Optional)</th>
            <th>Email</th>
            <th>Land Reg No</th>
            <th>Location</th>
            <th>Land Type</th>
            <th>Irrigation Type</th>
            <th>Size (acres)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['full_name'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['contact_number1'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['contact_number2'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['email'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['land_reg_no'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['land_location'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['land_type'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['irrigation_type'] ?? '—') ?></td>
              <td><?= htmlspecialchars($row['land_size_in_acres'] ?? '—') ?></td>
              <td>
                <form method="POST" action="verify_land_action.php">
                  <input type="hidden" name="lid" value="<?= $row['LID'] ?>">
                  <input type="hidden" name="fid" value="<?= $row['FID'] ?>">
                  <button name="action" value="verify" class="btn btn-success btn-sm">✅ Verify</button>
                  <button name="action" value="reject" class="btn btn-danger btn-sm">❌ Reject</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-info">No lands to verify for your division.</div>
    <?php endif; ?>

</div>
</body>
</html>

<?php
session_start();
require 'database.php';

$foid = $_SESSION['FOID'] ?? 3;

// get officer division
$sql = "SELECT assign_division FROM FieldOfficer WHERE FOID = $foid";
$res = $con->query($sql);
$division = "";
if ($res && $row = $res->fetch_assoc()) {
    $division = $row['assign_division'];
}

// fetch unverified farmers who have land in that division
$sql = "SELECT DISTINCT U.full_name, U.contact_number1, U.contact_number2, U.email, F.FID 
        FROM User U 
        JOIN Farmer F ON F.FID = U.UID
        JOIN Land L ON L.FID = F.FID
        WHERE L.is_verify = 0 AND L.regional_secretariat_division = '$division'";

$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify Farmers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3 class="mb-4">👤 Farmers to Verify (Division: <?= htmlspecialchars($division) ?>)</h3>

  <?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Contact</th>
          <th>Contact (optional)</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($farmer = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($farmer['full_name']) ?></td>
            <td><?= htmlspecialchars($farmer['contact_number1']) ?></td>
            <td><?= htmlspecialchars($farmer['contact_number2'] ?? '—') ?></td>
            <td><?= htmlspecialchars($farmer['email']) ?></td>
            <td>
              <form method="POST" action="verify_farmer_action.php" class="d-inline">
                <input type="hidden" name="fid" value="<?= $farmer['FID'] ?>">
                <button name="action" value="verify" class="btn btn-success btn-sm">✅ Verify</button>
                <button name="action" value="reject" class="btn btn-danger btn-sm">❌ Reject</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No farmers to verify at the moment.</div>
  <?php endif; ?>
</div>
</body>
</html>

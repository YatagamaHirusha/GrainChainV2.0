<?php
session_start();
include 'database.php';

$fid = $_SESSION['FID'] ?? 1;

$sql = "SELECT SD.SDID, SD.season_name, SD.year, L.location, SD.paddy_type, SD.plant_date, 
               SD.harvest_date, SD.expected_harvest_in_KG, SD.status
        FROM SeasonalDetails SD
        JOIN Land L ON SD.LID = L.LID
        WHERE SD.FID = ? AND SD.status = 'Ongoing'";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $fid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Seasonal Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Update Ongoing Seasonal Details</h2>
    <?php if ($result->num_rows > 0): ?>
    <form method="POST" action="update_seasonal_details_action.php">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Season</th>
                    <th>Year</th>
                    <th>Land Location</th>
                    <th>Paddy Type</th>
                    <th>Plant Date</th>
                    <th>Harvest Date</th>
                    <th>Expected Harvest (KG)</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['season_name'] ?></td>
                    <td><?= $row['year'] ?></td>
                    <td><?= $row['location'] ?></td>
                    <td><?= $row['paddy_type'] ?></td>
                    <td><?= $row['plant_date'] ?></td>
                    <td><input type="date" name="harvest_date_<?= $row['SDID'] ?>" value="<?= $row['harvest_date'] ?>"></td>
                    <td><input type="number" step="0.01" name="expected_kg_<?= $row['SDID'] ?>" value="<?= $row['expected_harvest_in_KG'] ?>"></td>
                    <td>
                        <select name="status_<?= $row['SDID'] ?>" class="form-select">
                            <option value="Ongoing" <?= ($row['status'] == 'Ongoing') ? 'selected' : '' ?>>Ongoing</option>
                            <option value="Completed" <?= ($row['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </td>
                    <td><button type="submit" name="update" value="<?= $row['SDID'] ?>" class="btn btn-primary btn-sm">Update</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
    <?php else: ?>
        <div class="alert alert-info">No ongoing seasonal details found. You can add a new one.</div>
    <?php endif; ?>
</div>
</body>
</html>

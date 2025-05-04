<?php
include 'database.php';
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
$division = $_SESSION['assign_division']; // Get the logged-in officer's division

// Fetch all pending subsidy requests for the officer's division
$sql = "SELECT fs.*, 
               u.full_name AS farmer_name, 
               l.land_reg_no, 
               l.land_size_in_acres AS area, 
               sd.season_name, 
               sd.harvest_date, 
               fs.amount_of_fertlizer_in_money, 
               fs.amount_of_fertilizer_in_KG, 
               fs.application_date 
        FROM fertilizesubsidy fs
        JOIN land l ON fs.LID = l.LID
        JOIN seasonaldetails sd ON fs.SDID = sd.SDID
        JOIN farmer f ON fs.FID = f.FID
        JOIN user u ON f.FID = u.UID
        WHERE fs.status = 'Pending'
        AND l.division = '$division'
        ORDER BY fs.application_date DESC";

$result = mysqli_query($con, $sql);
?>

<!-- Enhanced Table Design -->
<div class="container mt-4">
  <h3 class="text-center mb-4">Subsidy Requests for Verification</h3>
  <table class="table table-bordered table-hover table-striped">
    <thead class="thead-dark">
      <tr>
        <th>Farmer</th>
        <th>Land Reg No</th>
        <th>Season</th>
        <th>Size of Land (ha)</th>
        <th>Fertilizer Amount (kg)</th>
        <th>Money (LKR)</th>
        <th>Application Date</th>
        <th>Note</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?= $row['farmer_name'] ?></td>
          <td><?= $row['land_reg_no'] ?></td>
          <td><?= $row['season_name'] ?></td>
          <td><?= $row['size_of_land'] ?> ha</td>
          <td><?= $row['amount_of_fertilizer_in_KG'] ?> kg</td>
          <td><?= $row['amount_of_fertlizer_in_money'] ?> LKR</td>
          <td><?= $row['application_date'] ?></td>
          <td>
            <input type="text" name="note_<?= $row['id'] ?>" class="form-control form-control-sm" placeholder="Enter note" />
          </td>
          <td>
            <!-- Form for approve/reject buttons -->
            <form method="post" action="verify_subsidy_action.php" class="d-flex gap-2">
              <input type="hidden" name="subsidy_id" value="<?= $row['id'] ?>" />
              <button name="approve" class="btn btn-success btn-sm">Approve</button>
              <button name="reject" class="btn btn-danger btn-sm">Reject</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

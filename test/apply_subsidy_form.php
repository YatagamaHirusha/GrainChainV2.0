<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'database.php';

$farmer_id = $_SESSION['farmer_id']??1;
$lands_result = mysqli_query($con, "SELECT land_reg_no FROM Land WHERE FID = '$farmer_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fertilizer Subsidy Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Apply for Fertilizer Subsidy</h4>
    </div>
    <div class="card-body">

      <form method="POST" action="submit_subsidy.php">

        <!-- Land Selection -->
        <div class="mb-4">
          <label for="land" class="form-label">Select Land (by Registration No):</label>
          <select name="land_reg_no" id="land" class="form-select" required>
            <option value="">-- Select Land --</option>
            <?php while ($land = mysqli_fetch_assoc($lands_result)) {
              echo "<option value='{$land['land_reg_no']}'>{$land['land_reg_no']}</option>";
            } ?>
          </select>
        </div>

        <!-- Display land & seasonal info -->
        <div id="land-details" class="border p-3 rounded bg-white mb-4" style="display: none;"></div>

        <!-- Cultivated Area Input -->
        <div class="mb-4">
          <label for="cultivated_area" class="form-label">Cultivated Land Area (acres):</label>
          <input type="number" step="0.01" name="cultivated_area" class="form-control" required>
        </div>
        <input type="hidden" name="land_id" value="<?php echo $land['LID']; ?>">
        <!-- <input type="hidden" name="land_id" value="<?php echo $land['LID']; ?>"> -->
        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-success btn-lg">Submit Application</button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
document.getElementById('land').addEventListener('change', function () {
  const landRegNo = this.value;
  const detailsDiv = document.getElementById("land-details");
  const submitBtn = document.querySelector("button[type='submit']");

  if (landRegNo !== "") {
    fetch("fetch_land_and_seasonal_details.php?land_reg_no=" + landRegNo)
      .then(response => response.text())
      .then(data => {
        detailsDiv.innerHTML = data;
        detailsDiv.style.display = "block";

        const isVerified = document.getElementById("land-verified");
        if (isVerified && isVerified.value === "0") {
          submitBtn.disabled = true;
        } else {
          submitBtn.disabled = false;
        }        
      });
  } else {
    detailsDiv.innerHTML = "";
    detailsDiv.style.display = "none";
    submitBtn.disabled = false;
  }
});
</script>

</body>
</html>


<!-- <?php
session_start();
include 'database.php'; // update path as needed

$farmer_id = $_SESSION['farmer_id'] ?? 1; // assuming you set this during login

// Fetch lands for the logged-in farmer
$lands_result = mysqli_query($con, "SELECT land_reg_no FROM Land WHERE FID = '$farmer_id'");
?>

<h2>Apply for Fertilizer Subsidy</h2>

<form method="POST" action="submit_subsidy.php">
  <label for="land">Select Land (by Land Reg No):</label>
  <select name="land_reg_no" id="land" required>
    <option value="">--Select Land--</option>
    <?php while ($land = mysqli_fetch_assoc($lands_result)) {
      echo "<option value='{$land['land_reg_no']}'>{$land['land_reg_no']}</option>";
    } ?>
  </select>

  <div id="land-details" style="margin-top: 15px;"></div>

  <label for="cultivated_area">Cultivated Land Amount (in acres):</label>
  <input type="number" step="0.01" name="cultivated_area" required><br><br>

  <input type="submit" name="submit" value="Submit Application">
</form>

<script>
  document.getElementById('land').addEventListener('change', function () {
    const landRegNo = this.value;

    if (landRegNo !== "") {
      fetch("fetch_land_and_seasonal_details.php?land_reg_no=" + landRegNo)
        .then(response => response.text())
        .then(data => {
          document.getElementById("land-details").innerHTML = data;
        });
    } else {
      document.getElementById("land-details").innerHTML = "";
    }
  });
</script>
-->
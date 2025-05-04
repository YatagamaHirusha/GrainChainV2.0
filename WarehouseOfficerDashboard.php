<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
include('database.php');
$woid = $_SESSION['UID']; 



// Get stock data for this warehouse officer
$sql = "SELECT * FROM Warehouse W join WarehouseOfficer WO on WO.WID = W.WID WHERE WOID = $woid";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warehouse Officer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f1f8e9;
    }
    .dashboard-container {
      max-width: 1200px;
      margin: auto;
      padding: 2rem;
    }
    .card {
      border-radius: 1rem;
    }
    h3 {
      color: #2e7d32;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">GrainChain</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <!--<span class="nav-link text-white">Welcome, Officer </span>-->
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-outline-light ms-4" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="dashboard-container">
  <h3 class="mb-4">Warehouse Officer Dashboard</h3>

  <!-- Stock Display Section -->
  <div class="card mb-5 p-4">
    <h5 class="mb-3">Available Stock</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-success">
          <tr>
            <th>Warehouse ID</th>
            <th>Warehouse Name</th>
            <th>Division</th>
            <th>Capacity (KG)</th>
            <th>Current Stock (KG)</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['WID'] ?></td>
                <td><?= $row['warehouse_name'] ?></td>
                <td><?= $row['division'] ?></td>
                <td><?= $row['capacity_KG'] ?></td>
                <td><?= $row['current_stock_KG'] ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5">No stock available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sell Paddy Section -->
  <div class="card p-4">
    <h5 class="mb-3">Sell Paddy</h5>
    <form action="submit_sell_paddy.php" method="POST">
      <input type="hidden" name="woid" value="<?= $woid ?>" />
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Buyer Name</label>
          <input type="text" name="buyer_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Paddy Type</label>
          <input type="text" name="paddy_type" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Quantity (KG)</label>
          <input type="number" name="paddy_quantity" step="0.01" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Price per KG (Rs)</label>
          <input type="number" name="price_per_KG" step="0.01" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Note (optional)</label>
          <textarea name="note" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-success">Submit Sale</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>

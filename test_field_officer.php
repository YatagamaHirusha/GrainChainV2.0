<?php
session_start();
require 'database.php';

$foid = $_SESSION['FOID'] ?? 3; // simulate for now

// Get field officer name and division
$sql = "SELECT U.full_name, F.assign_division 
        FROM User U 
        JOIN FieldOfficer F ON F.FOID = U.UID 
        WHERE F.FOID = $foid";
$result = $con->query($sql);

$name = "Field Officer";
$division = "Unknown";

if ($result && $row = $result->fetch_assoc()) {
    $name = $row['full_name'];
    $division = $row['assign_division'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Field Officer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }
    .welcome-box {
      background-color: #e0f7fa;
      padding: 1.5rem;
      border-radius: 10px;
      margin-bottom: 2rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .card:hover {
      transform: scale(1.02);
      transition: 0.3s;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">GrainChain</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="welcome-box">
    <h4>Welcome, <?= htmlspecialchars($name) ?> 👨‍🌾</h4>
    <p>Assigned Division: <strong><?= htmlspecialchars($division) ?></strong></p>
    <p>Use this dashboard to manage farmer-related activities in your area.</p>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">👤 Verify Farmers</h5>
          <p class="card-text">Approve or reject new farmers in your division.</p>
          <a href="verify_farmers.php" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">💊 Verify Subsidies</h5>
          <p class="card-text">Review and approve subsidy requests.</p>
          <a href="verify_subsidies.php" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">🌾 Verify Seasonal Details</h5>
          <p class="card-text">Check seasonal data submitted by farmers.</p>
          <a href="verify_seasonal.php" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">💰 Purchase Paddy</h5>
          <p class="card-text">Record paddy purchases from farmers.</p>
          <a href="purchase_paddy.php" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">🏬 Assign to Warehouse</h5>
          <p class="card-text">Manage paddy stock and assign to warehouses.</p>
          <a href="assign_warehouse.php" class="btn btn-primary">Go</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

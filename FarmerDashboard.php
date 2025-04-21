<!-- dashboard.php -->
<?php
// Start session and validate login (optional: add actual logic)
session_start();
require 'database.php';

$farmer_id = $_SESSION['FID'] ?? 1;

// fetch farmer details 
$name = " ";
$sql = "SELECT U.full_name FROM User U JOIN Farmer F ON F.$farmer_id = U.$farmer_id";
$result = $con->query($sql);
if($result && $row = $result->fetch_assoc()){
  $name = $row['full_name'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Dashboard - GrainChain</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card:hover {
      transform: scale(1.02);
      transition: 0.3s;
    }
    .welcome-box {
      background: #e2f0d9;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">GrainChain</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="add_paddy.php">Add Paddy</a></li>
        <li class="nav-item"><a class="nav-link" href="apply_subsidy.php">Subsidy</a></li>
        <li class="nav-item"><a class="nav-link" href="request_sell.php">Sell Paddy</a></li>
        <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
        <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Container -->
<div class="container mt-4">
  <!-- Welcome Box -->
  <div class="welcome-box mb-4">
    <h4>Welcome, <?= $name ?> 👋</h4>
    <p>This is your personalized dashboard. Here you can manage your paddy details, subsidy, and sale requests.</p>
  </div>

  <!-- Action Cards -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">🌾 Add Paddy Details</h5>
          <p class="card-text">Enter your seasonal paddy details here.</p>
          <a href="add_paddy.php" class="btn btn-success">Add Now</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">🌱 Apply for Subsidy</h5>
          <p class="card-text">Request government fertilizer subsidy.</p>
          <a href="apply_subsidy.php" class="btn btn-success">Apply</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">💼 Sell Paddy</h5>
          <p class="card-text">Submit a request to sell your harvest.</p>
          <a href="request_sell.php" class="btn btn-success">Sell</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Notification Preview -->
  <div class="mt-5">
    <h5>🔔 Latest Notifications</h5>
    <ul class="list-group">
      <li class="list-group-item">✅ Subsidy Approved for Yala 2025</li>
      <li class="list-group-item">📦 City Agent accepted your sell request</li>
      <li class="list-group-item">📩 New message from City Agent</li>
    </ul>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

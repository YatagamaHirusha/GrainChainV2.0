<?php
include 'database.php';
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.html");
    exit();
}
$doid = $_SESSION['UID'];

// Fetch summary counts
$total_farmers = $con->query("SELECT 
          COUNT(DISTINCT f.FID) AS total_farmers
      FROM 
          deputyofficer d
      JOIN 
          fieldofficer fo ON d.DOID = fo.DOID
      JOIN  
          land l ON fo.FOID = l.FOID
      JOIN 
          farmer f ON l.FID = f.FID
      WHERE d.DOID = $doid")->fetch_assoc()['total_farmers'];
$total_fo = $con->query("SELECT COUNT(*) AS count FROM FieldOfficer WHERE DOID = $doid")->fetch_assoc()['count'];
$total_wo = $con->query("SELECT COUNT(*) AS count FROM WarehouseOfficer WHERE DOID = $doid")->fetch_assoc()['count'];
$total_stock = $con->query("SELECT SUM(current_stock_KG) AS total 
      FROM Warehouse 
      WHERE WID IN (SELECT DISTINCT WID FROM WarehouseOfficer WHERE DOID = $doid)")->fetch_assoc()['total'];

// Fetch officer details with names
$fo_query = "
  SELECT FO.FOID, FO.assign_division AS division, U.full_name 
  FROM FieldOfficer FO 
  JOIN USER U ON U.UID = FO.FOID 
  WHERE FO.DOID = $doid
";
$fo_result = $con->query($fo_query);

$wo_query = "
  SELECT WO.WOID, WO.assign_division AS division, WO.assign_district, U.full_name 
  FROM WarehouseOfficer WO 
  JOIN USER U ON U.UID = WO.WOID 
  WHERE WO.DOID = $doid
";
$wo_result = $con->query($wo_query);



// Fetch unverified officers
$unverified_fo = $con->query("
  SELECT FO.FOID, FO.assign_division AS division, U.full_name 
  FROM FieldOfficer FO 
  JOIN USER U ON U.UID = FO.FOID 
  WHERE FO.DOID = $doid AND FO.verification_status = 'Pending'
");

$unverified_wo = $con->query("
  SELECT WO.WOID, WO.assign_division AS division, U.full_name 
  FROM WarehouseOfficer WO 
  JOIN USER U ON U.UID = WO.WOID 
  WHERE WO.DOID = $doid AND WO.verification_status = 'Pending'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deputy Officer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #e8f5e9;
      font-family: 'Segoe UI', sans-serif;
    }
    .dashboard-container {
      max-width: 1200px;
      margin: auto;
      padding: 2rem;
    }
    h3 {
      color: #2e7d32;
    }
  </style>
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
<!-- Deputy Officer Dashboard Layout -->
<div class="container mt-5">
  <h2 class="mb-4">Deputy Officer Dashboard</h2>
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-white bg-primary">
        <div class="card-body">
          <h5 class="card-title">Total Farmers</h5>
          <p class="card-text"><?= $total_farmers ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-info">
        <div class="card-body">
          <h5 class="card-title">Field Officers</h5>
          <p class="card-text"><?= $total_fo ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-success">
        <div class="card-body">
          <h5 class="card-title">Warehouse Officers</h5>
          <p class="card-text"><?= $total_wo ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white bg-warning">
        <div class="card-body">
          <h5 class="card-title">Total Stock</h5>
          <p class="card-text"><?= $total_stock ?> Kg</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Officer Tables -->
  <div class="card p-4">
    <h5 class="mb-3">Assigned Field Officers</h5>
    <table class="table table-bordered table-hover">
      <thead class="table-secondary">
        <tr>
          <th>Officer ID</th>
          <th>Full Name</th>
          <th>Division</th>
          <th>Reassign</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $fo_result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['FOID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['division'] ?></td>
            <td>
              <form action="reassign_officer.php" method="POST" class="row gx-1 gy-2 align-items-center">
                <input type="hidden" name="officer_id" value="<?= $row['WOID'] ?? $row['FOID'] ?>">
                <input type="hidden" name="role" value="<?= isset($row['WOID']) ? 'wo' : 'fo' ?>">
                <input type="hidden" name="new_division" class="division-hidden">
                <input type="hidden" name="new_subdivision" class="subdivision-hidden">
                <input type="hidden" name="new_warehouse" class="warehouse-hidden">
                <div class="col-auto">
                  <select class="form-select form-select-sm district-select" required>
                    <option value="">District</option>
                  </select>
                </div>
                <div class="col-auto">
                  <select class="form-select form-select-sm division-select" required disabled>
                    <option value="">Division</option>
                  </select>
                </div>
                <div class="col-auto">
                  <select class="form-select form-select-sm subdivision-select" required disabled>
                    <option value="">SubDivision</option>
                  </select>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-sm btn-outline-success">Reassign</button>
                </div>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="card mt-4 p-4">
    <h5 class="mb-3">Assigned Warehouse Officers</h5>
    <table class="table table-bordered table-hover">
      <thead class="table-secondary">
        <tr>
          <th>Officer ID</th>
          <th>Full Name</th>
          <th>Division</th>
          <th>Reassign</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $wo_result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['WOID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['division'] ?></td>
            <td>
              <form action="reassign_officer.php" method="POST" class="row gx-1 gy-2 align-items-center">
                <input type="hidden" name="officer_id" value="<?= $row['WOID'] ?? $row['FOID'] ?>">
                <input type="hidden" name="role" value="<?= isset($row['WOID']) ? 'wo' : 'fo' ?>">
                <input type="hidden" name="new_division" class="division-hidden">
                <input type="hidden" name="new_subdivision" class="subdivision-hidden">
                <input type="hidden" name="new_warehouse" class="warehouse-hidden">
                
                <div class="col-auto">
                  <select class="form-select form-select-sm district-select" required>
                    <option value="">District</option>
                  </select>
                </div>
                <div class="col-auto">
                  <select class="form-select form-select-sm division-select" required disabled>
                    <option value="">Division</option>
                  </select>
                </div>
                <div class="col-auto">
                  <select class="form-select form-select-sm warehouse-select" required disabled>
                    <option value="">Warehouse</option>
                  </select>
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-sm btn-outline-success">Reassign</button>
                </div>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Verification Sections -->
  
<!-- Verify Field Officers -->
<div class="card mt-5 p-4">
  <h5 class="mb-3">Verify Field Officers</h5>
  <?php if ($unverified_fo->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-warning">
        <tr>
          <th>Officer ID</th>
          <th>Full Name</th>
          <th>Division</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $unverified_fo->fetch_assoc()): ?>
          <tr>
            <td><?= $row['FOID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['division'] ?></td>
            <td>
              <form method="POST" action="verify_officer.php" style="display:inline;">
                <input type="hidden" name="officer_id" value="<?= $row['FOID'] ?>">
                <input type="hidden" name="role" value="fo">
                <button type="submit" class="btn btn-sm btn-success">Verify</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted">No pending Field Officers found.</p>
  <?php endif; ?>
</div>

<!-- Verify Warehouse Officers -->
<div class="card mt-4 p-4">
  <h5 class="mb-3">Verify Warehouse Officers</h5>
  <?php if ($unverified_wo->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-warning">
        <tr>
          <th>Officer ID</th>
          <th>Full Name</th>
          <th>Division</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $unverified_wo->fetch_assoc()): ?>
          <tr>
            <td><?= $row['WOID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['division'] ?></td>
            <td>
              <form method="POST" action="verify_officer.php" style="display:inline;">
                <input type="hidden" name="officer_id" value="<?= $row['WOID'] ?>">
                <input type="hidden" name="role" value="wo">
                <button type="submit" class="btn btn-sm btn-success">Verify</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted">No pending Warehouse Officers found.</p>
  <?php endif; ?>
</div>


<script>
const locationData = {
  "Galle": {
    "Divisions": {
      "Galle Town": {
        "SubDivisions": ["Galle Fort", "Magalle", "Kaluwella"],
        "Warehouses": ["Warehouse G1", "Warehouse G2"]
      },
      "Baddegama": {
        "SubDivisions": ["Yakkalamulla", "Kandana", "Udugama"],
        "Warehouses": ["Warehouse B1"]
      },
      "Balapitiya": {
        "SubDivisions": ["Ahungalla", "Kosgoda", "Balapitiya Town"],
        "Warehouses": ["Warehouse BA1", "Warehouse BA2"]
      },
      "Akuressa": {
        "SubDivisions": ["Pitabeddara", "Kamburupitiya", "Thihagoda"],
        "Warehouses": ["Warehouse AK1"]
      }
    }
  },
  "Matara": {
    "Divisions": {
      "Matara Town": {
        "SubDivisions": ["Walgama", "Nupe", "Pamburana"],
        "Warehouses": ["Warehouse M1", "Warehouse M2"]
      },
      "Weligama": {
        "SubDivisions": ["Mirissa", "Weligama City", "Midigama"],
        "Warehouses": ["Warehouse W1"]
      },
      "Akuressa": {
        "SubDivisions": ["Pitabeddara", "Deiyandara", "Pasgoda"],
        "Warehouses": ["Warehouse A1"]
      }
    }
  },
  "Hambantota": {
    "Divisions": {
      "Hambantota Town": {
        "SubDivisions": ["Mirijjawila", "Godawaya", "Magam Ruhunupura"],
        "Warehouses": ["Warehouse H1"]
      },
      "Tangalle": {
        "SubDivisions": ["Netolpitiya", "Tangalle Center", "Kalametiya"],
        "Warehouses": ["Warehouse T1", "Warehouse T2"]
      },
      "Ambalantota": {
        "SubDivisions": ["Nonagama", "Ambalantota Town", "Ranna"],
        "Warehouses": ["Warehouse AM1"]
      }
    }
  }
};

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('form').forEach((form) => {
    const districtSelect = form.querySelector('.district-select');
    const divisionSelect = form.querySelector('.division-select');
    const subdivisionSelect = form.querySelector('.subdivision-select');
    const warehouseSelect = form.querySelector('.warehouse-select');

    const divisionHidden = form.querySelector('.division-hidden');
    const subdivisionHidden = form.querySelector('.subdivision-hidden');
    const warehouseHidden = form.querySelector('.warehouse-hidden');

    // Populate districts
    Object.keys(locationData).forEach(district => {
      districtSelect.add(new Option(district, district));
    });

    districtSelect.addEventListener('change', function () {
      const divisions = locationData[this.value]?.Divisions || {};
      divisionSelect.innerHTML = '<option value="">Division</option>';
      if (subdivisionSelect) {
        subdivisionSelect.innerHTML = '<option value="">SubDivision</option>';
        subdivisionSelect.disabled = true;
      }
      if (warehouseSelect) {
        warehouseSelect.innerHTML = '<option value="">Warehouse</option>';
        warehouseSelect.disabled = true;
      }
      divisionSelect.disabled = false;

      for (const division in divisions) {
        divisionSelect.add(new Option(division, division));
      }

      divisionHidden.value = '';
      if (subdivisionHidden) subdivisionHidden.value = '';
      if (warehouseHidden) warehouseHidden.value = '';
    });

    divisionSelect.addEventListener('change', function () {
      const district = districtSelect.value;
      const division = this.value;
      const divisionData = locationData[district]?.Divisions?.[division];

      if (subdivisionSelect && divisionData?.SubDivisions) {
        subdivisionSelect.innerHTML = '<option value="">SubDivision</option>';
        subdivisionSelect.disabled = false;
        divisionData.SubDivisions.forEach(sd => {
          subdivisionSelect.add(new Option(sd, sd));
        });
      }

      if (warehouseSelect && divisionData?.Warehouses) {
        warehouseSelect.innerHTML = '<option value="">Warehouse</option>';
        warehouseSelect.disabled = false;
        divisionData.Warehouses.forEach(wh => {
          warehouseSelect.add(new Option(wh, wh));
        });
      }

      divisionHidden.value = division;
      if (subdivisionHidden) subdivisionHidden.value = '';
      if (warehouseHidden) warehouseHidden.value = '';
    });

    if (subdivisionSelect) {
      subdivisionSelect.addEventListener('change', function () {
        subdivisionHidden.value = this.value;
      });
    }

    if (warehouseSelect) {
      warehouseSelect.addEventListener('change', function () {
        warehouseHidden.value = this.value;
      });
    }
  });
});

</script>
</body>
</html>
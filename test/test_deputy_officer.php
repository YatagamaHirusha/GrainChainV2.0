<?php
session_start();
include('database.php');
$doid = $_SESSION['DOID'] ?? 204; // Deputy Officer ID from login


// Get assigned Warehouse Officers
$wo_query = "SELECT U.full_name, WO.WOID, WO.assign_division FROM WarehouseOfficer WO JOIN USER U ON U.UID = WO.WOID WHERE DOID = $doid";
$wo_result = $con->query($wo_query);

// Get assigned Field Officers
$fo_query = "SELECT U.full_name, FO.assign_division, FO.FOID FROM FieldOfficer FO JOIN USER U ON U.UID = FO.FOID JOIN DeputyOfficer DO ON DO.DOID = FO.DOID WHERE DO.DOID = $doid";
$fo_result = $con->query($fo_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
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
  <div class="dashboard-container">
    <?php
    // Get summary data
    $total_wo = $con->query("SELECT COUNT(*) AS count FROM WarehouseOfficer WHERE DOID = $doid")->fetch_assoc()['count'];
    $total_fo = $con->query("SELECT COUNT(*) AS count FROM FieldOfficer WHERE DOID = $doid")->fetch_assoc()['count'];
    $total_current_stock = $con->query("
      SELECT SUM(current_stock_KG) AS total 
      FROM Warehouse 
      WHERE WID IN (SELECT DISTINCT WID FROM WarehouseOfficer WHERE DOID = $doid)
    ")->fetch_assoc()['total'] ?? 0;

    $total_farmers = $con->query("
      SELECT 
          COUNT(DISTINCT f.FID) AS total_farmers
      FROM 
          deputyofficer d
      JOIN 
          fieldofficer fo ON d.DOID = fo.DOID
      JOIN  
          land l ON fo.FOID = l.FOID
      JOIN 
          farmer f ON l.FID = f.FID
      WHERE d.DOID = $doid
    ")->fetch_assoc()['total_farmers'];
    ?>

    <h3 class="mb-4">Deputy Officer Dashboard</h3>
    <div class="row mb-4">
  <div class="col-md-3">
    <div class="card text-white bg-success shadow">
      <div class="card-body">
        <h6 class="card-title">Warehouse Officers</h6>
        <h4><?= $total_wo ?></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-primary shadow">
      <div class="card-body">
        <h6 class="card-title">Field Officers</h6>
        <h4><?= $total_fo ?></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning shadow">
      <div class="card-body">
        <h6 class="card-title">Total Current Stock (kg)</h6>
        <h4><?= $total_current_stock ?></h4>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-info shadow">
      <div class="card-body">
        <h6 class="card-title">Farmers in Divisions</h6>
        <h4><?= $total_farmers ?></h4>
      </div>
    </div>
  </div>
</div>


    <!-- Warehouse Officers Section -->
    <div class="card mb-4 p-4">
      <h5>Warehouse Officers</h5>
      <table class="table table-bordered table-hover">
        <thead class="table-success">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Division</th>
            <th>Reassign</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $wo_result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['WOID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['assign_division'] ?></td>
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

    <!-- Field Officers Section -->
    <div class="card p-4">
      <h5>Field Officers</h5>
      <table class="table table-bordered table-hover">
        <thead class="table-success">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Division</th>
            <th>Reassign</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $fo_result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['FOID'] ?></td>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['assign_division'] ?></td>
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
  </div>
  <div class="card mt-5 p-4">

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

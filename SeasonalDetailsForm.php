<!-- seasonal_details_form.php -->
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasonal Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Add Seasonal Details</h2>
    <form action="insert_seasonal_details.php" method="POST">
        <div class="mb-3">
            <label for="fid" class="form-label">Farmer ID (FID)</label>
            <input type="number" name="fid" id="fid" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="lid" class="form-label">Land ID (LID)</label>
            <input type="number" name="lid" id="lid" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="season" class="form-label">Season Name</label>
            <select name="season" class="form-select" required>
                <option value="Yala">Yala</option>
                <option value="Maha">Maha</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" id="year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="paddy_type" class="form-label">Paddy Type</label>
            <input type="text" name="paddy_type" id="paddy_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="plant_date" class="form-label">Plant Date</label>
            <input type="date" name="plant_date" id="plant_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="expected_harvest" class="form-label">Expected Harvest (KG)</label>
            <input type="number" step="0.01" name="expected_harvest" id="expected_harvest" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="harvest_date" class="form-label">Harvest Date</label>
            <input type="date" name="harvest_date" id="harvest_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Ongoing">Ongoing</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Seasonal Details</button>
    </form>
</div>
</body>
</html>

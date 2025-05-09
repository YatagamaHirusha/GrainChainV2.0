<?php
include 'database.php';
header('Content-Type: application/json');

$land_reg_no = $_GET['land_reg_no'];

$land_sql = "SELECT * FROM Land WHERE land_reg_no = '$land_reg_no'";
$land_result = mysqli_query($con, $land_sql);
$land = mysqli_fetch_assoc($land_result);

$verified = (bool) $land['is_verify'];
$land_id = $land['LID'];

$season_sql = "SELECT * FROM SeasonalDetails WHERE LID = '$land_id' ORDER BY SDID DESC LIMIT 1";
$season_result = mysqli_query($con, $season_sql);
$season = mysqli_fetch_assoc($season_result);

$season_id = $season['SDID'] ?? null;

$html = "<div class='mb-3'><h5 class='text-primary'>Land Details</h5>";
$html .= "<p><strong>Location:</strong> {$land['location']}</p>";
$html .= "<p><strong>Division:</strong> {$land['division']}</p>";
$html .= "<p><strong>Land Type:</strong> {$land['land_type']}</p>";
$html .= "<p><strong>Land Size:</strong> {$land['land_size_in_acres']} acres</p></div>";

if ($season) {
    $html .= "<div><h5 class='text-primary'>Latest Seasonal Details</h5>";
    $html .= "<p><strong>Season:</strong> {$season['season_name']}</p>";
    $html .= "<p><strong>Plant Date:</strong> {$season['plant_date']}</p></div>";
} else {
    $html .= "<p class='text-warning'>No seasonal details found for this land.</p>";
}

if (!$verified) {
    $html .= "<p class='text-danger'>⚠️ This land is not verified.</p>";
}

echo json_encode([
    'html' => $html,
    'verified' => $verified,
    'land_id' => $land_id,
    'season_id' => $season_id
]);

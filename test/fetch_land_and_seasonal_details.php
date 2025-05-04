<?php
include 'database.php';

if (isset($_GET['land_reg_no'])) {
    $land_reg_no = $_GET['land_reg_no'];

    // Fetch land details
    $land_sql = "SELECT * FROM Land WHERE land_reg_no = '$land_reg_no'";
    $land_result = mysqli_query($con, $land_sql);
    $land = mysqli_fetch_assoc($land_result);

    $is_land_verified = $land['is_verify'];

    if($is_land_verified){
        // Fetch latest seasonal detail for the selected land
        $season_sql = "SELECT * FROM SeasonalDetails SD JOIN Land L ON L.LID = SD.LID WHERE land_reg_no = '$land_reg_no' ORDER BY SDID DESC LIMIT 1";
        $season_result = mysqli_query($con, $season_sql);
        $season = mysqli_fetch_assoc($season_result);

        if ($land) {
            echo "<div class='mb-3'><h5 class='text-primary'>Land Details</h5>";
            echo "<p><strong>Location:</strong> {$land['location']}</p>";
            echo "<p><strong>Division:</strong> {$land['division']}</p>";
            echo "<p><strong>Land Type:</strong> {$land['land_type']}</p>";
            echo "<p><strong>Land Size:</strong> {$land['land_size_in_acres']} acres</p></div>";
        }
        if ($season) {
            echo "<div><h5 class='text-primary'>Latest Seasonal Details</h5>";
            echo "<p><strong>Season:</strong> {$season['season_name']}</p>";
            echo "<p><strong>Plant Date:</strong> {$season['plant_date']}</p>";
            echo "<p><strong>Plant Date:</strong> {$season['plant_date']}</p></div>";
        } else {
            echo "<p class='text-warning'>No seasonal details found for this land.</p>";
        }
        echo "<input type='hidden' id='land-verified' value='1'>";
    }
    else{
        echo "<p class='text-danger' id='land-verification-msg'>⚠️ This land is not verified. Application submission is disabled.</p>";
        echo "<input type='hidden' id='land-verified' value='0'>";        
    }


}
?>

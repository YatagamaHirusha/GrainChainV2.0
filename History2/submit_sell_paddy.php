<?php
include('database.php');
$woid = $_SESSION['UID'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyer = $con->real_escape_string($_POST['buyer_name']);
    $type = $con->real_escape_string($_POST['paddy_type']);
    $qty = floatval($_POST['paddy_quantity']);
    $price = floatval($_POST['price_per_KG']);
    $note = $con->real_escape_string($_POST['note']) ?? null;

    $sql = "INSERT INTO SellPaddy (WOID, buyer_name, paddy_type, paddy_quantity, price_per_KG, sell_date, note)
            VALUES (?, ?, ?, ?, ?, CURDATE(), ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("issdds", $woid, $buyer, $type, $qty, $price, $note);

    $sql_deduct = "UPDATE Warehouse W JOIN WarehouseOfficer WO ON WO.WID = W.WID SET W.current_stock_KG = current_stock_KG - ? WHERE WO.WOID = ?";
    $st = $con->prepare($sql_deduct);
    $st->bind_param("di", $qty, $woid);

    if ($stmt->execute() && $st->execute()) {
        header("Location: WarehouseOfficerDashboard.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>

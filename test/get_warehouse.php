<?php
// warehouses.php

session_start();
require 'database.php';

// 2. Query warehouse names
$sql = "SELECT WID, warehouse_name FROM Warehouse";
$result = $conn->query($sql);

// 3. Print <option> for each warehouse
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row["warehouse_id"] . '">' . $row["warehouse_name"] . '</option>';
    }
} else {
    echo '<option>No warehouses found</option>';
}

// 4. Close connection
$conn->close();
?>

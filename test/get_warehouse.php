<?php
// warehouses.php

// 1. Connect to database
$conn = new mysqli("localhost", "your_username", "your_password", "your_database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Query warehouse names
$sql = "SELECT warehouse_id, warehouse_name FROM warehouses";
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

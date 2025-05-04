<?php
session_start();
include('database.php'); // DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username and Password are required!'); window.location.href='Login.html';</script>";
        exit;
    }
    
    $stmt = $con->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) { 
            $_SESSION['UID'] = $user['UID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            switch ($user['role']) {
                case 'Farmer':
                    header("Location: FarmerDashboard.php");
                    break;
                case 'Field Officer':
                    header("Location: FieldOfficerDashboard.php");
                    break;
                case 'Warehouse Officer':
                    header("Location: WarehouseOfficerDashboard.php");
                    break;
                case 'Deputy Officer':
                    header("Location: DeputyOfficerDashboard.php");
                    break;
                default:
                    echo "<script>alert('Unknown user.'); window.location.href='Login.html';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='Login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='Login.html';</script>";
    }

    $stmt->close();
    $con->close();
}
?>

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

    // Prepared statement for security
    $stmt = $con->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) { // You should hash this later
            $_SESSION['user_id'] = $user['UID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            switch ($user['role']) {
                case 'Farmer':
                    header("Location: farmerdashboard.php");
                    break;
                case 'Field Officer':
                    header("Location: fieldofficerdashboard.php");
                    break;
                case 'Warehouse Officer':
                    header("Location: warehouseofficerdashboard.php");
                    break;
                case 'Deputy Officer':
                    header("Location: deputyofficerdashboard.php");
                    break;
                default:
                    echo "<script>alert('Unknown role.'); window.location.href='Login.html';</script>";
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

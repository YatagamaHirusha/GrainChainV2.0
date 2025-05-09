<?php
include 'database.php';
if(isset($_POST['btnSubmit'])) {

    $name = $_POST['txtName'];
    $email = $_POST['txtEmail'];
    $message = $_POST['txtMessage'];

    $message = mysqli_real_escape_string($con,$message);
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

    
    if (mysqli_query($con, $sql)) {
        $msg =  "Message submitted successfully!";
    } else {
        $msg = "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
    header("Location: Index.html");  
    exit;
}
?>

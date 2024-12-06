<?php
// Include database connection
include 'dbconfig.php';

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT);
    $role = 'Patient'; // Assign default role as 'patient'

    // Check if email already exists
    $checkEmailQuery = "SELECT UserID FROM user WHERE Email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if ($emailResult && mysqli_num_rows($emailResult) > 0) {
        echo "<script>alert('Email already exists. Please try logging in.'); window.location.href='index.php';</script>";
    } else {
        // Insert user into database with role
        $insertQuery = "INSERT INTO user (UserName, Email, Password, Role) VALUES ('$name', '$email', '$password', '$role')";
        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>alert('Signup successful! Please log in.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Signup failed. Please try again later.'); window.location.href='index.php';</script>";
        }
    }
}
?>

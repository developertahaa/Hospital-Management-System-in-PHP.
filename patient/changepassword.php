<?php
include '../dbconfig.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'New password and confirm password do not match!',
            }).then(function() {
                window.location.href = 'settings.php';
            });
        </script>";
        exit;
    }

    $query = "SELECT Password FROM user WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();

    if (password_verify($old_password, $user_data['Password'])) {
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE user SET Password = ? WHERE UserID = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $hashed_new_password, $user_id);

        if ($update_stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Your password has been updated successfully!',
                }).then(function() {
                    window.location.href = 'settings.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an issue updating your password. Please try again.',
                }).then(function() {
                    window.location.href = 'settings.php';
                });
            </script>";
        }
        $update_stmt->close();
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Incorrect Password',
                text: 'The old password you entered is incorrect.',
            }).then(function() {
                window.location.href = 'settings.php';
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

</head>
<body>
    
</body>
</html>

<?php
// Include database connection and session start
include '../dbconfig.php';
session_start();

// Check if user is logged in, else redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #6c5ce7, #74b9ff);
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            margin: 15px 0;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            transform: translateX(10px);
            color: #dfe6e9;
        }
        .sidebar a .icon {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            min-height: 100vh;
        }
        .no-reports {
            text-align: center;
            margin-top: 50px;
            font-size: 1.5rem;
            color: #555;
        }
        .no-reports i {
            font-size: 3rem;
            color: #f39c12;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h2 class="text-center my-4">Reports</h2>

        <div class="no-reports">
            <i class="fas fa-file-alt"></i>
            <p>No reports available right now.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

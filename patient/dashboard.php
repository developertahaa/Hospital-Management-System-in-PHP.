<?php
// Start session to ensure user is logged in
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Greet user with their name from session
$user_email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: white !important;
        }

        .main-content {
            margin-left: 270px;
            padding: 30px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .option-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .option-card i {
            font-size: 30px;
            color: #6c5ce7;
            margin-bottom: 10px;
        }

        .option-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <p class="greeting">Welcome, <?php echo $user_email; ?>!</p>
        <div class="options">
            <div class="option-card">
                <i class="fas fa-calendar-check"></i>
                <h3>Appointments</h3>
            </div>
            <div class="option-card">
                <i class="fas fa-user-injured"></i>
                <h3>Patients</h3>
            </div>
            <div class="option-card">
                <i class="fas fa-user-md"></i>
                <h3>Doctors</h3>
            </div>
            <div class="option-card">
                <i class="fas fa-file-alt"></i>
                <h3>Reports</h3>
            </div>
            <div class="option-card">
                <i class="fas fa-cog"></i>
                <h3>Settings</h3>
            </div>
            <div class="option-card">
                <i class="fas fa-sign-out-alt"></i>
                <h3>Logout</h3>
            </div>
        </div>
    </div>
</body>
</html>

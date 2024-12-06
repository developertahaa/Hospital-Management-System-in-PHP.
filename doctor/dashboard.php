<?php
// Start session
session_start();

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Doctor') {
    echo "<script>alert('Access denied!'); window.location.href='../index.php';</script>";
    exit;
}

// Fetch the doctor's name from the session (assuming it's set during login)
$doctorName = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Doctor';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    include 'sidebar.php';
    ?>
    <div class="container main-content">
        <h2 class="text-center">Welcome, Dr. <?php echo htmlspecialchars($doctorName); ?>!</h2>
        <p class="text-center">Your dashboard to manage patients and appointments.</p>

        <div class="row mt-4">
            <!-- Manage Patients -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Manage Patients</h5>
                        <p class="card-text">View, add, and manage your patient records.</p>
                        <a href="manage_patients.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Manage Appointments -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Appointments</h5>
                        <p class="card-text">Check and manage your appointments.</p>
                        <a href="manage_appointments.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>

            <!-- Profile Settings -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Profile Settings</h5>
                        <p class="card-text">Update your profile and account settings.</p>
                        <a href="profile_settings.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Logout -->
            <div class="col-md-4 offset-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Logout</h5>
                        <p class="card-text">Log out of your account securely.</p>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

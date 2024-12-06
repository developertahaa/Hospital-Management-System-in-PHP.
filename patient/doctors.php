<?php
// Include database connection and session start
include '../dbconfig.php';
session_start();

// Check if user is logged in, else redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Fetch doctors and their available time slots from the doctor table
$doctorQuery = "
    SELECT DoctorID, FirstName, LastName, Specialization, Timestart, Time_end
    FROM doctor
    ORDER BY LastName, FirstName";
$doctorResult = $conn->query($doctorQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
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
            background-color: #f8f9fa;
            color: #343a40;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            position: fixed;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            color: #ffffff;
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
        }
        .doctor-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .doctor-card h4 {
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }
        .doctor-card p {
            font-size: 1rem;
            margin: 5px 0;
        }
        .doctor-card .time-slot {
            font-weight: bold;
            color: #6c757d;
        }
        .doctor-card .specialization {
            color: #007bff;
        }
        .doctor-card i {
            margin-right: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            font-weight: bold;
            color: #495057;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="header">
            <h2>Doctors and Their Availability</h2>
            <p class="text-muted">Find information about doctors and their available time slots.</p>
        </div>

        <div class="container">
            <?php if ($doctorResult->num_rows > 0): ?>
                <?php while ($row = $doctorResult->fetch_assoc()): ?>
                    <div class="doctor-card">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-md fa-3x text-primary"></i>
                            <div class="ms-3">
                                <h4><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></h4>
                                <p class="specialization"><i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($row['Specialization']); ?></p>
                                <p class="time-slot">
                                    <i class="fas fa-clock"></i> Available: <?php echo htmlspecialchars($row['Timestart'] . ' - ' . $row['Time_end']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No doctors available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

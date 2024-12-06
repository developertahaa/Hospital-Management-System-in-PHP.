<?php
// Database Connection
include '../dbconfig.php';

// Fetch Total Patients
$totalPatientsQuery = "SELECT COUNT(*) AS total_patients FROM patient";
$totalPatientsResult = $conn->query($totalPatientsQuery);
$totalPatients = $totalPatientsResult->fetch_assoc()['total_patients'];

// Fetch Total Doctors
$totalDoctorsQuery = "SELECT COUNT(*) AS total_doctors FROM doctor";
$totalDoctorsResult = $conn->query($totalDoctorsQuery);
$totalDoctors = $totalDoctorsResult->fetch_assoc()['total_doctors'];

// Fetch Total Revenue from Appointments
$totalRevenueQuery = "SELECT SUM(fee) AS total_revenue FROM appointment";
$totalRevenueResult = $conn->query($totalRevenueQuery);
$totalRevenue = $totalRevenueResult->fetch_assoc()['total_revenue'];

// Fetch Daily Appointment Data for Graphs
$appointmentsQuery = "
    SELECT Date AS appointment_date, COUNT(*) AS total_appointments, SUM(fee) AS total_revenue 
    FROM appointment 
    GROUP BY Date";
$appointmentsResult = $conn->query($appointmentsQuery);

$dates = [];
$appointments = [];
$revenues = [];
while ($row = $appointmentsResult->fetch_assoc()) {
    $dates[] = $row['appointment_date'];
    $appointments[] = $row['total_appointments'];
    $revenues[] = $row['total_revenue'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #6c757d;
            background-color: #e9ecef;
            margin-top: 20px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Welcome, Admin!</h2>
    <p>Here is a quick overview of your platform's status.</p>

    <!-- Metrics Row -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-body text-center">
                    <i class="fas fa-users mb-2"></i>
                    <h5 class="card-title">Total Patients</h5>
                    <p class="card-text fs-4"><?= $totalPatients; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-body text-center">
                    <i class="fas fa-user-md mb-2"></i>
                    <h5 class="card-title">Total Doctors</h5>
                    <p class="card-text fs-4"><?= $totalDoctors; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign mb-2"></i>
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text fs-4">Rs. <?= number_format($totalRevenue, 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mt-4">
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Appointments Overview</h5>
                    <canvas id="appointmentsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue Overview</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2024 Admin Dashboard. All rights reserved.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const appointmentDates = <?= json_encode($dates); ?>;
    const totalAppointments = <?= json_encode($appointments); ?>;
    const totalRevenues = <?= json_encode($revenues); ?>;

    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: appointmentDates,
            datasets: [{
                label: 'Appointments',
                data: totalAppointments,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: appointmentDates,
            datasets: [{
                label: 'Revenue (Rs. )',
                data: totalRevenues,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
</body>
</html>

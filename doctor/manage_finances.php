<?php
// Start session
session_start();

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Doctor') {
    echo "<script>alert('Access denied!'); window.location.href='../index.php';</script>";
    exit;
}

// Include database connection
include '../dbconfig.php';

// Get the user_id from the session
$userID = $_SESSION['user_id'];

// Fetch doctorID from the doctor table using the userID from session
$query = "SELECT DoctorID FROM doctor WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $doctorRow = $result->fetch_assoc();
    $doctorID = $doctorRow['DoctorID'];
} else {
    echo "<script>alert('Doctor record not found!'); window.location.href='../index.php';</script>";
    exit;
}

// Fetch appointment data and fees for the doctor
$query = "SELECT DATE(a.Date) AS appointment_date, SUM(a.Fee) AS total_fees
          FROM appointment a
          WHERE a.DoctorID = ?
          GROUP BY DATE(a.Date)
          ORDER BY appointment_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctorID);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for chart
$dates = [];
$fees = [];
$totalAmount = 0; // Variable to hold total amount for the doctor
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['appointment_date'];
    $fees[] = (float)$row['total_fees'];
    $totalAmount += (float)$row['total_fees']; // Sum the total fees
}
?>

<!-- Include Bootstrap and Font Awesome for icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<!-- Include Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
 include 'sidebar.php';
 ?>
<div class="container main-content">
        <!-- Content Area -->
        <div class="col-md-9 p-5">
            <h3>Manage Finances</h3>
            
            <!-- Total Amount Section -->
            <div class="alert alert-info">
                <h4>Total Fees Earned: <strong>Rs. <?php echo number_format($totalAmount, 2); ?></strong></h4>
                <i class="fas fa-credit-card"></i> Total amount earned from appointments
            </div>

            <!-- Display financial summary -->
            <div class="financial-summary">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for chart rendering -->
<script>
    var ctx = document.getElementById('financeChart').getContext('2d');
    var financeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Total Fees',
                data: <?php echo json_encode($fees); ?>,
                backgroundColor: function(context) {
                    var value = context.dataset.data[context.dataIndex];
                    return value > 1000 ? '#4e73df' : '#1cc88a'; // Different color for large amounts
                },
                borderColor: '#ffffff',
                borderWidth: 2,
                borderRadius: 10, // Rounded corners for the bars
                hoverBackgroundColor: '#007bff',
                hoverBorderColor: '#0056b3'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Fees (Rs.)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#6e7dff'
                    },
                    grid: {
                        color: '#e0e0e0',
                        borderColor: '#e0e0e0',
                        borderWidth: 1
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#6e7dff'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Appointment Date',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#6e7dff'
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#6e7dff'
                    }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    bodyFont: {
                        size: 14
                    },
                    titleFont: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 10,
                    cornerRadius: 6
                },
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#6e7dff'
                    }
                }
            }
        }
    });
</script>


<style>
    /* Body and general layout */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fc;
    }

   

    .nav-link {
        color: #bbb;
        font-size: 18px;
        padding: 15px 20px;
        border-radius: 5px;
    }

    .nav-link:hover {
        color: white;
        background-color: #007bff;
    }

    .nav-link.active {
        color: white;
        background-color: #007bff;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        #sidebar {
            display: none;
        }

        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>

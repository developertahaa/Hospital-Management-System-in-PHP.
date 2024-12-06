<?php
include '../dbconfig.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch all appointments for the logged-in user
$appointment_query = "
    SELECT 
        a.AppointmentID, 
        a.Date,
        a.timeslot,
        a.DoctorID, 
        d.FirstName AS DoctorFirstName, 
        d.LastName AS DoctorLastName
    FROM 
        appointment a
    JOIN 
        doctor d ON a.DoctorID = d.DoctorID
    JOIN
        patient p ON a.PatientID = p.PatientID
    WHERE 
        p.UserID = ?
    ORDER BY 
        a.Date DESC
";
$appointment_stmt = $conn->prepare($appointment_query);
$appointment_stmt->bind_param("i", $user_id);
$appointment_stmt->execute();
$appointment_result = $appointment_stmt->get_result();

// Fetch all lab tests for the logged-in user
$labtest_query = "
    SELECT 
        bt.LabTestID, 
        lt.TestName, 
        bt.Date_Time, 
        bt.Result, 
        bt.Fees
    FROM 
        book_labtest bt
    JOIN 
        labtest lt ON bt.TestID = lt.TestID
    JOIN
        patient p ON bt.PatientID = p.PatientID
    WHERE 
        p.UserID = ?
    ORDER BY 
        bt.Date_Time DESC
";
$labtest_stmt = $conn->prepare($labtest_query);
$labtest_stmt->bind_param("i", $user_id);
$labtest_stmt->execute();
$labtest_result = $labtest_stmt->get_result();

// Close statements
$appointment_stmt->close();
$labtest_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment and Lab Test History</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>

        h2 {
            text-align: center;
            color: #4CAF50;
            font-size: 30px;
            font-weight: 700;
        }

        .table-container {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 16px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .section-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php
include 'sidebar.php';
?>
<div class="container main-content">
    <h2>Your Appointment and Lab Test History</h2>

    <!-- Appointments Section -->
    <div class="table-container">
        <div class="section-title">Your Appointments</div>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>TimeSlot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($appointment_result->num_rows > 0) {
                    while ($row = $appointment_result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['AppointmentID']) . "</td>
                                <td>" . htmlspecialchars($row['DoctorFirstName']) . " " . htmlspecialchars($row['DoctorLastName']) . "</td>
                                <td>" . htmlspecialchars($row['Date']) . "</td>
                                <td>" . htmlspecialchars($row['timeslot']) . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Lab Tests Section -->
    <div class="table-container">
        <div class="section-title">Your Lab Tests</div>
        <table>
    <thead>
        <tr>
            <th>Lab Test ID</th>
            <th>Test Name</th>
            <th>Date & Time</th>
            <th>Result</th>
            <th>Fees</th>
            <th>Generate Invoice</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($labtest_result->num_rows > 0) {
            while ($row = $labtest_result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['LabTestID']) . "</td>
                        <td>" . htmlspecialchars($row['TestName']) . "</td>
                        <td>" . htmlspecialchars($row['Date_Time']) . "</td>
                        <td>" . htmlspecialchars($row['Result']) . "</td>
                        <td>Rs. " . number_format($row['Fees'], 2) . "</td>
                        <td>
                            <a href='testreceipt.php?labTestID=" . htmlspecialchars($row['LabTestID']) . "' class='btn btn-success'>
                                Generate Invoice
                            </a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No lab tests found.</td></tr>";
        }
        ?>
    </tbody>
</table>

    </div>


    <div class="footer">
        <p>&copy; 2024 Your Healthcare System. All Rights Reserved.</p>
    </div>
</div>

</body>
</html>

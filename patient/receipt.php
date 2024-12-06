<?php
// Include necessary files and database connection
include '../dbconfig.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Fetch patient details and appointment information
$patientQuery = "SELECT p.PatientID, p.FirstName, p.LastName, a.timeslot, d.FirstName AS DoctorFirstName,a.Date, a.fee, d.LastName AS DoctorLastName, d.specialization 
                 FROM patient p
                 JOIN appointment a ON p.PatientID = a.PatientID
                 JOIN doctor d ON a.DoctorID = d.DoctorID
                 WHERE p.UserID = ?";
$stmt = $conn->prepare($patientQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the patient details and appointment info
    $row = $result->fetch_assoc();
    $patient_id = $row['PatientID'];
    $first_name = $row['FirstName'];
    $last_name = $row['LastName'];
    $appointment_time = $row['timeslot'];
    $appointment_date = $row['Date'];
    $fees = $row['fee'];
    $doctor_name = $row['DoctorFirstName'] . ' ' . $row['DoctorLastName'] . ' (Specialization: ' . $row['specialization'] . ')';
} else {
    // Handle case where no appointment found for user
    echo "No appointment found for the logged-in user.";
    exit;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            padding: 40px 0;
        }
        .receipt-container {
            background: linear-gradient(145deg, #ffffff, #e9ecef);
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 750px;
            margin: 0 auto;
            border-top: 6px solid #007bff;
        }
        .header-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-section img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .header-section h2 {
            font-weight: bold;
            color: #007bff;
        }
        .description {
            text-align: center;
            margin-bottom: 30px;
            font-style: italic;
            color: #555;
        }
        .stamp {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            font-size: 1.5rem;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .stamp-icon {
            font-size: 3rem;
            color: #28a745;
        }
        .details {
            margin-top: 20px;
            font-size: 1rem;
        }
        .details label {
            font-weight: bold;
            color: #555;
        }
        .details p {
            margin-bottom: 15px;
            line-height: 1.6;
            color: #333;
        }
        .precautions {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            border-radius: 5px;
        }
        .precautions h4 {
            color: #856404;
            font-weight: bold;
        }
        .precautions ul {
            list-style: none;
            padding-left: 0;
            color: #856404;
        }
        .precautions ul li {
            margin-bottom: 10px;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn-print {
            background-color: #007bff;
            color: #fff;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="receipt-container">
    
        <div class="stamp">
            <i class="fas fa-check-circle stamp-icon"></i>
            Appointment Confirmed
        </div>

        <div class="details">
            <p><label>Patient Name:</label> <?php echo $first_name . ' ' . $last_name; ?></p>
            <p><label>Appointment Date:</label> <?php echo $appointment_date; ?></p>
            <p><label>Appointment TimeSlot:</label> <?php echo $appointment_time; ?></p>
            <p><label>Doctor:</label> <?php echo $doctor_name; ?></p>
            <p><label>Fees:</label> $<?php echo $fees; ?></p>
        </div>

        <div class="precautions">
            <h4>Precautions for Your Appointment:</h4>
            <ul>
                <li>Arrive at least 15 minutes early to complete any formalities.</li>
                <li>Bring all relevant medical records and previous prescriptions.</li>
                <li>Wear a mask and follow clinic safety protocols.</li>
                <li>If you experience any symptoms, inform the clinic prior to arrival.</li>
            </ul>
        </div>

        <div class="button-container">
            <button class="btn btn-print" onclick="window.print()">Print Receipt</button>
        </div>

        <div class="footer-text">
            <p>&copy; <?php echo date("Y"); ?> Your Clinic Name. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


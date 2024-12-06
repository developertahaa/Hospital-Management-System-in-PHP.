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

// Fetch all appointments for the doctor
$query = "SELECT a.AppointmentID, a.PatientID, a.Date, a.timeslot, p.FirstName, p.LastName, p.MedicalHistory 
          FROM appointment a
          JOIN patient p  ON a.PatientID = p.PatientID 
          WHERE a.DoctorID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctorID);
$stmt->execute();
$result = $stmt->get_result();

include 'sidebar.php';
// Check if there are any appointments
if ($result->num_rows > 0) {
    echo "<div class='container main-content'>";
    echo "<h3>Manage Patients</h3>";
    echo "<div class='appointments-container'>";

    // Loop through the appointments and display patient details
    while ($row = $result->fetch_assoc()) {
        $appointmentID = $row['AppointmentID'];
        $patientID = $row['PatientID'];
        $firstName = $row['FirstName'];
        $lastName = $row['LastName'];
        $medicalHistory = $row['MedicalHistory'];
        $appointmentDate = $row['Date'];
        $appointmentTime = $row['timeslot'];

        echo "<div class='appointment-card'>";
        echo "<div class='appointment-info'>
                <p><i class='fas fa-user'></i> <strong>Patient:</strong> $firstName $lastName</p>
                <p><i class='fas fa-calendar-day'></i> <strong>Appointment Date:</strong> $appointmentDate</p>
                <p><i class='fas fa-clock'></i> <strong>Appointment Time:</strong> $appointmentTime</p>
              </div>";
        echo "<div class='patient-history'>
                <p><i class='fas fa-notes-medical'></i> <strong>Medical History:</strong></p>
                <p>$medicalHistory</p>
              </div>";
        echo "</div>";
    }

    echo "</div>";
} else {
    echo "<p>No appointments found for today.</p>";
}
?>

<!-- Include FontAwesome CDN -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<style>
    /* General styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        color: #333;
    }

    h3 {
        color: #444;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    /* Container for all appointments */
    .appointments-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    /* Individual appointment card */
    .appointment-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border-left: 4px solid #007bff;
    }

    /* Hover effect for appointment cards */
    .appointment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    /* Appointment info */
    .appointment-info p {
        margin: 10px 0;
        font-size: 16px;
        color: #333;
        line-height: 1.5;
    }

    .appointment-info p i {
        color: #007bff;
        margin-right: 8px;
    }

    .appointment-info p strong {
        color: #007bff;
    }

    /* Patient's medical history section */
    .patient-history {
        margin-top: 15px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        font-size: 14px;
        color: #6c757d;
    }

    .patient-history p {
        margin: 5px 0;
    }

    .patient-history p i {
        color: #28a745;
        margin-right: 8px;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .appointments-container {
            grid-template-columns: 1fr;
        }

        .appointment-card {
            padding: 15px;
        }
    }
</style>

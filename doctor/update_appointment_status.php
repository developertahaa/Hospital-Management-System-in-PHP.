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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentID = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update the status of the appointment
    $query = "UPDATE appointment SET Status = ? WHERE AppointmentID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $appointmentID);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment status updated successfully.'); window.location.href='manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Failed to update appointment status.'); window.location.href='manage_appointments.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
// Database connection
include '../dbconfig.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    // Retrieve form values
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $doctorID = intval($_POST['doctorID']);
    $date = $_POST['appointment_date']; // Date selected by user
    $time = $_POST['date_time']; // Time selected by user
    $medicalHistory = $_POST['medicalHistory'];
    $bedID = intval($_POST['bedID']); // Bed selected by user

    $fees = $_POST['fees']; // Bed selected by user

echo $date;
echo $fees;
echo $time;


    $insertPatientQuery = "INSERT INTO patient (FirstName, LastName, BedID, UserID, MedicalHistory) 
                           VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertPatientQuery);
    $stmt->bind_param("ssiis", $firstName, $lastName, $bedID, $user_id, $medicalHistory);

    if ($stmt->execute()) {
        // Get the patient_id generated
        $patientID = $stmt->insert_id;

        // Update bed availability to 0 (not available)
        $updateBedQuery = "UPDATE bed SET Availability = 0 WHERE BedID = ?";
        $bedStmt = $conn->prepare($updateBedQuery);
        $bedStmt->bind_param("i", $bedID);
        $bedStmt->execute();
        $bedStmt->close();

        // Insert into the appointments table with the selected time slot
        $insertAppointmentQuery = "INSERT INTO appointment (PatientID, DoctorID, Date, timeslot, Fee, Status) 
                                   VALUES (?, ?,?, ?, ?, 'Pending')";
        $appointmentStmt = $conn->prepare($insertAppointmentQuery);
        $appointmentStmt->bind_param("iisss", $patientID, $doctorID, $date, $time, $fees); // Store the selected time slot

        if ($appointmentStmt->execute()) {
            header("Location: appointments.php?success=true");
            $success = "Appointment booked successfully!";
        } else {
            $error = "Error booking appointment. Please try again.";
        }

        $appointmentStmt->close();
    } else {
        $error = "Error saving patient details. Please try again.";
    }

    $stmt->close();
}
?>

<?php
// Database Connection
include '../dbconfig.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
    $appointmentId = $_POST['appointment_id'];
    $newStatus = $_POST['status'];

    // Update Query
    $updateQuery = "UPDATE appointment SET status = ? WHERE AppointmentID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $newStatus, $appointmentId);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Status updated successfully!']);
    } else {
        echo json_encode(['message' => 'Failed to update status.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['message' => 'Invalid request.']);
    exit;
}
?>

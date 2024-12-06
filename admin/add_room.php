<?php
// Include database connection
include '../dbconfig.php';

// Check if the form is submitted for adding a room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle adding a new room
    if (isset($_POST['room_type_id'])) {
        $roomTypeId = $_POST['room_type_id'];
        $capacity = $_POST['capacity'];
        $charges = $_POST['charges'];
        $status = "Available";


        // Insert the new room into the database
        $addRoomQuery = "INSERT INTO room (Capacity, Charges, Status, room_type_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($addRoomQuery);
        $stmt->bind_param("iiis", $capacity, $charges, $status, $roomTypeId);

        if ($stmt->execute()) {
            echo "<script>alert('Room added successfully!'); window.location.href='manage_hospital.php';</script>";
        } else {
            echo "<script>alert('Error adding room. Please try again.'); window.location.href='manage_hospital.php';</script>";
        }
    }
    // Handle adding a bed to a specific room
    elseif (isset($_POST['room_id'])) {
        $roomId = $_POST['room_id'];
        $availability = 1;


        // Insert the new bed into the database
        $addBedQuery = "INSERT INTO bed (RoomID, Availability) VALUES (?,?)";
        $stmt = $conn->prepare($addBedQuery);
        $stmt->bind_param("ii", $roomId, $availability);

        if ($stmt->execute()) {
            echo "<script>alert('Bed added successfully!'); window.location.href='manage_hospital.php';</script>";
        } else {
            echo "<script>alert('Error adding bed. Please try again.'); window.location.href='manage_hospital.php';</script>";
        }
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='manage_hospital.php';</script>";
}

// Close the connection
$conn->close();
?>

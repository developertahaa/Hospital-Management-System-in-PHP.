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

// Fetch doctorID, Timestart, and Time_end from the doctor table
$query = "SELECT DoctorID, Timestart, Time_end FROM doctor WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $doctorRow = $result->fetch_assoc();
    $doctorID = $doctorRow['DoctorID'];
    $timeStart = $doctorRow['Timestart'];
    $timeEnd = $doctorRow['Time_end'];
} else {
    echo "<script>alert('Doctor record not found!'); window.location.href='../index.php';</script>";
    exit;
}

// Fetch all timeslot values and their corresponding dates from the appointment table for the doctor
$fetchedSlots = [];
$query = "SELECT timeslot, Date FROM appointment WHERE DoctorID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctorID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Store both timeslot and the corresponding date
    $fetchedSlots[$row['timeslot']] = $row['Date'];
}

// Function to generate 30-minute time slots
function generateSlotRanges($start, $end) {
    $slots = [];
    $startTime = strtotime($start);
    $endTime = strtotime($end);

    while ($startTime < $endTime) {
        $slotStart = date("H:i", $startTime); // 24-hour format
        $slotEnd = date("H:i", strtotime('+30 minutes', $startTime));
        $slots[] = "$slotStart - $slotEnd"; // Store the time slot in the array
        $startTime = strtotime('+30 minutes', $startTime);
    }
    return $slots;
}

// Generate 30-minute time slots between Timestart and Time_end
$generatedSlots = generateSlotRanges($timeStart, $timeEnd);

include 'sidebar.php';
// Echo all the generated slots
echo "<div class='container main-content'><h2>Manage Your Time</h2>";
foreach ($generatedSlots as $slot) {
    // Check if the slot is booked
    if (array_key_exists($slot, $fetchedSlots)) {
        // If booked, show as disabled and display the booked date
        $appointmentDate = $fetchedSlots[$slot];
        echo "<div class='timeslot-card disabled-slot'>
                <i class='fa-solid fa-calendar-times slot-icon'></i>
                <div>
                    <p><strong>$slot</strong></p>
                    <p>Your have an Appointment on: <span class='booked-date'>$appointmentDate</span></p>
                </div>
              </div>";
    } else {
        // If not booked, show as available
        echo "<div class='timeslot-card available-slot'>
                <i class='fa-solid fa-calendar-check slot-icon'></i>
                <div>
                    <p><strong>$slot</strong></p>
                    <p>Available</p>
                </div>
              </div>
              ";
    }
}
?>

<style>
    /* General Styling for Timeslot Cards */
    .timeslot-card {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        background-color: #ffffff;
        padding: 15px;
        margin: 12px 0;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        font-family: 'Arial', sans-serif;
    }

    .timeslot-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    /* Icon Styling */
    .slot-icon {
        font-size: 32px;
        margin-right: 20px;
        color: #6c757d;
        transition: color 0.3s ease;
    }

    /* Hover effect for available slots */
    .available-slot:hover .slot-icon {
        color: #28a745;
    }

    /* Content container inside each card */
    .timeslot-card div {
        flex-grow: 1;
    }

    /* Disabled Slot (Booked) Styling */
    .disabled-slot {
        background-color: #f8d7da;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.7;
        border: 1px solid #f5c6cb;
    }

    .disabled-slot .slot-icon {
        color: #dc3545;
    }

    .disabled-slot .booked-date {
        font-weight: bold;
        color: #dc3545;
    }

    .disabled-slot p {
        margin: 0;
    }

    /* Available Slot Styling */
    .available-slot {
        background-color: #e9f7e3;
        color: #3c763d;
        cursor: pointer;
        border: 1px solid #28a745;
    }

    .available-slot:hover {
        background-color: #d4edda;
    }

    .available-slot .slot-icon {
        color: #28a745;
    }

    .available-slot p {
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .timeslot-card {
            padding: 12px;
            margin: 8px 0;
        }

        .slot-icon {
            font-size: 28px;
        }
    }
</style>

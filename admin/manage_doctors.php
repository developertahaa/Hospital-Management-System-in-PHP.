<?php
// Database Connection
include '../dbconfig.php';

// Fetch all doctors and their availability times
$doctorsQuery = "
    SELECT 
        DoctorID AS doctor_id,
        FirstName AS doctor_name,
        Timestart AS available_time_start,
        time_end AS available_time_end
    FROM 
        doctor
    ORDER BY FirstName";
$doctorsResult = $conn->query($doctorsQuery);

$doctors = [];
while ($row = $doctorsResult->fetch_assoc()) {
    $doctorId = $row['doctor_id'];
    $doctors[$doctorId]['name'] = $row['doctor_name'];
    $doctors[$doctorId]['available_time_start'] = $row['available_time_start'];
    $doctors[$doctorId]['available_time_end'] = $row['available_time_end'];
}

// Fetch doctor's appointments for tomorrow
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$appointmentsQuery = "
    SELECT 
        DoctorID AS doctor_id,
        timeslot AS appointment_time
    FROM 
        appointment
    WHERE 
        Date = '$tomorrow'";
$appointmentsResult = $conn->query($appointmentsQuery);

$appointments = [];
while ($row = $appointmentsResult->fetch_assoc()) {
    $doctorId = $row['doctor_id'];
    $appointments[$doctorId][] = $row['appointment_time'];
}

// Prepare availability and appointment slots
$availability = [];
foreach ($doctors as $doctorId => $doctor) {
    $availability[$doctorId]['time_slots'] = [];

    // Generate all 30-minute intervals for tomorrow within the doctor's availability
    $start = new DateTime($doctor['available_time_start']);
    $end = new DateTime($doctor['available_time_end']);
    $interval = new DateInterval('PT30M'); // 30-minute intervals
    $timeSlots = new DatePeriod($start, $interval, $end);

    $occupiedSlots = isset($appointments[$doctorId]) ? $appointments[$doctorId] : [];

    foreach ($timeSlots as $slot) {
        $formattedSlot = $slot->format('H:i');

        // Check if this slot is occupied
        if (in_array($formattedSlot, $occupiedSlots)) {
            $availability[$doctorId]['time_slots'][] = ['slot' => $formattedSlot, 'status' => 'Booked'];
        } else {
            $availability[$doctorId]['time_slots'][] = ['slot' => $formattedSlot, 'status' => 'Available'];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .doctor-card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
        }
        .doctor-card h4 {
            margin-bottom: 10px;
            color: #333;
        }
        .time-slot {
            display: inline-block;
            border-radius: 20px;
            padding: 5px 10px;
            margin: 5px 5px 0 0;
            font-size: 14px;
        }
        .time-slot.booked {
            background: #f44336;
            color: white;
        }
        .time-slot.available {
            background: #2196F3;
            color: white;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container main-content">
    <h2>Manage Doctors</h2>

    <!-- Display doctor's availability and appointments for tomorrow -->
    <div class="row">
        <?php foreach ($availability as $doctorId => $available): ?>
            <div class="col-md-6">
                <div class="doctor-card">
                    <h4><?= htmlspecialchars($doctors[$doctorId]['name']) ?></h4>
                    <p><strong>Availability for Tomorrow:</strong></p>
                    <div>
                        <?php foreach ($available['time_slots'] as $slot): ?>
                            <span class="time-slot <?= strtolower($slot['status']) ?>"><?= htmlspecialchars($slot['slot']) ?> (<?= $slot['status'] ?>)</span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

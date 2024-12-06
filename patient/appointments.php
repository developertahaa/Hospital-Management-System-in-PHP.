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
$successswal = isset($_GET['success']) && $_GET['success'] === 'true';

// Fetch available doctors
$doctorsQuery = "SELECT DoctorID, CONCAT(FirstName, ' ', LastName, ' (', Specialization, ')') AS DoctorName, timestart, time_end, Fees FROM doctor";
$doctorsResult = $conn->query($doctorsQuery);

// Function to generate 30-minute time slots between start and end time
function generateTimeSlots($startTime, $endTime) {
    $timeSlots = [];
    $startTime = strtotime($startTime); // Convert to Unix timestamp
    $endTime = strtotime($endTime); // Convert to Unix timestamp

    while ($startTime < $endTime) {
        $nextTime = $startTime + 30 * 60; // Add 30 minutes in seconds
        // Format times to HH:mm (UTC timezone)
        $formattedStartTime = date("H:i", $startTime);
        $formattedEndTime = date("H:i", $nextTime);
        $timeSlots[] = $formattedStartTime . ' - ' . $formattedEndTime;
        $startTime = $nextTime;
    }
    return $timeSlots;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container main-content">
        <h2 class="text-center">Book an Appointment</h2>

        <?php if (!empty($successswal)) { ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Appointment Booked!',
                    text: 'Your appointment has been successfully booked.',
                    confirmButtonText: 'Get Your Receipt',
                    showCancelButton: true,
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'receipt.php';  // Adjust this to your receipt page
                    }
                });
            </script>
        <?php } ?>

        <form action="bookappointment.php" method="POST" class="mt-4">
            <!-- Step 1: Select Doctor -->
            <div class="mb-3">
                <label for="doctorID" class="form-label">Select Doctor</label>
                <select class="form-select" id="doctorID" name="doctorID" required onchange="fetchTimeSlots()">
                    <option value="" disabled selected>-- Select Doctor --</option>
                    <?php while ($row = $doctorsResult->fetch_assoc()) { ?>
                        <option value="<?php echo $row['DoctorID']; ?>" data-fees="<?php echo $row['Fees']; ?>"
                            data-start="<?php echo $row['timestart']; ?>"
                            data-end="<?php echo $row['time_end']; ?>">
                            
                            <?php echo $row['DoctorName']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Step 2: Select Date -->
            <div class="mb-3">
                <label for="appointment_date" class="form-label">Select Appointment Date</label>
                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
            </div>

            <!-- Step 3: Select Time Slot -->
            <div class="mb-3" id="timeSlotContainer">
                <label for="date_time" class="form-label">Select Time Slot</label>
                <select class="form-select" id="date_time" name="date_time" required disabled>
                    <option value="" disabled selected>-- Select Time Slot --</option>
                </select>
            </div>

            <!-- Step 4: Select Bed -->
            <div class="mb-3">
                <label for="bedID" class="form-label">Select Bed</label>
                <select class="form-select" id="bedID" name="bedID" required>
                    <option value="" disabled selected>-- Select Bed --</option>
                    <?php
                    $bedsQuery = "SELECT BedID FROM bed WHERE Availability = 1";
                    $bedsResult = $conn->query($bedsQuery);
                    while ($row = $bedsResult->fetch_assoc()) { ?>
                        <option value="<?php echo $row['BedID']; ?>"><?php echo $row['BedID']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Step 5: Patient Details -->
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>

            <div class="mb-3">
    <label for="fees" class="form-label">Appointment Fees</label>
    <input type="text" class="form-control" id="fees" name="fees" value="" required readonly>
</div>


            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>

            <div class="mb-3">
                <label for="medicalHistory" class="form-label">Medical History</label>
                <textarea class="form-control" id="medicalHistory" name="medicalHistory" rows="3" required></textarea>
            </div>

            <button type="submit" name="book_appointment" class="btn btn-primary">Book Appointment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function fetchTimeSlots() {
            var doctorSelect = document.getElementById('doctorID');
            var selectedDoctor = doctorSelect.options[doctorSelect.selectedIndex];

            // Fetch the start and end times from the selected doctor option
            var startTime = selectedDoctor.getAttribute('data-start');
            var endTime = selectedDoctor.getAttribute('data-end');

            // Generate time slots for the selected doctor
            var timeSlots = generateTimeSlots(startTime, endTime);

            // Enable the time slot dropdown and update options
            var timeSlotContainer = document.getElementById('timeSlotContainer');
            var dateTimeSelect = document.getElementById('date_time');
            var fees = selectedDoctor.getAttribute('data-fees');
            document.getElementById('fees').value = fees;

            dateTimeSelect.disabled = false;

            // Clear previous time slots
            dateTimeSelect.innerHTML = '<option value="" disabled selected>-- Select Time Slot --</option>';

            // Populate new time slots
            timeSlots.forEach(function (slot) {
                var option = document.createElement('option');
                option.value = slot;
                option.text = slot;
                dateTimeSelect.appendChild(option);
            });
        }

        // Function to generate 30-minute time slots between start and end times (ETC/UTC time zone)
        function generateTimeSlots(startTime, endTime) {
            var timeSlots = [];
            var currentTime = new Date('1970-01-01T' + startTime + 'Z').getTime();
            var endTimeMillis = new Date('1970-01-01T' + endTime + 'Z').getTime();

            while (currentTime < endTimeMillis) {
                var nextTime = currentTime + 30 * 60 * 1000; // 30 minutes in milliseconds
                var formattedCurrentTime = new Date(currentTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'UTC'  // Use UTC timezone
                });
                var formattedNextTime = new Date(nextTime).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'UTC'  // Use UTC timezone
                });
                timeSlots.push(formattedCurrentTime + ' - ' + formattedNextTime);
                currentTime = nextTime;
            }

            return timeSlots;
        }
    </script>
</body>
</html>

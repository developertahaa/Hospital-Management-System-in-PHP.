<?php
// Include necessary files and database connection
include '../dbconfig.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Fetch lab tests from the labtest table
$labTestQuery = "SELECT * FROM labtest";
$labTestResult = $conn->query($labTestQuery);

// Fetch available beds
$bedQuery = "SELECT * FROM bed WHERE availability = 1";
$bedResult = $conn->query($bedQuery);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include the separate file to handle lab test booking
    include 'booklabtest.php';
}
$successswal = isset($_GET['success']) && $_GET['success'] === 'true';
$labTestID = isset($_GET['labTestID']) ? $_GET['labTestID'] : '';  // Get labTestID from URL if available

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Lab Test Appointment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            min-height: 100vh;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="form-container">
            <h3>Book Lab Test Appointment</h3>
            <?php if (!empty($successswal)) { ?>
            <script>
                // SweetAlert for appointment success
                Swal.fire({
                    icon: 'success',
                    title: 'Test Booked!',
                    text: 'Your Lab test has been successfully booked.',
                    confirmButtonText: 'Get Your Receipt',
                    showCancelButton: true,
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to the receipt page and send labTestID
                        window.location.href = 'testreceipt.php?labTestID=<?php echo $labTestID; ?>';  // Redirect with labTestID
                    }
                });
            </script>
        <?php } ?>
            <form method="POST" action="booklabtest.php">
                <!-- Patient Name -->
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>

                <!-- Lab Test -->
                <div class="mb-3">
                    <label for="lab_test" class="form-label">Lab Test</label>
                    <select class="form-select" id="lab_test" name="lab_test" required>
                        <option value="">Select Lab Test</option>
                        <?php while ($row = $labTestResult->fetch_assoc()) { ?>
                            <option value="<?php echo $row['TestID']; ?>" data-fees="<?php echo $row['Fee']; ?>">
                                <?php echo $row['TestName']; ?> - Rs. <?php echo $row['Fee']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Fees -->
                <div class="mb-3">
                    <label for="fees" class="form-label">Fees</label>
                    <input type="text" class="form-control" id="fees" name="fees" readonly>
                </div>

                <!-- Date & Time -->
                <div class="mb-3">
                    <label for="date_time" class="form-label">Date and Time</label>
                    <input type="datetime-local" class="form-control" id="date_time" name="date_time" required>
                </div>

                <!-- Bed ID -->
                <div class="mb-3">
                    <label for="bed_id" class="form-label">Bed ID</label>
                    <select class="form-select" id="bed_id" name="bed_id" required>
                        <option value="">Select Bed</option>
                        <?php while ($bed = $bedResult->fetch_assoc()) { ?>
                            <option value="<?php echo $bed['BedID']; ?>">Bed No: <?php echo $bed['BedID']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- jQuery to dynamically update fees when lab test is selected -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lab_test').change(function() {
                var selectedOption = $(this).find(':selected');
                var fees = selectedOption.data('fees');
                $('#fees').val(fees);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include '../dbconfig.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Fetch form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$lab_test_id = $_POST['lab_test'];
$fees = $_POST['fees'];
$date_time = $_POST['date_time'];
$bed_id = $_POST['bed_id'];

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Check if the provided BedID exists in the bed table
$checkBedQuery = "SELECT * FROM bed WHERE BedID = ?";
$stmt1 = $conn->prepare($checkBedQuery);
$stmt1->bind_param("i", $bed_id);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows == 0) {
    // BedID does not exist in the bed table
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'The specified bed ID does not exist. Please choose a valid bed.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
    exit();
}

// Insert patient data into the patient table
$insertPatientQuery = "INSERT INTO patient (FirstName, LastName, UserID, BedID) VALUES (?, ?, ?,?)";
$stmt2 = $conn->prepare($insertPatientQuery);
$stmt2->bind_param("ssii", $first_name, $last_name, $user_id, $bed_id);

if ($stmt2->execute()) {
    $patient_id = $stmt2->insert_id; // Get the inserted patient_id

    // Insert lab test appointment into the book_labtest table
    $insertLabTestQuery = "INSERT INTO book_labtest (PatientID, TestID, Fees, Result, Date_Time, BedID) 
                           VALUES (?, ?, ?, 'Pending', ?, ?)";
    $stmt3 = $conn->prepare($insertLabTestQuery);
    $stmt3->bind_param("iiiss", $patient_id, $lab_test_id, $fees, $date_time, $bed_id);

    if ($stmt3->execute()) {
        // Get the inserted lab test ID
        $labTestID = $stmt3->insert_id;

        // Redirect with success and labTestID as query parameters
        header("Location: labtest.php?success=true&labTestID=" . $labTestID);
        exit();
    } else {
        // Handle error in booking lab test
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'There was an error booking the lab test. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
} else {
    // Handle error in inserting patient data
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'There was an error saving the patient information. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
}

$stmt1->close();
$stmt2->close();
$stmt3->close();
?>

<?php
// Include database connection
include '../dbconfig.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
    $email = $_POST['email'];
    $role = $_POST['role'];
    $createdAt = date("Y-m-d H:i:s"); // Current date and time

    // Only collect Doctor-specific data if the role is Doctor
    $firstname = $role === 'Doctor' ? $_POST['firstname'] : null;
    $lastname = $role === 'Doctor' ? $_POST['lastname'] : null;
    $fees = $role === 'Doctor' ? $_POST['fees'] : null;
    $time_start = $role === 'Doctor' ? $_POST['time_start'] : null;
    $time_end = $role === 'Doctor' ? $_POST['time_end'] : null;
    $specialization = $role === 'Doctor' ? $_POST['specialization'] : null;

    // Begin a transaction to prevent partial inserts
    $conn->begin_transaction();

    try {
        // Insert data into the users table
        $query = "INSERT INTO user (UserName, Password, Email, Role, CreatedAt) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $username, $password, $email, $role, $createdAt);
        $stmt->execute();

        // Get the last inserted UserID
        $userID = $stmt->insert_id;

        // Output the UserID and Role for debugging
        echo "UserID: $userID<br>";
        echo "Role: $role<br>";

        // If the role is Doctor, insert into the doctor table
        if ($role === 'Doctor') {
            $query_doctor = "INSERT INTO doctor (UserID, FirstName, LastName, Fees, Specialization, Timestart, Time_end) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_doctor = $conn->prepare($query_doctor);
            $stmt_doctor->bind_param("issssss", $userID, $firstname, $lastname, $fees, $specialization, $time_start, $time_end);
            $stmt_doctor->execute();

            // // Output Doctor-specific debug information
            // echo "Doctor record created successfully.<br>";
            // echo "Name: $firstname $lastname<br>";
            // echo "Specialization: $specialization<br>";
        } else {
            echo "No Doctor record needed.<br>";
        }

        // Commit the transaction
        $conn->commit();
        echo "<script>alert('Account Created successfully!'); window.location.href='manage_account.php';</script>";

    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $conn->rollback();
         echo "<script>alert('Failed to create account!'); window.location.href='manage_account.php';</script>";
    }

    // Close the statement and connection
    if (isset($stmt)) $stmt->close();
    if (isset($stmt_doctor)) $stmt_doctor->close();
    $conn->close();
}
?>

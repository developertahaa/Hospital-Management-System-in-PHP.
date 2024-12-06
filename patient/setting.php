<?php
include '../dbconfig.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user settings
$query = "SELECT UserName, Email FROM user WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
       body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            width: calc(100% - 260px);
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .form-group .danger {
            background-color: #e74c3c;
        }

        .form-group .danger:hover {
            background-color: #c0392b;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 40px;
        }

        .back-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #45a049;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-button {
            display: block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .modal-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<?php
include 'sidebar.php';
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h3>User Settings</h3>
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" value="<?= htmlspecialchars($user_data['UserName']) ?>" disabled>
        </div>
      
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" value="<?= htmlspecialchars($user_data['Email']) ?>" disabled>
        </div>
    </div>

    <!-- Change Password Button -->
    <div class="container">
        <h3>Change Password</h3>
        <button class="modal-button" id="changePasswordBtn">Change Password</button>
    </div>

    <!-- Delete Account Button -->
    <div class="container">
        <h3>Delete Account</h3>
        <button class="modal-button danger" id="deleteAccountBtn">Delete Account</button>
    </div>
  
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeChangePasswordModal">&times;</span>
        <h3>Change Password</h3>
        <!-- Form action set to change_password.php -->
        <form action="change    password.php" method="POST">
            <div class="form-group">
                <label for="old_password">Old Password</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit">Change Password</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeDeleteAccountModal">&times;</span>
        <h3>Confirm Account Deletion</h3>
        <form action="delete_account.php" method="POST">
            <div class="form-group">
                <label for="delete_password">Enter Password</label>
                <input type="password" id="delete_password" name="delete_password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="danger">Confirm Deletion</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal control for change password and delete account
    var changePasswordModal = document.getElementById('changePasswordModal');
    var deleteAccountModal = document.getElementById('deleteAccountModal');
    var changePasswordBtn = document.getElementById('changePasswordBtn');
    var deleteAccountBtn = document.getElementById('deleteAccountBtn');
    var closeChangePasswordModal = document.getElementById('closeChangePasswordModal');
    var closeDeleteAccountModal = document.getElementById('closeDeleteAccountModal');

    changePasswordBtn.onclick = function() {
        changePasswordModal.style.display = "block";
    }

    deleteAccountBtn.onclick = function() {
        deleteAccountModal.style.display = "block";
    }

    closeChangePasswordModal.onclick = function() {
        changePasswordModal.style.display = "none";
    }

    closeDeleteAccountModal.onclick = function() {
        deleteAccountModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target === changePasswordModal) {
            changePasswordModal.style.display = "none";
        } else if (event.target === deleteAccountModal) {
            deleteAccountModal.style.display = "none";
        }
    }
</script>

</body>
</html>

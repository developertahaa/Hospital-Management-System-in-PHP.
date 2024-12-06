<?php
// Include database connection
include '../dbconfig.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
    </style>
</head>
<body>

<?php
include 'sidebar.php'; // Include your sidebar here
?>

<div class="container main-content">
    <h2 class="mb-4">Create Account</h2>
    
    <!-- Account Creation Form -->
    <form action="create_account.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role" id="role" required>
                <option value="Patient">Patient</option>
                <option value="Doctor">Doctor</option>
            </select>
        </div>

        <div id="doctor-fields" style="display: none;">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname" id="firstname">
            </div>
            
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" id="lastname">
            </div>

            <div class="mb-3">
                <label for="fees" class="form-label">Fees</label>
                <input type="number" class="form-control" name="fees" id="fees">
            </div>

            <div class="mb-3">
                <label for="specialization" class="form-label">Specialization</label>
                <input type="text" class="form-control" name="specialization" id="specialization">
            </div>

            <div class="mb-3">
                <label for="time_start" class="form-label">Time Start</label>
                <input type="time" class="form-control" name="time_start" id="time_start">
            </div>

            <div class="mb-3">
                <label for="time_end" class="form-label">Time End</label>
                <input type="time" class="form-control" name="time_end" id="time_end">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Account</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('role').addEventListener('change', function() {
        if (this.value === 'Doctor') {
            document.getElementById('doctor-fields').style.display = 'block';
        } else {
            document.getElementById('doctor-fields').style.display = 'none';
        }
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #6c5ce7, #74b9ff);
            position: fixed;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            margin: 15px 0;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            transform: translateX(10px);
            color: #dfe6e9;
        }

        .sidebar a .icon {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 class="text-white text-center mb-4">Admin panel</h2>
            <a href="dashboard.php"><i class="fas fa-home icon"></i>Dashboard</a>
            <a href="patients.php"><i class="fas fa-calendar-check icon"></i>Manage Appointments</a>
            <a href="manage_doctors.php"><i class="fas fa-user-md icon"></i>Manage Doctors</a>
            <a href="manage_hospital.php"><i class="fas fa-hospital-alt icon"></i>Manage Hospital</a>
            <a href="invoices.php"><i class="fas fa-file-invoice icon"></i>Invoices</a>
            <a href="inventory.php"><i class="fas fa-cogs icon"></i>Inventory</a>
            <a href="manage_labtest.php"><i class="fas fa-flask icon"></i>Manage Lab Test</a>
            <a href="manage_account.php"><i class="fas fa-cog icon"></i>Account Management</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt icon"></i>Logout</a>
    </div>
</body>
</html>

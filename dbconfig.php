<?php
// Database configuration
$host = 'localhost';       // Hostname (usually 'localhost')
$username = 'root';        // Database username
$password = '';            // Database password
$dbname = 'hms';   // Database name

// Create a connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

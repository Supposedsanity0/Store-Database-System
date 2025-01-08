<?php
$host = "localhost"; // Replace with your host
$username = "root"; // Replace with your database username
$password = "Karim1245"; // Replace with your database password
$database = "sims"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
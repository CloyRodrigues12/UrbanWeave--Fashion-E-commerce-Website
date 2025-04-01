<?php
$servername = "localhost"; // Database server (usually localhost)
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "ecommerce_db";  // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
}
?>

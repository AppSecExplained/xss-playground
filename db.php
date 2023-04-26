<?php

$servername = "db";
$username = "xss_demo_user";
$password = "xss_demo_password";
$dbname = "xss_demo";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

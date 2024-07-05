<?php
$servername = "192.168.1.15";
$username = "root";
$password = "";
$dbname = "calendaroom";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

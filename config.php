<?php
$servername = "192.168.100.6";
$username = "root";
$password = "";
$dbname = "calendaroom";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

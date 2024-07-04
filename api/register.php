<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$phone = $data['phone'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$name = $data['name'];
$surname = $data['surname'];
$patronymic = $data['patronymic'];

$sql = "INSERT INTO user (name, surname, patronymic, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $surname, $patronymic, $email, $phone, $password);

$response = [];

if ($stmt->execute()) {
        echo json_encode(['message' => 'User registered successfully']);
        header('Location: http://calendaroom.ru/auth.php');
    } else {
        echo json_encode(['message' => 'User registration failed']);
    }
}

$conn->close();
?>

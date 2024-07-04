<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON: ' . json_last_error_msg()]);
        exit();
    }

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit();
    }

    $sql = "SELECT * FROM user WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['roleId'] = $user['roleId'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['surname'] = $user['surname'];
        $_SESSION['patronymic'] = $user['patronymic'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
$conn->close();
?>

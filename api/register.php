<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $phone = $data['phone'];
    $password = $data['password'];
    $repeatPassword = $data['repeatPassword'];
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $name = $data['name'];
    $surname = $data['surname'];
    $patronymic = $data['patronymic'];

    $response = [];

    if($password != $repeatPassword){
        echo json_encode(['message' => 'Пароли не совпадают']);
        exit();
    }

    // Проверка, существует ли пользователь с таким email
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['message' => 'Данный пользователь уже зарегистрирован в системе']);
        exit();
    }

    $stmt->close();

    // Начало транзакции
    $conn->begin_transaction();

    try {
        // Вставка пользователя
        $sql = "INSERT INTO user (name, surname, patronymic, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $surname, $patronymic, $email, $phone, $passwordHash);

        if (!$stmt->execute()) {
            throw new Exception("User registration failed: " . $stmt->error);
        }

        $stmt->close();

        // Вставка компании
        $sql = "INSERT INTO company (companyName) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if (!$stmt->execute()) {
            throw new Exception("Company registration failed: " . $stmt->error);
        }

        $stmt->close();

        // Получение ID компании
        $sql = "SELECT companyId FROM company WHERE companyName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $companyId = $row['companyId'];

        $stmt->close();

        // Получение ID пользователя
        $sql = "SELECT userId FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $userId = $row['userId'];

        $stmt->close();

        // Вставка в таблицу companyUser
        $sql = "INSERT INTO companyUser (companyId, userId) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $companyId, $userId);

        if (!$stmt->execute()) {
            throw new Exception("CompanyUser registration failed: " . $stmt->error);
        }

        $stmt->close();

        // Фиксация транзакции
        $conn->commit();
        echo json_encode(['message' => 'Пользователь зарегистрирован!']);
    } catch (Exception $e) {
        // Откат транзакции в случае ошибки
        $conn->rollback();
        echo json_encode(['message' => $e->getMessage()]);
    }
}

$conn->close();
?>

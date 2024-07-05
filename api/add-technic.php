<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include '../config.php';
require '../vendor/autoload.php';  // Подключение автозагрузчика Composer

use WebSocket\Client;

// Включаем отображение ошибок PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['status' => 'error', 'message' => 'Unknown error occurred'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON: ' . json_last_error_msg());
        }

        $companyId = $data['companyId'] ?? '';
        $type = $data['type'] ?? '';
        $name = $data['name'] ?? '';
        $weight = $data['weight'] ?? '';
        $capacity = $data['capacity'] ?? '';
        $engineModel = $data['engineModel'] ?? '';
        $enginePower = $data['enginePower'] ?? '';
        $frontBucketVolume = $data['frontBucketVolume'] ?? '';
        $backBucketVolume = $data['backBucketVolume'] ?? '';
        $diggingDepth = $data['diggingDepth'] ?? '';

        if (empty($type) || empty($name)) {
            throw new Exception('Type and name are required');
        }

        $sql = "INSERT INTO technic (typeTechnicId, name, mass, loadCapacity, engineModel, enginePower, frontBucketVolume, backBucketVolume, maxDiggingDepth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare statement failed: ' . $conn->error);
        }

        $stmt->bind_param("sssssssss", $type, $name, $weight, $capacity, $engineModel, $enginePower, $frontBucketVolume, $backBucketVolume, $diggingDepth);

        if ($stmt->execute()) {
            $newTechnicId = $conn->insert_id;

            $sql = "INSERT INTO companyTechnic (companyId, technicId) VALUES (?, ?)";
            $stmt2 = $conn->prepare($sql);
            if (!$stmt2) {
                throw new Exception('Prepare statement failed: ' . $conn->error);
            }

            $stmt2->bind_param("ii", $companyId, $newTechnicId);
            if (!$stmt2->execute()) {
                throw new Exception('Execute statement failed: ' . $stmt2->error);
            }

            // WebSocket notification
            $msg = json_encode([
                'type' => 'new_technic',
                'companyId' => $companyId,
                'technic' => [
                    'type' => $type,
                    'name' => $name,
                    'weight' => $weight,
                    'capacity' => $capacity,
                    'engineModel' => $engineModel,
                    'enginePower' => $enginePower,
                    'frontBucketVolume' => $frontBucketVolume,
                    'backBucketVolume' => $backBucketVolume,
                    'diggingDepth' => $diggingDepth
                ]
            ]);

            $webSocketConn = new Client("ws://localhost:8080");
            $webSocketConn->send($msg);
            $webSocketConn->close();

            $response = ['status' => 'success', 'message' => 'Technic added successfully'];
        } else {
            throw new Exception('Execute statement failed: ' . $stmt->error);
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Error: " . $e->getMessage());
}

echo json_encode($response);
$conn->close();
?>

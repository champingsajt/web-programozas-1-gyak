<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Read counters
        $stmt = $pdo->prepare("SELECT * FROM counters WHERE user_id = ? ORDER BY deadline ASC");
        $stmt->execute([$user_id]);
        $counters = $stmt->fetchAll();
        echo json_encode($counters);
        break;

    case 'POST':
        // Create counter
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['title'], $data['deadline'])) {
            $stmt = $pdo->prepare("INSERT INTO counters (user_id, title, deadline, status_message) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$user_id, $data['title'], $data['deadline'], $data['status_message'] ?? ''])) {
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Database error']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing data']);
        }
        break;

    case 'PUT':
        // Update counter
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'], $data['title'], $data['deadline'])) {
            // Ensure the counter belongs to the user
            $stmt = $pdo->prepare("UPDATE counters SET title = ?, deadline = ?, status_message = ? WHERE id = ? AND user_id = ?");
            if ($stmt->execute([$data['title'], $data['deadline'], $data['status_message'] ?? '', $data['id'], $user_id])) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Database error']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing data']);
        }
        break;

    case 'DELETE':
        // Delete counter
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $stmt = $pdo->prepare("DELETE FROM counters WHERE id = ? AND user_id = ?");
            if ($stmt->execute([$data['id'], $user_id])) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Database error']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing data']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>

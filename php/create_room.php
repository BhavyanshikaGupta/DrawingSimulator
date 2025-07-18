<?php
function generateRoomCode($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($characters), 0, $length);
}

$roomCode = generateRoomCode();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pic_the_word_game", "root", ""); // Update with your credentials
    $stmt = $pdo->prepare("INSERT INTO game_sessions (room_code, status) VALUES (:room_code, 'waiting')");
    $stmt->bindParam(':room_code', $roomCode);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'sessionId' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Could not create session.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
}
?>

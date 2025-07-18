<?php
$sessionId = $_POST['sessionId'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pic_the_word_game", "root", ""); // Update with your credentials

    // Update the game session status to "active"
    $stmt = $pdo->prepare("UPDATE game_sessions SET status = 'active' WHERE session_id = :session_id");
    $stmt->bindParam(':session_id', $sessionId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Game started']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to start the game']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
}
?>

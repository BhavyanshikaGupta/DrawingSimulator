<?php
$sessionId = $_GET['sessionId'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pic_the_word_game", "root", ""); // Update with your credentials

    // Check the game session status
    $stmt = $pdo->prepare("SELECT status FROM game_sessions WHERE session_id = :session_id");
    $stmt->bindParam(':session_id', $sessionId);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['status'] === 'active') {
        echo json_encode(['status' => 'active']);
    } else {
        echo json_encode(['status' => 'waiting']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
}
?>

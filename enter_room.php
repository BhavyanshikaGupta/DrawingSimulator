<?php
$roomCode = $_POST['roomCode'];
$username = $_POST['username'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pic_the_word_game", "root", ""); // Update with your credentials

    $stmt = $pdo->prepare("SELECT session_id FROM game_sessions WHERE room_code = :room_code AND status = 'waiting'");
    $stmt->bindParam(':room_code', $roomCode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $sessionId = $stmt->fetch(PDO::FETCH_ASSOC)['session_id'];
        echo json_encode(['status' => 'success', 'sessionId' => $sessionId]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Room does not exist or is no longer waiting for players.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
}
?>

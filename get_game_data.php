<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pic_the_word_game";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch a random word for the game session
function getRandomWord($conn) {
    $result = $conn->query("SELECT word FROM words ORDER BY RAND() LIMIT 1");
    return $result->num_rows > 0 ? $result->fetch_assoc()['word'] : null;
}

// Get leaderboard data for the current session
function getLeaderboard($conn) {
    $result = $conn->query("SELECT u.username, l.score FROM leaderboard l JOIN users u ON l.user_id = u.user_id ORDER BY l.score DESC");
    $leaderboard = [];
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
    return $leaderboard;
}

// Fetch data
$response = [
    "word" => getRandomWord($conn),
    "leaderboard" => getLeaderboard($conn)
];

echo json_encode($response);
$conn->close();
?>

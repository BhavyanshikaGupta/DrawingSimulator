<?php
$conn = new mysqli("localhost", "root", "", "pic_the_word_game");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT username, score FROM leaderboard ORDER BY score DESC");
$leaderboard = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($leaderboard);
$conn->close();
?>

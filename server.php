<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class GameServer implements MessageComponentInterface {
    protected $clients = [];
    protected $gameWord = null;
    protected $drawer = null;

    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = $conn;
        echo "New connection: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        
        switch ($data->type) {
            case "startGame":
                $this->gameWord = $this->getRandomWord();
                $this->drawer = $from;

                foreach ($this->clients as $client) {
                    $client->send(json_encode([
                        "type" => "setWord",
                        "word" => ($client === $from) ? $this->gameWord : str_repeat("_", strlen($this->gameWord)),
                        "isDrawer" => ($client === $from)
                    ]));
                }
                break;

            case "draw":
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        $client->send(json_encode(["type" => "draw", "data" => $data->data]));
                    }
                }
                break;

            case "guess":
                $isCorrect = ($data->message === $this->gameWord);
                foreach ($this->clients as $client) {
                    $client->send(json_encode([
                        "type" => "guess",
                        "username" => $data->username,
                        "message" => $data->message,
                        "correct" => $isCorrect
                    ]));
                }
                if ($isCorrect) {
                    $this->updateScore($data->userId);
                }
                break;

            case "endTurn":
                // End of turn logic
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        unset($this->clients[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function getRandomWord() {
        $conn = new mysqli("localhost", "root", "", "pic_the_word_game");
        $result = $conn->query("SELECT word FROM words ORDER BY RAND() LIMIT 1");
        $word = $result->fetch_assoc()['word'];
        $conn->close();
        return $word;
    }

    private function updateScore($userId) {
        $conn = new mysqli("localhost", "root", "", "pic_the_word_game");
        $conn->query("UPDATE leaderboard SET score = score + 10 WHERE user_id = $userId");
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new GameServer()
        )
    ),
    8080
);

$server->run();

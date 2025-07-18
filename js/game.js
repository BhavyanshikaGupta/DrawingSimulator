const ws = new WebSocket('ws://localhost:8080'); // Adjust to your WebSocket server

// Canvas setup
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
let isDrawing = false;
let lastX = 0;
let lastY = 0;

// Timer setup
let timeLeft = 60;
const timerDisplay = document.getElementById("timer");

function startTimer() {
    const timer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timer);
            ws.send(JSON.stringify({ type: "endTurn" }));
        } else {
            timerDisplay.innerText = timeLeft--;
        }
    }, 1000);
}

// WebSocket message handling
ws.onmessage = (event) => {
    const message = JSON.parse(event.data);

    switch (message.type) {
        case "setWord":
            document.getElementById("wordToDraw").innerText = message.isDrawer ? message.word : "_".repeat(message.word.length);
            if (message.isDrawer) startTimer();
            break;

        case "draw":
            drawFromData(message.data);
            break;

        case "guess":
            displayMessage(message.username, message.message);
            if (message.correct) {
                alert(`${message.username} guessed the word!`);
                updateLeaderboard();
            }
            break;
    }
};

// Drawing functions
canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
});

canvas.addEventListener('mousemove', (e) => {
    if (!isDrawing) return;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();

    ws.send(JSON.stringify({ type: 'draw', data: { x1: lastX, y1: lastY, x2: e.offsetX, y2: e.offsetY } }));
    [lastX, lastY] = [e.offsetX, e.offsetY];
});

canvas.addEventListener('mouseup', () => isDrawing = false);
canvas.addEventListener('mouseout', () => isDrawing = false);

function drawFromData(data) {
    ctx.beginPath();
    ctx.moveTo(data.x1, data.y1);
    ctx.lineTo(data.x2, data.y2);
    ctx.stroke();
}

// Chat functionality
document.getElementById("sendMessage").onclick = function() {
    const message = document.getElementById("chatMessage").value;
    ws.send(JSON.stringify({ type: "guess", username: "YourUsername", message: message }));
    document.getElementById("chatMessage").value = "";
};

function displayMessage(username, message) {
    const chatBox = document.getElementById("chatBox");
    chatBox.innerHTML += `<div><strong>${username}</strong>: ${message}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Leaderboard update
function updateLeaderboard() {
    fetch('get_leaderboard.php')
        .then(response => response.json())
        .then(data => {
            const leaderboardList = document.getElementById('leaderboardList');
            leaderboardList.innerHTML = data.map(entry => `<li>${entry.username}: ${entry.score}</li>`).join('');
        });
}

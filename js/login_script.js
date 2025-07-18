// js/login_script.js

function createRoom() {
    const username = document.getElementById('username').value;

    if (!username) {
        alert("Please enter your name to create a room.");
        return;
    }

    fetch('create_room.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ username })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Redirect to select_players.html with roomCode and sessionId
            window.location.href = `select_players.html?sessionId=${data.sessionId}&roomCode=${data.roomCode}&username=${username}`;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error creating room:', error));
}


function enterRoom() {
    document.getElementById('joinRoomSection').style.display = 'block';
}

function submitRoomCode() {
    const roomCode = document.getElementById('roomCode').value;
    const username = document.getElementById('username').value;

    if (!username) {
        alert("Please enter your name to join a room.");
        return;
    }

    if (!roomCode) {
        alert("Please enter a room code to join.");
        return;
    }

    fetch('enter_room.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ roomCode, username })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Redirect to loading.html with sessionId
            window.location.href = `loading.html?sessionId=${data.sessionId}&username=${username}`;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error joining room:', error));
}

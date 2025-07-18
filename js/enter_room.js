document.getElementById('enterRoomButton').addEventListener('click', () => {
    const roomCode = document.getElementById('roomCodeInput').value;
    const username = document.getElementById('usernameInput').value;

    fetch('enter_room.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ roomCode, username })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(`Joined room! Welcome, ${username}`);
            window.location.href = `game.html?sessionId=${data.sessionId}&username=${username}`;
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error joining room:', error));
});

document.getElementById('createRoomButton').addEventListener('click', () => {
    fetch('create_room.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(`Room created! Code: ${data.roomCode}`);
                window.location.href = `game.html?sessionId=${data.sessionId}`;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error creating room:', error));
});

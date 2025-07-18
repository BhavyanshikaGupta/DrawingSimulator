# 🎨 Draw & Guess - PHP-Based Drawing Simulator Game

A web-based drawing simulator game where one player draws a given word and the other guesses it via a live chat box. The game selects random words from a backend database using PHP and was originally designed for two players.

---

## 📌 Features

- 🎯 Random word selection from MySQL database
- ✏️ Drawing canvas for player 1
- 💬 Real-time guessing via chatbox for player 2
- 📈 Basic leaderboard support
- 🧠 Game logic written in PHP and JavaScript
- 🔒 Game rooms with unique IDs
- 🧪 Local 2-player play (multiplayer partially working)

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL (Word table)
- **Others:** AJAX for chat updates, session management

---

## 🎮 How It Works

1. Player 1 creates a game room and receives a word to draw.
2. Player 2 joins the room via room ID.
3. As player 1 draws, player 2 sends guesses using the chat box.
4. If the guess matches the word, the game ends successfully.

> ⚠️ Multiplayer over socket connections was not implemented due to lack of WebSocket experience. Game works for 2 players in a controlled environment.

---

## 📂 Project Structure

```bash
📁 tryproject/
├── create_game.html         # Game creation form
├── game.html                # Main drawing and guessing page
├── create_room.php          # Room creation logic
├── get_game_data.php        # Word and game state retrieval
├── check_game_status.php    # Check win/loss
├── get_leaderboard.php      # Leaderboard logic
├── enter_room.php           # Room entry logic
├── composer.json / .lock    # PHP package dependencies

## 📂 How it runs

1. Clone the repository
2. Run a local server (e.g., XAMPP, WAMP, or PHP built-in)
3. Set up a MySQL database and import the words table
4. Place files in the htdocs directory (if using XAMPP)
5. Open create_game.html in your browser
6. Test with two tabs or devices (Player 1 and Player 2)

## Future Improvements

1. WebSocket-based real-time multiplayer
2. AI auto-guess system (similar to QuickDraw)
3. Improved UI/UX for drawing
4. Full session management and security

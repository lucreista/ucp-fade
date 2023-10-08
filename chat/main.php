<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
</head>
<body>
    <div class="chat-container">
        <div class="messages">
            <!-- Chat messages go here -->
        </div>
        <div class="input-container">
            <input type="text" id="message-input" placeholder="Type your message...">
            <button id="send-button">Send</button>
        </div>
    </div>
    <script src="script.js"></script>
</body>
<script>
    var username = "<?= $_SESSION['username'];?>"
</script>
</html>

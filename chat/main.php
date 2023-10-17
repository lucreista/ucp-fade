<body>
    <div class="chat-container">
        <div class="messages">
            <!-- chat messages -->
        </div>
        <div class="input-container">
            <input type="text" id="message-input" placeholder="Type your message...">
            <button id="send-button">Send</button>
        </div>
    </div>
    <script src="script.js"></script>
<script>
    var username = "<?= $_SESSION['username'];?>"
</script>
</html>

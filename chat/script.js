// Import socket.io-client if using modules
// import { io } from 'socket.io-client';

// For development environment
const socket = io('http://localhost:12000/chat', {
  path: '/socket.io/',
  secure: false,
});

// For production environment
// const socket = io('https://ucp.fade.lv:8443/chat', {
//   path: '/socket.io/',
//   secure: true,
// });

socket.on('connect', () => {
  console.log('Connected to chat Server');

  // Emit events and listen for events specific to chat namespace
  socket.emit('new-user',sessionStorage.getItem('fadesession'))

  socket.on('receivedMessage', (data) => {
    console.log(data);
    const textimg = "https://minotar.net/avatar/" + data.username + ".png";
    const isSentBycurrentUser = data.username === username;
    const sender = isSentBycurrentUser ? 'sent' : 'received';
    appendMessage(data.message, sender, textimg, data.username, data.time);
  });
});

  socket.on('notification', (data) => {
  toastr[data.type](data.description, data.title);
});
  socket.on('messageHistory', (data) => {
    console.log(data);
    data.forEach((message) => {
      const textimg = "https://minotar.net/avatar/" + message.username + ".png";
      const isSentBycurrentUser = message.username === username;
      const sender = isSentBycurrentUser ? 'sent' : 'received';
      appendMessage(message.message, sender, textimg, message.username, message.time);
    })
  })
// js to handle messages

const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');
const messagesContainer = document.querySelector('.messages');

// whole message stuff
function appendMessage(message, sender, imageUrl = null, username = null, unix) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', sender);
    const date =  new Date(unix)
    const options = {
      day: '2-digit',
      month: '2-digit',
      hour: 'numeric',
      minute: 'numeric',
      hour12: true,
    }
    const formatDate = date.toLocaleString('en-US', options)

    const userInfoDiv = document.createElement('div');
    userInfoDiv.classList.add('user-info');
    userInfoDiv.style.display = 'flex'; 
    
    // create img
    if (imageUrl) {
        const userImage = document.createElement('img');
        userImage.src = imageUrl;
        userImage.alt = 'User Image';
        userImage.classList.add('user-image'); 
        userImage.style.width = '30px';
        userImage.style.height = '30px';
        userImage.style.borderRadius = '50%'; // Make the image rounded
        userImage.style.marginRight = '7px'; 

        userInfoDiv.appendChild(userImage);
    }
    
    // create a paragraph element for the username
    if (username) {
        const userNamePara = document.createElement('p');
        userNamePara.textContent = username + " - " + formatDate;
        userNamePara.classList.add('user-name'); // add a class for styling
        userInfoDiv.appendChild(userNamePara);
    }
    
    messageDiv.appendChild(userInfoDiv);
    
    // create a paragraph element for the message content
    const messageContent = document.createElement('p');
    messageContent.textContent = message;
    
    messageDiv.appendChild(messageContent);
    messagesContainer.appendChild(messageDiv);
    
    // scroll to the bottom of the chat
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // calculate the bubble width
    const textLength = message.length;
    const minWidth = 260; // minimum width 
    const maxWidth = 400; // maximum width 
    const calculatedWidth = Math.min(minWidth + textLength * 10, maxWidth);
    messageDiv.style.width = `${calculatedWidth}px`;
}

// event listener for sending a message
sendButton.addEventListener('click', () => {
    const message = messageInput.value;
    if (message.trim() !== '') {
        const sessionid = sessionStorage.getItem('fadesession');
        socket.emit('new-message', message, socket.id)
        messageInput.value = ''; // clear input
    }
});

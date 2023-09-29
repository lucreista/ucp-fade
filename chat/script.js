const socket = io('https://ucp.fade.lv:8443/chat', {
  path: '/socket.io/',
  secure: true, // Use secure WebSocket connection
});

socket.on('connect', () => {
  console.log('Connected to chat Server');

  // Emit events and listen for events specific to namespace2
  socket.emit('new-user',sessionStorage.getItem('fadesession'))

  socket.on('another-event-from-server', (data) => {
    console.log('Received another-event-from-server in script2.js:', data);
  });
});

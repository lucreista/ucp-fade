const socket = io('https://ucp.fade.lv:8443')
socket.on("connect", () => {
  socket.emit('new-user',sessionStorage.getItem('fadesession'))
  });
// countdown
const countdownEl = document.getElementById('countdown')
socket.on('countdown', data => {
    countdownEl.textContent = 'Starting in ' + data + 's';
    countdownEl.style.color = ''
  });
  // random number obtain
  socket.on('gameStarted', data => {
    countdownEl.textContent = data;
        var demo = new CountUp("countdown", 1, data,2);
        demo.start(updateCoins(data));

  });
  // connection errors
  socket.on('connect_error', () => {
    countdownEl.textContent = 'Connect error';
  });
  
  socket.on('connect_timeout', () => {
    countdownEl.textContent = 'Connect timeout';
  });

  socket.on('gameID-update', function(gameID) {
    var game = ''
    game = 'GameID: ' + gameID;
    document.getElementById('gameid').innerHTML = game

    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    socket.emit('lastBets', isMobile);

  })

  socket.on('lastBetsData', (data) => { 
    const container = document.getElementById('bet-container');
    var color;
    container.innerHTML = '';
    data.forEach((bet) => {
      if (bet.target < '1.90') {
        color = 'lost';
      } else {
        color = 'won';
      }
  
      const div = document.createElement('div');
      div.className = 'tooltip betsColumn'; // nosauties gribu =)
      div.id = 'lastBets';
      div.innerHTML = `
        <a class="${color}"target="_blank" href="https://ucp.fade.lv/slide/game/?id=${bet.gameID}">${bet.target}x</a>
        <div class="tooltip-content">
          <p>ID: ${bet.gameID}</p>
          <p>Target: ${bet.target}x</p>
        </div>
      `;
      container.appendChild(div);
    });
  });

  function clicking() {
    socket.emit('getOnlineUsers');
  }
  socket.on('receivedOnlineUsers', (data) => { // modal online useriii
    const element = document.getElementById('onlineUsers');

    element.innerHTML = '';
    data.forEach((name) => {
      const div = document.createElement('div');
      div.className = 'model-avatar';
      div.innerHTML = `
      <span class="modal-avatar">
      <img src="https://minotar.net/avatar/${name}" alt>
      <h2>${name}</h2>
      </span>
      </div>
      `;
      element.appendChild(div);
    });
  });
  
  socket.on('statsGame', (data) => {
    console.log('statsGame received');
    document.getElementById('avgTarget').innerHTML = data.avgTarget.toFixed(2) + "x";
    document.getElementById('maxTarget').innerHTML = data.maxTarget.toLocaleString() + "x";
    console.log(data.maxTarget);
  })
  socket.on('connectionStatus', (data) => { // updatojas katru reizi kad users jauns 
    const element = document.getElementById('connectionStatus');
    element.innerHTML = '<span class="dot"></span>Connected - <a id="myLink">' + data.userlist.length + ' users online</a>'; //uztaisit lai users online var clickot un paradas popup
  })
// betstatus confirm (pienemts/atteikts)
  socket.on('betStatus', (data) => {
    setTimeout (() => {
      toastr[data.type](data.description, data.title);
    }, 1700)
  })
  socket.on('activebets-update', function(activebets) {
    var betList = ''
    var totalamount = 0
    var nobets = '<h1>No bets to display.</h1>'
  if (window.innerWidth >= 768) {
    if (activebets.length === 0) {
      document.getElementById('betsText').innerHTML = 'Bets'
      document.getElementById('active-bets').innerHTML = nobets
      document.getElementById('total-amount').innerHTML = '$0.00 total'
    } else {
    for (var i = 0; i < activebets.length; i++) {
      var bet = activebets[i]
      totalamount += bet.amount
      var img = `<a id="betsLink"href="https://ucp.fade.lv/dashboard/profile/${bet.username}"><span class="modal-avatar"><img style="width: 20px;height: 20px;"src="https://minotar.net/avatar/${bet.username}" alt>`
      var betText = img + bet.username + ' - ' + bet.target.toFixed(2) + 'x - ' + bet.amount.toFixed(0) + ' <i class="bx bxs-coin-stack"></i></span></a>';
      betList += '<p>' + betText + '</p>';
    }
    document.getElementById('betsText').innerHTML = 'Bets'
    document.getElementById('active-bets').innerHTML = betList
    document.getElementById('total-amount').innerHTML = '$'+ totalamount.toFixed(2) +' total'
  }} else { // ja uz mobile device tad iznemt visus tos <div>
    document.getElementById('active-bets').innerHTML = ''
    document.getElementById('total-amount').innerHTML = ''
    document.getElementById('betsText').innerHTML = ''
  }})
// update balance
  socket.on('notification', (data) => {
    toastr[data.type](data.description, data.title);
    if (data.type == 'success') {
      var oldcoinsget = document.getElementById('coinsuh').innerHTML;
      var oldcoins = oldcoinsget.replaceAll(',','');
      var coindiffrence = oldcoins - data.amount
      var options = {
useEasing : true,
useGrouping : true,
separator : ',',
decimal : '.',
prefix : '',
suffix : ''
};
var demo = new CountUp("coinsuh", oldcoins, coindiffrence , 2, 1, options);
demo.start();

const button = document.getElementById('placeBet')
button.disabled = true;
button.innerHTML = 'Place Bet (Cooldown)'
setTimeout(() => {
  button.disabled = false;
  button.innerHTML = 'Place Bet'
}, 1000)
    }
  })
  
// countdown end

    const sendBetForm = document.getElementById('sendBet')

sendBetForm.addEventListener('submit', e => {
  e.preventDefault()
  const amountInput = document.getElementById('betAmount')
  const targetInput = document.getElementById('betTarget')

  const amount = amountInput.value
  const target = targetInput.value
  const token = sessionStorage.getItem('fadesession')

  socket.emit('place-bet', { amount, target, token }, socket.id)
})


function updateCoins(number) {
  var st = sessionStorage.getItem("fadesession");
  $.ajax({
    type: "post",
    url: "../functions.php",
    data: {
      'getCoinsUsername' :st
    },
    cache: false,
    success: function(data)
    {
      var oldcoinsget = document.getElementById('coinsuh').innerHTML;
      var oldcoins = oldcoinsget.replaceAll(',','');
var demo = new CountUp("coinsuh", oldcoins, data , 2, 1);
demo.start();
setTimeout(function() {
  if (number > 1.90) {
    countdownEl.style.color = 'green'
  } else {
    countdownEl.style.color = 'red'
  }
}, 1100);
}
    }
  )}


  
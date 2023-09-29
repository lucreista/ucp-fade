const https = require('https');
const fs = require('fs');
const { createPool } = require('mysql2/promise');
const crypto = require('crypto');
const { Socket } = require('socket.io');
require('dotenv').config();
const io = require('socket.io')(https.createServer({
  key: fs.readFileSync('/etc/letsencrypt/live/ucp.fade.lv/privkey.pem'),
  cert: fs.readFileSync('/etc/letsencrypt/live/ucp.fade.lv/fullchain.pem')
}).listen(8443), {
    cors: {
      origin: "*"
    }
  });
  const pool = createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    waitForConnections: true,
    connectionLimit: 50,
    queueLimit: 0
  });

  let users = {};
  let activeBets = {}
  var simplifiedBets = []
  let gameID;

  pool.getConnection()
  .then((connection) => {
    console.log('Connected to database');
    startCountdownLoop();
    connection.release();
  })
  .catch((error) => {
    console.error('Error connecting to database: ' + error.stack);
  });

  async function getLatestGameID() {
    try {
      const [rows] = await pool.query('SELECT MAX(id) as latest_game_id FROM games');
      return rows[0].latest_game_id || 0;
    } catch (error) {
      console.error('Error getting latest game ID: ', error);
      return 0;
    }
  }
  
  

// countdown start
function startCountdownLoop() {
    let countdownSeconds = 15;
    let isGameRunning = false;
  
    function slideGen() {
      // generate client seed
        const unix = Math.floor(Date.now() / 1000); // iegust tagadejo unix laiku sekundees
        const randomString = crypto.randomBytes(12).toString('hex'); // izdoma random hexadecimal string ar 24 garumu

        const client_seed = unix + randomString; // apvieno unix laiku + hexadecimalo string kopa

      //generate server hash
        const server_hash = crypto.randomBytes(32); // izdoma 32 bitu string
        const beta = 1.0000000001; // konstante kas tiks izmantots aprēkiņos
      
        const f = crypto.createHmac('sha256', client_seed.toString()) // izveido HMAC hash izmantojot client seed
                       .update(server_hash.toString()) // atjaunina hash ar server hash
                       .digest(); // iegust hashu bitos
        const a = parseInt(f.toString('hex'), 16) / Math.pow(2, 256); // parveido hash baitus par floating-point ciparu
        const d = beta / (1 - a); // veic aprekinus izmantojot beta konstanti un floating-point ciparu
        const e = Math.floor(102 * d) / 102;
        const x = Math.min(Math.max(e,1), 2**20); // ierobežo rezultatu noteiktaja diapazona
        
        const result = {
          number: x.toFixed(2),
          serverHash: server_hash.toString('hex'),
          clientSeed: client_seed
        };
        return result;
      }
      

    function broadcastCountdown() {
      io.emit('countdown', countdownSeconds);
    }
    function registerGame(slide) {
      const query = 'INSERT INTO `games` (`target`, `clientseed`, `serverhash`) VALUES (?, ?, ?)';
      pool.query(query, [slide.number, slide.clientSeed, slide.serverHash], (error, results, fields) => {
        if (error) {
          console.error(error);
          return;
        }
      });
    }
    
    function startGame() {
      isGameRunning = true;
      getLatestGameID().then((value) => {
        gameID = value + 2;
        console.log('gameID ir :' + gameID);
      });
      console.log(activeBets)
      countdownSeconds = 15;
      const slide = slideGen();
      console.log(slide);
      io.emit('gameStarted', slide.number)
      console.log(slide.number);
      registerGame(slide);
      resolveBets(activeBets,slide.number,gameID)
      // izdarit kko pirms activeBeti tiek clearoti
      activeBets = {}
      simplifiedBets = []
      setTimeout(() => {
        isGameRunning = false;
        countdownSeconds = 15;
        broadcastCountdown();
        io.emit('gameID-update', gameID);
        io.emit('activebets-update', simplifiedBets)
      }, 5000);
    }
  
    setInterval(() => {
      if (!isGameRunning) {
        countdownSeconds--;
        if (countdownSeconds === 0) {
          startGame();
        } else {
          broadcastCountdown();
        }
      }
    }, 1000);
  } 
  
// countdown end
//check winnings?
async function checkBalance(token) {
  const sql = `SELECT coins FROM users WHERE token = ?`;
  const params = [token];
  const [rows] = await pool.execute(sql, params);
  return rows[0].coins;
}
async function updateBalance(token, amount, operator) {
  const sql = `UPDATE users SET coins = coins ${operator} ? WHERE token = ?`;
  const params = [amount, token];
  await pool.execute(sql, params);
}
async function registerBet(userID, gameID, username, target, amount) {
  const query = 'INSERT INTO bets (uniqueid, gameID, user, target, amount, bet_time) VALUES (?, ?, ?, ?, ?, NOW())';
  const params = [userID, gameID, username, target !== undefined ? target : null, amount];
  await pool.execute(query, params);
}

function resolveBets(bets, serverTarget, gameID) {
  for (let userId in bets) {
    const bet = bets[userId].bet;
    const { target, amount, token, socket} = bet;
    const username = users[bet.socket];
    registerBet(userId, gameID, username, target !== undefined ? target : null, amount);
    if (target <= serverTarget) {
      console.log(`Win: ${username} won $${amount * target} (Target: ${target}x, Bet: ${amount})`);
      io.to(bet.socket).emit('betStatus', {
        title: 'Win',
        description: `Tu uzvarēji ${amount * target} <i class="bx bxs-coin-stack"></i>`,
        type: 'success'
      });

      // update balance
      updateBalance(token, amount * target - amount, '+').then(() => {
        console.log(`Updated balance for ${username}`);
      }).catch((error) => {
        throw error;
      });
    } else {
      // update balance
      updateBalance(token, amount, '-').then(() => {
        console.log(`Updated balance for ${username}`);
      }).catch((error) => {
        throw error;
      });

      // lost notification
      console.log(`Loss: ${username} lost $${amount} (Target: ${target}x, Bet: ${amount})`);
      io.to(bet.socket).emit('betStatus', {
        title: 'Lose',
        description: `Tu zaudēji ${amount}<i class="bx bxs-coin-stack"></i>`,
        type: 'error'
      });
    }
  }
}
async function latestBets(limit) {
  try {
    const conn = await pool.getConnection();
    const result = await conn.query('SELECT ID, target FROM games ORDER BY id DESC LIMIT ?', [limit]);
    conn.release();
    return result[0].map((row) => ({ gameID: row.ID, target: row.target }));
  } catch (err) {
    throw err;
  }
}

// connection
  io.on('connection', socket => {
    socket.on('new-user', token => {
      const query = `
      SELECT
        u.mcusername,
        g.avg_target,
        g.max_target,
        b.gameID,
        b.amount,
        b.user
      FROM users u
      INNER JOIN (
        SELECT AVG(target) AS avg_target, MAX(target) AS max_target
        FROM games
      ) g ON 1 = 1
      LEFT JOIN (
        SELECT gameID, amount, user
        FROM bets
        WHERE amount = (SELECT MAX(amount) FROM bets)
      ) b ON 1 = 1
      WHERE u.token = ?
      LIMIT 1
    `;
      pool.query(query, [token])
        .then(([results, fields]) => {
          const username = results[0].mcusername;
          users[socket.id] = username;
          console.log(username + ` connected (${socket.id})`);
          console.log(results);
          const latestData = {
            userlist: Object.values(users),
            connected: true
          };
          const statsGame = {
            avgTarget: results[0].avg_target,
            maxTarget: results[0].max_target,
            highestBet: results[0].amount,
            highestBetID: results[0].gameID,
            highestBetUser: results[0].user
          }
          io.emit('statsGame', statsGame);
          io.emit('gameID-update', gameID);
          io.emit('activebets-update', simplifiedBets)
          io.emit('connectionStatus', latestData);
        })
        .catch(error => {
          console.error('Error querying database: ' + error.stack);
        });
    });
    socket.on('disconnect', () => {
      const username = users[socket.id]
      console.log(`${username} disconnected (${socket.id})`)
      
      setTimeout(() => {
        delete users[socket.id]
        const latestData = {
          userlist: Object.values(users),
          connected: true 
        };
        console.log(`User with ID ${username} has been removed. (ID ${socket.id})`);
        io.emit('connectionStatus', latestData);
      }, 20000); // 20 seconds, pielikts jo ja uzreiz iznem useri kamer vinam ir aktivs bets, vina bets nesaglabajas.
    })

    socket.on('lastBets', async (platform) => {
      if (platform == true) {
        limit = 4
      } else limit = 12;
      const bets = await latestBets(limit);
      socket.emit('lastBetsData',bets);
    })
    socket.on('getOnlineUsers', () => {
      socket.emit('receivedOnlineUsers', Object.values(users));
    })

  // place bet
    socket.on('place-bet', async bet => {
        console.log(bet);
        bet.target = Math.round(Number(bet.target) * 100) / 100;
        bet.amount = Math.round(Number(bet.amount) * 100) / 100;
        bet.token = bet.token.replace(/[^a-zA-Z0-9]/g, '');
        bet.socket = socket.id;
        uniqueid = crypto.randomBytes(16).toString('hex');
        const token = bet.token;
        const existingBet = Object.values(activeBets).filter(b => b.bet.token === token);
        const totalValue = existingBet.reduce((total, betObj) => total + betObj.bet.amount, 0);
        if (existingBet.length < 5 && typeof bet.target === 'number' && typeof bet.amount === 'number') {
            const balance = await checkBalance(bet.token); // check balance
            if (bet.amount <= 0.09) {
              io.to(socket.id).emit('notification', {
                title: 'Invalid Bet',
                description: `Minimālais bets ir 0.10 <i class="bx bxs-coin-stack"></i>`,
                type: 'error'
              });
              return;
            }
            
          if (bet.target > 1.00) {
            if (bet.amount+totalValue <= balance) {
              // accept bet jo balance ir
                console.log('true');
                activeBets[uniqueid] = {bet}
                io.to(socket.id).emit('notification', {
                  title: 'Bet Successful',
                  description: `Target: ${bet.target}x , Bet: ${bet.amount}<i class="bx bxs-coin-stack"></i>`,
                  type: 'success',
                  amount: bet.amount
                });
                simplifiedBets.push({
                  username: users[socket.id],
                  amount: bet.amount,
                  target: bet.target
                });
                io.emit('activebets-update', simplifiedBets)
            } else {
              // deny likmi jo balances nav
              io.to(socket.id).emit('notification', {
                title: 'Nepietiek balance',
                description: `Tev ir tikai ${balance-totalValue} <i class="bx bxs-coin-stack"></i>!`,
                type: 'error'
              });
                console.log('false');
            } } else {  // kad bet target ir lower par 1.01

              io.to(socket.id).emit('notification', {
                title: 'Minimalais target',
                description: `Minimālais target ir 1.01x`,
                type: 'error'
              });

            }
        } else {
          // deny likmi jo jau uzlicis 5 betus
            console.log("user already has a bet")
            io.to(socket.id).emit('notification', {
              title: 'Bet Limit',
              description: `Katrs spēlētājs var likt 5 likmes`,
              type: 'error'
            });
        }
      });
  });
  
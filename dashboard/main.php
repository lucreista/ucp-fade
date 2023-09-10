<?php 
$variables = authmeUserData($_SESSION['username']);
$serverdata = serverdata();
?>
<div class="text"><?= $_SESSION['uuid'];?></div>
        <div class="container">
  <div class="column">
    <h2>Profili</h2>
    <p>Kopā mc.fade.lv ir izveidoti <?= $variables[3];?> profili.</p>
  </div>
  <div class="column">
    <h2>Pēdējais apmeklējums</h2>
    <p>Pēdējo reizi serverī biji <?= $variables[0];?></p>
  </div>
  <div class="column">
    <h2>Reģistrācijas laiks</h2>
    <p>Profils tika reģistrēts <?= $variables[1];?></p>
  </div>
  <div class="column">
    <h2>Pēdējā IP adrese</h2>
    <p class="hiddenIP" id="myText" onclick="toggleText()">Parādīt IP</p>
  </div>
</div>
<div class="divider"></div>
<div class="container">
<div class="column"><?php 
$data = serverData(); // Get the data
if (!isset($data['%server_online%'])) {
  echo "<h2>Server Status <span style='color: red'>Offline</span></h2>";
  echo "<p>Server is offline :(</p>";
} else {
  echo "<h2>Server Status: <span style='color: green'>Online</span></h2>";
$serverOnlineContent = isset($data['%server_online%']) ? $data['%server_online%'] : '';
$serverTps = isset($data['%server_tps_1%']) ? $data['%server_tps_1%'] : '';
$playerStatusContent = isset($data['%essentials_baltop_player_0%']) ? $data['%essentials_baltop_player_0%'] : '';
$balanceContent = isset($data['%essentials_baltop_balance_0%']) ? $data['%essentials_baltop_balance_0%'] : '';
$serverUptime = isset($data['%server_uptime%']) ? $data['%server_uptime%'] : '';
$servername = isset($data['%server_name%']) ? $data['%server_name%'] : '';

function tpsColor($tps) {
  switch ($tps) {
    case ($tps >= 18 && $tps <= 20):
      return 'green';
    case ($tps >= 15 && $tps < 18):
      return 'orange';
    case ($tps >= 1 && $tps < 15):
      return 'red';
  }
}
$tpsColor = tpsColor($tps);
echo "<p>Cnline Players: $serverOnlineContent</p>";
echo "<p>Server TPS: <span style='color: $tpsColor'>$serverTps</span></p>";
echo "<p>Server Uptime: $serverUptime </p>";
echo "<p>Richest player: $playerStatusContent($$balanceContent)</p>";
echo "<p>IP: $servername</p>";
}
?>
  </div>
</div>
<script>
    function toggleText() {
  var myText = document.getElementById("myText");
  if (myText.innerHTML === "Parādīt IP") {
    myText.innerHTML = "<?= $variables[2];?>";
  } else {
    myText.innerHTML = "Parādīt IP";
  }
}
</script>
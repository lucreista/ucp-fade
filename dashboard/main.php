<?php 
$variables = authmeUserData($_SESSION['username']);
?>
<div class="text">Spēlētaja info</div>
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
<div class="text">Servera info</div>
<div class="container">
<div class="column"><?php 
$data = serverData();
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
  if ($tps >= 18) {
    return 'green';
  } else if ($tps > 15) {
    return 'orange';
  } else if ($tps > 0) {
    return 'red';
  } else if ($tps == "*20.0") {
    return 'lime';
  }
  else return 'blue';
}
$tpsColor = tpsColor($serverTps);
echo "<p>Cnline Players: $serverOnlineContent</p>
<p>Server TPS: <span style='color: $tpsColor'>$serverTps</span></p>
<p>Server Uptime: $serverUptime </p>
<p>Richest player: $playerStatusContent($$balanceContent)</p>
<p>IP: $servername</p>";
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
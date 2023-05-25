<?php 
$variables = authmeUserData($_SESSION['username']);
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
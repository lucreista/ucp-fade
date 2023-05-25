<?php
include 'statsdb.php';
$result = $conn->query("SELECT
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Kills, '|Mobs_Killed.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS mobs_killed_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Kills, '|Animals_Killed.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS animals_killed_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Kills, '|Players_Killed.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS players_killed_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Kills, '|Players_Killed.killstreak:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS players_killstreak,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Deaths, '|Deaths_By_Mobs.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS deaths_by_mobs,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Deaths, '|Deaths_By_Suicide.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS deaths_by_suicide,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Deaths, '|Deaths_By_Players.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS deaths_by_players,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(World, '|Blocks_Broken.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS blocks_broken_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(World, '|Blocks_Placed.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS blocks_placed_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(World, '|Ores_Mined.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS ores_mined_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(World, '|Fishing_Successes.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS fishing_success_amount,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Movement, '|Distance_Travelled.sprinting:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS sprint_travel,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Movement, '|Distance_Travelled.walking:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS walk_travel,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Movement, '|Distance_Travelled.jumping:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS jump_travel,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Movement, '|Distance_Travelled.sneaking:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS sneak_travel,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Inventory, '|Items_Picked.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS items_picked,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Inventory, '|Items_Dropped.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS items_dropped,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Inventory, '|Items_Crafted.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS items_crafted,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Survival, '|Foods_Eaten.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS foods_eaten,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Damage, '|Players_Damaged.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS damage_players,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Damage, '|Mobs_Damaged.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS damage_mobs,
IFNULL(CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(Damage, '|Animals_Damaged.amount:', -1), '||', 1), '||', '') AS UNSIGNED), 0) AS damage_animals
FROM UltimateStatistics
WHERE uuid = '$_SESSION[uuid]'");

$row = $result->fetch_assoc();
//error_reporting(0);
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="text">Kills</div>
        <div class="container">
  <div class="column">
    <h2>Player Kills</h2>
    <p>Pašlaik tev ir <?= $row['players_killed_amount']; ?> kili</p>
  </div>
  <div class="column">
    <h2>Killstreak</h2>
    <p>Tavs augstākais player killstreak: <?= $row['players_killstreak'];  ?></p>
  </div>
  <div class="column">
  <?php
    $pveKills = $row['mobs_killed_amount'] + $row['animals_killed_amount'];
    ?>
    <h2>PvE Kill</h2>
    <p>Tu esi nositis <?= $row['mobs_killed_amount'];  ?> mobus</p>
  </div>
  <div class="column">
  <h2>Nāves</h2>
<?php
$totalDeaths = $row['deaths_by_mobs'] + $row['deaths_by_suicide'] + $row['deaths_by_players'];
?>
<script>
    $(document).ready(function() {
        $('#deaths').hover(function() {
            $(this).text('From mobs: <?= $row['deaths_by_mobs']; ?>, Suicide: <?= $row['deaths_by_suicide']; ?>, Players: <?= $row['deaths_by_players'];?>');
        }, function() {
            $(this).text('Tu nomiri: <?= $totalDeaths; ?>');
        });
    });
</script>
    <p id="deaths">Tu nomiri <?= $totalDeaths?> reizes</p>
  </div>
</div>
<div class="divider"></div>
<div class="text">World</div>
<div class="container">
  <div class="column">
    <h2>Salauzti bloki</h2>
    <p>Pašlaik tu esi salauzis <?= $row['blocks_broken_amount']; ?> bloku</p>
  </div>
  <div class="column">
    <h2>Uzlikti bloki</h2>
    <p>Pašlaik tu esi uzlicis <?= $row['blocks_placed_amount']; ?> blokus</p>
  </div>
  <div class="column">
    <h2>Izrakti ores</h2>
    <p>Kopā esi izracis <?= $row['ores_mined_amount']; ?> ores</p>
  </div>
  <div class="column">
  <h2>Zvejošana</h2>
    <p>Tu noķēri <?= $row['fishing_success_amount']; ?> zivis</p>
  </div>
</div>
<div class="divider"></div>

<div class="text">Travel</div>
<div class="container">
  <div class="column">
    <h2>Skrējējs</h2>
    <p>Distanci kuru esi noskrējis: <?= $row['sprint_travel']; ?> blokus</p>
  </div>
  <div class="column">
    <h2>Staigātājs</h2>
    <p>Distanci kuru esi nostaigājis: <?= $row['walk_travel']; ?> blokus</p>
  </div>
  <div class="column">
    <h2>Lekātājs</h2>
    <p>Distanci kuru esi nolēkājis: <?= $row['jump_travel']; ?> blokus</p>
  </div>
  <div class="column">
  <h2>Sneaking</h2>
  <p>Distanci kuru esi sneaking: <?= $row['sneak_travel']; ?> blokus</p>
  </div>
</div>

<div class="divider"></div>
<div class="text">Inventory</div>
<div class="container">
  <div class="column">
    <h2>Paceltie Itemi</h2>
    <p>Tu kopā esi pacēlis: <?= $row['items_picked']; ?> itemus</p>
  </div>
  <div class="column">
    <h2>Nomestie Itemi</h2>
    <p>Tu kopā esi nometis: <?= $row['items_dropped']; ?> itemus</p>
  </div>
  <div class="column">
    <h2>Krafteris</h2>
    <p>Tu kopā esi uzcraftojis: <?= $row['items_crafted']; ?> itemus</p>
  </div>
  <div class="column">
  <h2>Ēdieni</h2>
  <p>Tu kopā esi apēdis: <?= $row['foods_eaten']; ?> ēdienus</p>
  </div>
</div>


<div class="divider"></div>
<div class="text">Damage</div>
<div class="container">
  <div class="column">
    <h2>PvP damage</h2>
    <p>Spēlētājiem esi izdarījis <?= $row['damage_players']; ?> damage</p>
  </div>
  <div class="column">
    <?php // mob + animal damage saskaitits
    $pveDamage = $row['damage_mobs'] + $row['damage_animals'];
    ?>
    <script>
    $(document).ready(function() {
        $('#mobs').hover(function() {
            $(this).text('Mob damage: <?= $row['damage_mobs'];?>, Animals damage: <?= $row['damage_animals'];?>');
        }, function() {
            $(this).text('Mobiem/Animals esi izdarījis <?= $pveDamage ?> damage');
        });
    });
</script>
    <h2>PvE damage</h2>
    <p id="mobs">Mobiem/Animals esi izdarījis <?= $pveDamage ?> damage</p>
  </div>
  <div class="column">
    <h2>XXX</h2>
    <p>XXX</p>
  </div>
  <div class="column">
  <h2>XXX</h2>
  <p>XXX</p>
  </div>
</div>



<div class="divider"></div>
<div class="text">Damage</div>
<div class="container">
  <div class="column">
    <h2>PvP damage</h2>
    <p>Spēlētājiem esi izdarījis <?= $row['damage_players']; ?> damage</p>
  </div>
  <div class="column">
    <?php // mob + animal damage saskaitits
    $pveDamage = $row['damage_mobs'] + $row['damage_animals'];
    ?>
    <h2>PvE damage</h2>
    <p>Mobiem esi izdarījis <?= $pveDamage ?> damage</p>
  </div>
  <div class="column">
    <h2>XXX</h2>
    <p>XXX</p>
  </div>
  <div class="column">
  <h2>XXX</h2>
  <p>XXX</p>
  </div>
</div>
<div class="divider"></div>



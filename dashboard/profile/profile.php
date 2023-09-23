<?php $publicData = publicProfileData($_GET['username']);?>
<div class="search-container">
<div class="searchbox">
    <form id="searchForm">
    <i class="bx bx-search"></i>
        <input type="text" class="searchinput" name="txt" onmouseout="this.value = ''; this.blur();">
    </form>
</div>
</div>
<div class="text">Profile: <?= "$_GET[username]" ?></div>
  <div class="container profilegrid">
  <div class="column profilecolumn">
    <div class="bodyavatar">
    <img src="https://minotar.net/body/<?="$_GET[username]" ?>" alt="">
</div>
    <div class="profile text">
    <h2><?= "$_GET[username]" ?></h2>
    <p>Reģistrējies: <?= $publicData[1]; ?></p>
    <p>Pēdējo reizi serverī: <?= $publicData[0]; ?></p>
    <p>Monētas: <?= number_format($publicData[2], 2, '.', ',');; ?> <i class='bx bxs-coin-stack'> </i></p>
    <p>Custom Skin: <?= $publicData[4]; ?></p>
</div>
  </div>
</div>

<script>
    // Get a reference to the search form
    const searchForm = document.getElementById("searchForm");

    // Add an event listener to the form submission
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the user input from the search input field
        const userInput = searchForm.elements["txt"].value;

        // Construct the desired URL with the user's input
        const desiredURL = `${userInput}`;

        // Redirect the user to the desired URL
        window.location.href = desiredURL;
    });

    
</script>

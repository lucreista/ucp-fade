<head>
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/favicon.png" type="image/png">
</head>
<body id="modeColor">
<nav id="sidebar">
<script>
    var nav = document.getElementById("sidebar");
    var sidebarSet = sessionStorage.getItem("sidebarValue");
    if (sidebarSet == "sidebar") {
        nav.classList.add(sidebarSet);
    } else if (sidebarSet == "sidebar close") {
        nav.classList.add("sidebar", "close");
    } else {
        var sidebarSet = "sidebar close";
        nav.classList.add("sidebar", "close");
    }
    var modeSet = sessionStorage.getItem("modeColor");
    var mode = document.getElementById("modeColor");
    if (modeSet == "dark") {
        mode.classList.add(modeSet);
    } else if (modeSet == "") {
// nothing
    } else {
        mode.classList.add("dark");
    }
    </script>
        <header>
            <div class="image-text">
                <span class="image">
                    <a href="https://ucp.fade.lv/dashboard/profile/<?= "$_SESSION[username]" ?>">
                    <img src="https://minotar.net/avatar/<?="$_SESSION[username]" ?>" alt="">
                        </a>
                </span>

                <div class="text logo-text">
                    <span class="name"><?="$_SESSION[username]" ?></span>
                    <span class="ucprole">Coins <span id="coinsuh"><?= number_format(getCoins($_COOKIE["fadesession"]), 2, '.', ','); ?></span><i class='bx bxs-coin-stack'> </i></span>
                </div>
            </div>

          <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/dashboard">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Kontrolpanelis</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/dashboard/profile/<?=$_SESSION['username']?>">
                            <i class='bx bx-user icon' ></i>
                            <span class="text nav-text">Mans Profils</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/dashboard/skins">
                            <i class='bx bx-walk icon' ></i>
                            <span class="text nav-text">Pievienot skinu</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/dashboard/stats">
                            <i class='bx bx-stats icon' ></i>
                            <span class="text nav-text">Statistika</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a target="_blank" href="https://fade.lv/bans/history.php?uuid=<?= $_SESSION['uuid']; ?>">
                            <i class='bx bxs-shield-plus icon' ></i>
                            <span class="text nav-text">Parkāpumi</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a  href="/slide">
                            <i class='bx bx-dice-6 icon'></i>
                            <span class="text nav-text">Slide</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a  href="/chat">
                            <i class='bx bxs-chat icon' ></i>
                            <span class="text nav-text">Chat</span>
                        </a>
                    </li>
                    <?php 
                    $adminlvl = adminCheck();
                    if ($adminlvl > 0) {
                        echo '<li class="nav-link">
                        <a  href="/dashboard/admin">
                            <i class="bx bxl-sketch icon" ></i>
                            <span class="text nav-text">Admin</span>
                        </a>
                    </li>';
                    } else {
                        echo '';
                    }
                    ?>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="../../logout.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Iziet</span>
                    </a>
                </li>   
                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <script>
                        </script>
                    <span class="mode-text text">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
                
            </div>
        </div>

    </nav>
    <script>
        sessionStorage.setItem("fadesession",'<?= $_COOKIE['fadesession']; ?>');
    </script>
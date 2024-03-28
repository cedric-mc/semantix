<?php
$menu = !isset($menu) ? 0 : $menu;
if ($menu == 1) {
    $before = "../";
} else {
    $before = "";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="<?php echo $before; ?>style/menu.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3f3ecfc27b.js"></script>
    <title>Menu - Semonkey</title>
</head>

<body>

    <div class="hamburger">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    <nav class="hamburger-menu">
        <ul>
            <li><a href="<?php echo $before; ?>./">Accueil</a></li>
            <li><a href="<?php echo $before; ?>./profil/">Mon Profil</a></li>
            <li><a href="<?php echo $before; ?>./rules.php">Règles</a></li>
            <li><a href="<?php echo $before; ?>./classement/">Classement</a></li>
            <li><a href="<?php echo $before; ?>./amis/">Amis</a></li>
            <li><a href="<?php echo $before; ?>./traces.php">Traces</a></li>
            <li><a href="<?php echo $before; ?>./contact.php">Contact</a></li>
            <li><a href="<?php echo $before; ?>./connexion/script-logout.php">Se déconnecter</a></li>
        </ul>
    </nav>

    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="logo">
                <a href="#" class="nav-link">
                    <span class="link-text logo-text">Semonkey</span>
                    <svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="angle-double-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-angle-double-right fa-w-14 fa-5x">
                        <g class="fa-group">
                            <path fill="currentColor" d="M224 273L88.37 409a23.78 23.78 0 0 1-33.8 0L32 386.36a23.94 23.94 0 0 1 0-33.89l96.13-96.37L32 159.73a23.94 23.94 0 0 1 0-33.89l22.44-22.79a23.78 23.78 0 0 1 33.8 0L223.88 239a23.94 23.94 0 0 1 .1 34z" class="fa-secondary"></path>
                            <path fill="currentColor" d="M415.89 273L280.34 409a23.77 23.77 0 0 1-33.79 0L224 386.26a23.94 23.94 0 0 1 0-33.89L320.11 256l-96-96.47a23.94 23.94 0 0 1 0-33.89l22.52-22.59a23.77 23.77 0 0 1 33.79 0L416 239a24 24 0 0 1-.11 34z" class="fa-primary"></path>
                        </g>
                    </svg>
                </a>
            </li>

            <div class="scrollable-nav-items">
                <li class="nav-item">
                    <a href="<?php echo $before; ?>./" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12.71 2.29a1 1 0 0 0-1.42 0l-9 9a1 1 0 0 0 0 1.42A1 1 0 0 0 3 13h1v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7h1a1 1 0 0 0 1-1 1 1 0 0 0-.29-.71zM6 20v-9.59l6-6 6 6V20z"></path>
                        </svg>
                        <span class="link-text">Accueil</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $before; ?>./profil/" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path>
                        </svg>
                        <span class="link-text">Mon Profil</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $before; ?>./rules.php" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M21 3h-7a2.98 2.98 0 0 0-2 .78A2.98 2.98 0 0 0 10 3H3a1 1 0 0 0-1 1v15a1 1 0 0 0 1 1h5.758c.526 0 1.042.214 1.414.586l1.121 1.121c.009.009.021.012.03.021.086.079.182.149.294.196h.002a.996.996 0 0 0 .762 0h.002c.112-.047.208-.117.294-.196.009-.009.021-.012.03-.021l1.121-1.121A2.015 2.015 0 0 1 15.242 20H21a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM8.758 18H4V5h6c.552 0 1 .449 1 1v12.689A4.032 4.032 0 0 0 8.758 18zM20 18h-4.758c-.799 0-1.584.246-2.242.689V6c0-.551.448-1 1-1h6v13z"></path>
                        </svg>
                        <span class="link-text">Règles</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $before; ?>./classement/" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M21 4h-3V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v1H3a1 1 0 0 0-1 1v3c0 4.31 1.799 6.91 4.819 7.012A6.001 6.001 0 0 0 11 17.91V20H9v2h6v-2h-2v-2.09a6.01 6.01 0 0 0 4.181-2.898C20.201 14.91 22 12.31 22 8V5a1 1 0 0 0-1-1zM4 8V6h2v6.83C4.216 12.078 4 9.299 4 8zm8 8c-2.206 0-4-1.794-4-4V4h8v8c0 2.206-1.794 4-4 4zm6-3.17V6h2v2c0 1.299-.216 4.078-2 4.83z"></path>
                        </svg>
                        <span class="link-text">Classement</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $before; ?>./amis/" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M15 11h7v2h-7zm1 4h6v2h-6zm-2-8h8v2h-8zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2zm4-7c1.995 0 3.5-1.505 3.5-3.5S9.995 5 8 5 4.5 6.505 4.5 8.5 6.005 12 8 12z"></path>
                        </svg> <span class="link-text">Amis</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $before; ?>./traces.php" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path d="M416 0C352.3 0 256 32 256 32V160c48 0 76 16 104 32s56 32 104 32c56.4 0 176-16 176-96S512 0 416 0zM128 96c0 35.3 28.7 64 64 64h32V32H192c-35.3 0-64 28.7-64 64zM288 512c96 0 224-48 224-128s-119.6-96-176-96c-48 0-76 16-104 32s-56 32-104 32V480s96.3 32 160 32zM0 416c0 35.3 28.7 64 64 64H96V352H64c-35.3 0-64 28.7-64 64z" />
                        </svg> <span class="link-text">Traces</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $before; ?>./contact.php" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M224 16c-6.7 0-10.8-2.8-15.5-6.1C201.9 5.4 194 0 176 0c-30.5 0-52 43.7-66 89.4C62.7 98.1 32 112.2 32 128c0 14.3 25 27.1 64.6 35.9c-.4 4-.6 8-.6 12.1c0 17 3.3 33.2 9.3 48H45.4C38 224 32 230 32 237.4c0 1.7 .3 3.4 1 5l38.8 96.9C28.2 371.8 0 423.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-58.5-28.2-110.4-71.7-143L415 242.4c.6-1.6 1-3.3 1-5c0-7.4-6-13.4-13.4-13.4H342.7c6-14.8 9.3-31 9.3-48c0-4.1-.2-8.1-.6-12.1C391 155.1 416 142.3 416 128c0-15.8-30.7-29.9-78-38.6C324 43.7 302.5 0 272 0c-18 0-25.9 5.4-32.5 9.9c-4.8 3.3-8.8 6.1-15.5 6.1zm56 208H267.6c-16.5 0-31.1-10.6-36.3-26.2c-2.3-7-12.2-7-14.5 0c-5.2 15.6-19.9 26.2-36.3 26.2H168c-22.1 0-40-17.9-40-40V169.6c28.2 4.1 61 6.4 96 6.4s67.8-2.3 96-6.4V184c0 22.1-17.9 40-40 40zm-88 96l16 32L176 480 128 288l64 32zm128-32L272 480 240 352l16-32 64-32z" />
                        </svg> <span class="link-text">Contact</span>
                    </a>
                </li>
            </div>

            <li class="nav-item" id="themeButton">
                <a href="<?php echo $before; ?>./connexion/script-logout.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 21c4.411 0 8-3.589 8-8 0-3.35-2.072-6.221-5-7.411v2.223A6 6 0 0 1 18 13c0 3.309-2.691 6-6 6s-6-2.691-6-6a5.999 5.999 0 0 1 3-5.188V5.589C6.072 6.779 4 9.65 4 13c0 4.411 3.589 8 8 8z"></path>
                        <path d="M11 2h2v10h-2z"></path>
                    </svg> <span class="link-text">Se Déconnecter</span>
                </a>
            </li>
        </ul>
    </nav>

    <script>
        document.getElementById('themeButton').addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = '../connexion/script-logout.php'; // Redirigez vers la page de déconnexion
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.querySelector('.hamburger');
            const bar2 = document.querySelector('.bar2');
            const bar1 = document.querySelector('.bar1');
            const bar3 = document.querySelector('.bar3');
            const hamburger_menu = document.querySelector('.hamburger-menu');

            hamburger.addEventListener('click', function() {
                bar2.classList.toggle('cross1');
                bar1.classList.toggle('none');
                bar3.classList.toggle('cross2');
                hamburger_menu.classList.toggle('hamburger-menu-visible');
            });
        });
    </script>
</body>

</html>
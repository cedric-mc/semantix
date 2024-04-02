<?php
    include_once("class/User.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: connexion/');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="icon" href="favicon.ico"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="apple-touch-icon" href="logo192.png"/>
        <link rel="manifest" href="manifest.json"/>
        <title>React App</title>
    </head>

    <body>
        <noscript>You need to enable JavaScript to run this app.</noscript>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.1.0/react.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.1.0/react-dom.min.js"></script>
        <div id="root"></div>
        <script>
            let userData = {
                idUser: <?php echo $user->getIdUser(); ?>,
                pseudo: "<?php echo $user->getPseudo(); ?>"
            };
            console.log(userData);
            localStorage.setItem('userData', JSON.stringify(userData));
        </script>
    </body>
</html>

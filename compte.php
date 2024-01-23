<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('include/connexion.php');

include('include/redirection.php');
?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="style/style2.css">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script defer src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script defer src="bsTable.js"></script>
<head>
    <title> Mon compte</title>
    <link rel="icon" href="monkeyapp.png">

</head>
<main>
<div class="box">
    <h1> Mon compte </h1>
    <br>
    <br>
    <h2> Mes Informations </h2>
    <br>
        Pseudo : <?php echo htmlspecialchars($_SESSION['pseudo']); ?>
    <a href ="modif_user.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z"></path><path d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z"></path></svg>
    </a>
    <br>
    <br>

    <?php
    $stmt = $dbh->prepare("SELECT email FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $mail = $ligne->email;
    ?>
        Email : <?php echo $mail;?>
    <a href ="modif_mail.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z"></path><path d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z"></path></svg>
    </a>
    <br>
    <br>
    Modifier mot de passe :
    <a href="modif_password.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z"></path><path d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z"></path></svg>

    </a>
    <br>
    <br>
    <?php
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;


    $stmt = $dbh->prepare("SELECT admin FROM user WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_OBJ);

    if ($admin->admin == 1) {
        $_SESSION['admin'] = 1;
    }
    else{
        $_SESSION['admin']= 0;
    }
    $adm = $_SESSION['admin'];
    if (isset($_SESSION['admin'])){
        if ($adm == 1)
        echo "<a href='acceuilAdmin.php'> Se rendre sur l'espace admin </a>";
    }


    $stmt = $dbh->prepare("SELECT COUNT(score) as nb FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $nb_partie = $ligne->nb;

    $stmt = $dbh->prepare("SELECT MAX(score) as max FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $max = $ligne->max;

    $stmt = $dbh->prepare("SELECT MIN(score) as min FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $min = $ligne->min;

    $stmt = $dbh->prepare("SELECT SUM(score) as sum FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $sum = $ligne->sum;

    $moyenne = ($nb_partie > 0) ? ($sum / $nb_partie) : 0;

    echo "<h2>Statistiques de Parties</h2>
<br>
    <table class='table table-striped table-dark' align='center'>
        <tr>
            <th>Moyenne</th>
            <th>Minimum</th>
            <th>Maximum</th>
            <th>Nombre de Parties</th>
        </tr>
        <tr>
            <td>" . $moyenne . "</td>
            <td>" . $min . "</td>
            <td>" . $max . "</td>
            <td>" . $nb_partie . "</td>
        </tr>
    </table>";?>

    <br>
    <br>
    <h2> Historique des parties : </h2>
    <br>
    <table id="example" class='table table-striped table-dark' >
        <thead>
        <tr>
            <th> Score </th>
            <th> Date </th>
        </tr>
        </thead>
        <tbody>
    <?php
    $stmt = $dbh->prepare("SELECT * FROM score_game WHERE user_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
        echo "<tr>";
        echo "<td> $ligne->score</td>";
        echo "<td>$ligne->date </td>";
        echo "</tr>";
    }


    ?>
        </tbody>
    </table>
    <br><br>
    <h2> Historique des logs : </h2>
    <table id='example2' class="table-striped table table-dark">
        <thead>
        <tr>
            <th> Id utilisateur </th>
            <th> Action Réalisé </th>
            <th> IP</th>
            <th> Date et Heure</th>
        </tr>
        </thead> <tbody>
    <?php
    $stmt = $dbh->prepare("SELECT * FROM trace WHERE user_id = :id LIMIT 500");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
        if ($ligne->action == "Deconnexion" ) {
            $color = 'red';
        } elseif ($ligne->action == "Connexion" || $ligne->action == "Creation") {
            $color = 'green';}
        elseif ($ligne->action == "Modification Mail" || "Modification Pseudo" || "Modification mot de passe"){
            $color = 'orange';
        } else {
            $color = '';
        }
        echo "<tr style='color:$color;'>";
        echo "<td style='color:$color;' > $ligne->user_id</td>";
        echo "<td style='color:$color;'>$ligne->action </td>";
        echo "<td style='color:$color;'>$ligne->ip </td>";
        echo "<td style='color:$color;'>$ligne->date </td>";
        echo "</tr>";
    }




    ?>
        </tbody>
    </table>


</div>

</main>
<?php     include('include/menu.php');
?>


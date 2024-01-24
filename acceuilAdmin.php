<?php
include('include/connexion.php');

session_start();
if (isset($_SESSION['admin'])){
$admin = ($_SESSION['admin']);
if ($admin == 0){
    header("Location: compte.php");
}}
include('include/redirection.php');




?>
<head>
<title> Acceuil admin </title>
    <link rel="icon" href="img/monkeyapp.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style/style2.css">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script defer src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script defer src="js/bsTable.js"></script>
</head>


<div class="box">
    <h1> Fonctions Admin</h1>
    <br><br>

    <h2> Tableau des utilisateurs </h2>
    <table id='example' class="table-striped table table-dark">
    <thead>
    <th> id utilisateur</th>
    <th> Pseudo</th>
    <th> Adresse Mail</th>
    <th> Date de naissance</th>
    <th> Droit d'accès</th>
    <th> Fonctionnalitées </th>
    </thead>

        <tbody>
    <?php
    $stmt = $dbh->prepare("SELECT * FROM user");
    $stmt->execute();
    while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
        echo"<tr> <td> $ligne->id </td>";
        echo" <td> $ligne->pseudo </td>";
        echo" <td> $ligne->email </td>";
        echo" <td> $ligne->annee </td>";
        if($ligne->admin == 1)
            $dA = "Admin";
        else
            $dA = "Joueur";
        echo" <td> $dA </td>";
        $userId =  $ligne->id;
        echo "<td><button data-user-id=$userId class='delete-btn'>";?>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 0, 0, 1);transform: ;msFilter:;"><path d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm10.618-3L15 2H9L7.382 4H3v2h18V4z"></path></svg>
        <?php
        echo"
  </button>";
        echo"<button data-user-id=$userId class='admin-btn'>"; ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 250, 0, 1);transform: ;msFilter:;"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
    <?php
        echo"</button>";

         echo"</td></tr>";
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
            <th> Pseudo </th>
            <th> Action Réalisé </th>
            <th> IP</th>
            <th> Date et Heure</th>
        </tr>
        </thead> <tbody>
        <?php
        $stmt = $dbh->prepare("SELECT * FROM trace,user where trace.user_id = user.id  LIMIT 500");
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
            echo "<td style='color:$color;' > $ligne->pseudo </td>";
            echo "<td style='color:$color;'>$ligne->action </td>";
            echo "<td style='color:$color;'>$ligne->ip </td>";
            echo "<td style='color:$color;'>$ligne->date </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php
include('include/menu.php');
?>

<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-user-id');
            var row = this.closest('tr'); // Trouver la ligne de tableau correspondante

            // Demander une confirmation avant la suppression
            if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
                // Si l'utilisateur confirme, exécuter la requête de suppression
                fetch('del_user.php', {
                    method: 'POST',
                    body: JSON.stringify({ userId: userId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data === userId.toString()) { // Vérifier si la suppression a été réussie
                            row.remove(); // Supprimer la ligne du tableau
                            location.reload();
                        } else {
                            alert("Erreur lors de la suppression de l'utilisateur.");
                        }
                    });
            } else {
                // Si l'utilisateur annule, ne rien faire
                console.log('Suppression annulée.');
            }
        });
    });

    document.querySelectorAll('.admin-btn').forEach(button => {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-user-id');

            // Demander une confirmation avant la suppression
            if (confirm("Êtes-vous sûr de vouloir accorder les droits d'admin à cette utilisateur ?")) {
                // Si l'utilisateur confirme, exécuter la requête de suppression
                fetch('admin_user.php', {
                    method: 'POST',
                    body: JSON.stringify({ userId: userId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.text())
                    .then(data => {
                        location.reload();
                    });
            } else {
                // Si l'utilisateur annule, ne rien faire
            }
        });
    });


</script>

<style>
    .delete-btn {
        border: none;
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }

    .delete-btn:hover {
        background-color: #d32f2f; /* Rouge foncé */
    }

    .admin-btn {
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }

    .admin-btn:hover {
        background-color: #f8db54; /* Rouge foncé */
    }

</style>
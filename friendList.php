<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connexion.php');
include('redirection.php');
$id = $_SESSION['id'];
?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="style2.css">
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script defer src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script defer src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script defer src="bsTable.js"></script>
    <head>
        <title> Amis</title>
        <link rel="icon" href="monkeyapp.png">

    </head>
    <main>
    <div class="box">
    <h1> Amis </h1>
        <br><br>
        <h2> Vos amis </h2>

        <table id="example" class='table table-striped table-dark'>
            <thead>
            <th> Pseudo </th>
            <th> Statut</th>
            </thead>

            <tbody>
            <?php
            $stmt = $dbh->prepare(    "SELECT f.*, u1.pseudo AS pseudo_user1, u2.pseudo AS pseudo_user2
            FROM friendship f
            JOIN user u1 ON f.user_id1 = u1.id
            JOIN user u2 ON f.user_id2 = u2.id
            WHERE f.user_id1 = :id OR f.user_id2 = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
                if ($ligne->user_id1 == $id){
                    $pseudo = $ligne->pseudo_user2;
                }
                else{
                    $pseudo = $ligne->pseudo_user1;
                }
                echo "<tr>";
                echo "<td> $pseudo </td>";
                if ($ligne->status == "En attente"){
                    if($ligne->action_user_id == $id){
                        $statut = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 126, 0, 1);transform: ;msFilter:;"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path></svg>';
                    }
                    else{
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(71, 255, 0, 1);transform: ;msFilter:;"><path d="M20.29 8.29 16 12.58l-1.3-1.29-1.41 1.42 2.7 2.7 5.72-5.7zM4 8a3.91 3.91 0 0 0 4 4 3.91 3.91 0 0 0 4-4 3.91 3.91 0 0 0-4-4 3.91 3.91 0 0 0-4 4zm6 0a1.91 1.91 0 0 1-2 2 1.91 1.91 0 0 1-2-2 1.91 1.91 0 0 1 2-2 1.91 1.91 0 0 1 2 2zM4 18a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3v1h2v-1a5 5 0 0 0-5-5H7a5 5 0 0 0-5 5v1h2z"></path></svg>';
                        $statut = "<button class='accept-btn' onclick='accepterDemandeAmi(" . $ligne->id . ")'>$icon</button>";
                    }
                }
                else{
                    $statut = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(71, 255, 0, 1);transform: ;msFilter:;"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path></svg>';
                }
                echo "<td> $statut <button class='delete-btn' onclick='supprimerAmi(" . $ligne->id . ")'> ";
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 0, 0, 1);transform: ;msFilter:;"><path d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm10.618-3L15 2H9L7.382 4H3v2h18V4z"></path></svg>

                </button></td>
            <?php
                echo "</tr>";
            }
            ?>

            </tbody>


        </table>
        <br><br>
        <h2> Ajouter un ami </h2>

        <table id="example2" class='table table-striped table-dark' >
        <thead>
        <th>
            Pseudo
        </th>
        <th>
            Ajouter
        </th>
        </thead>
            <tbody>

                <?php
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(71, 255, 0, 1);transform: ;msFilter:;"><path d="M4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5zM19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2z"></path></svg>';
                $currentUserId = $id;
                $stmt = $dbh->prepare(
                    "SELECT u.pseudo, u.id FROM user u WHERE u.id != :currentUserId AND u.id NOT IN (
                        SELECT user_id2 FROM friendship WHERE user_id1 = :currentUserId AND (status = 'Ami' OR status = 'En attente')
                        UNION
                        SELECT user_id1 FROM friendship WHERE user_id2 = :currentUserId AND (status = 'Ami' OR status = 'En attente')
                        )"
                );
                $stmt->bindParam(':currentUserId', $currentUserId);
                $stmt->execute();

                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($user['pseudo']) . '</td>';
                    echo "<td><button class='accept-btn' onclick='envoyerDemandeAmi(" . $user['id'] . ")'>" . $icon . "</button></td>";
                    echo '</tr>';
                }
                ?>





            </tbody>
        </table>
    </div>


    </main>

<script>
    function accepterDemandeAmi(id) {
        // Envoyer une requête AJAX pour accepter la demande d'amitié
        fetch('accepter_demande.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();// Affiche une alerte avec la réponse du serveur
                // Vous pouvez également rafraîchir la page ou mettre à jour l'interface utilisateur ici
            });
    }

    function supprimerAmi(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet ami ?")) {
            // Envoyer une requête AJAX pour supprimer l'ami
            fetch('supprimer_ami.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();// Affiche une alerte avec la réponse du serveur
                    // Vous pouvez rafraîchir la page ou mettre à jour l'interface utilisateur ici
                });
        }
    }

    function envoyerDemandeAmi(idAmi) {
        fetch('envoyer_demande.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'idAmi=' + idAmi
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();// Afficher une alerte avec la réponse du serveur
                // Vous pouvez également rafraîchir la page ou mettre à jour l'interface utilisateur ici
            });
    }

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

        .accept-btn{
            border: none;
            border: none;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .accept-btn:hover {
            background-color: rgba(71, 255, 0, 0.55);
        }


    </style>

<?php
include('menu.php');
?>
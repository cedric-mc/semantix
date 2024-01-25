<?php 
exec("./jdk-21/bin/java -jar ./Fichiers_Java/SAE/out/artifacts/SAE_jar/SAE.jar optimize fichier_du_jeu.txt 2>&1", $output);
echo json_encode($output);
?>
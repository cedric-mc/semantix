<?php 
$mot = $_GET['mot'];
exec("./Fichiers_C/add_word Fichiers_C/words.bin $mot");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page PHP</title>
</head>
<body>
<?php
exec('./hello', $output); // Exec ça permet d'appeler un autre programme sur la page php
foreach ($output as $line) {
    echo $line . "<br>";
}
?>
</body>
</html>


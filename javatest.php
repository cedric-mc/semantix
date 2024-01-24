<?php
exec( "./jdk-21/bin/java -jar ./Fichiers_Java/SAE/out/artifacts/SAE_jar/SAE.jar 2>&1",$output);
print_r($output);

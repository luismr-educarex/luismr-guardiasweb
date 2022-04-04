<!DOCTYPE html>
<html>
<body>

<?php

    
    $cadena = "MAT.AC-3ESO 3º ESO A (AULA 3A)";
$buscar = "MAT.AC";
$resultado = strpos($cadena, $buscar);
if($resultado !== FALSE){
    echo "La subcadena '$buscar' fue encontrada dentro de la cadena '$cadena' en la posición: '$resultado'";
}
?> 

</body>
</html>
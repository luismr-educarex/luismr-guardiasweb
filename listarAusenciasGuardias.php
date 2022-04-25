<?php

require_once './ausencias/AusenciasDAO.php';
require_once './ausencias/Guardias.php';


$ausenciaDAO = new AusenciasDAO();

$semana = 29;
$dia = 5;

$result = $ausenciaDAO ->obtener_ausencias($semana,$dia);

print_r($result);


?>
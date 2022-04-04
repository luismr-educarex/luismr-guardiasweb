<?php

require_once 'AusenciasDAO.php';
require_once 'Guardias.php';


if(isset($_POST["ausencia"]))
      $ausencia = $_POST["ausencia"]; 
if(isset($_POST["docenteGuardia"]))
      $docenteGuardia = $_POST["docenteGuardia"]; 

$guardia = new Guardia();

$guardia->setId($ausencia);
$guardia->setIdProfGuardia($docenteGuardia);

$ausenciaDAO = new AusenciasDAO();

$result = $ausenciaDAO ->guardar_docenteGuardia($guardia);

return $result;


?>
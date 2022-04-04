<?php

require_once 'AusenciasDAO.php';
require_once 'Guardias.php';


if(isset($_POST["profesor"]))
      $profesor = $_POST["profesor"]; 
if(isset($_POST["semana"]))
      $semana = $_POST["semana"]; 
if(isset($_POST["dia"]))
      $dia = $_POST["dia"];
if(isset($_POST["hora"]))
      $hora = $_POST["hora"]; 

$guardia = new Guardia();

$guardia->setIdProfesor($profesor);
$guardia->setSemana($semana);
$guardia->setDia($dia);
$guardia->setHora($hora);

$ausenciaDAO = new AusenciasDAO();

$id = $ausenciaDAO ->borrar_ausencia($guardia);

return $id;


?>
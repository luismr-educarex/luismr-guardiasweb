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
if(isset($_POST["observaciones"]))
      $observaciones = $_POST["observaciones"]; 

$guardia = new Guardia();

$guardia->setIdProfesor($profesor);
$guardia->setSemana($semana);
$guardia->setDia($dia);
$guardia->setHora($hora);

$ausenciaDAO = new AusenciasDAO();

$id = $ausenciaDAO ->crear_observacion($guardia,$observaciones);

return $id;


?>
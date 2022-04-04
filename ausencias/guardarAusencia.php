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
if(isset($_POST["idhora"]))
      $idhora = $_POST["idhora"]; 
if(isset($_POST["fecha"]))
      $fecha = $_POST["fecha"]; 

$guardia = new Guardia();

$guardia->setIdProfesor($profesor);
$guardia->setSemana($semana);
$guardia->setDia($dia);
$guardia->setHora($hora);
$guardia->setIdHora($idhora);
$guardia->setFechaGuardia($fecha);

$ausenciaDAO = new AusenciasDAO();

$id = $ausenciaDAO ->crear_ausencia($guardia);

echo $id;


?>
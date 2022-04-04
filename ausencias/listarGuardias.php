

<?php

require_once '../comun/cabecera.php';
require_once 'GuardiaDao.php';
require_once 'Guardias.php';
require_once '../comun/fechas.php';
require_once '../DTO/HorasSemanasDocente.php';
require_once '../docente/DocenteDAO.php';


$guardiasDAO = new GuardiaDao();

if(isset($_GET["dia"]))
      $dia = $_GET["dia"]; 
if(isset($_GET["hora"]))
      $hora = $_GET["hora"]; 


$guardias = $guardiasDAO ->obtener_guardias($dia,$hora);


// se pasa los resultados obtenidos de la consulta de la base de datos a un array asociativo.
$listaGuardias = array();
if ($guardias->num_rows > 0) {
    while($guardia = $guardias->fetch_assoc()) {       
        $listaGuardias []= $guardia;   
    } 
} 



?>
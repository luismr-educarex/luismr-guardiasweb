
<?php
// Motrar todos los errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../comun/cabecera.php';
require_once 'DocenteDao.php';
require_once 'DocenteDTO.php';
require_once '../horario/HorarioDao.php';
require_once '../horario/Hora.php';

 
$docente_dao = new DocenteDao();
$docenteDTO = new DocenteDTO();


if(isset($_POST["id"]))
      $id = $_POST["id"]; 
if(isset($_POST["nombre"]))
      $nombre = $_POST["nombre"]; 


$horario = array();

//Recorrer los datos del formulario del horario
for($dia=1;$dia<=5;$dia++){
      for($hora=0;$hora<=5;$hora++){

           $idHora =   $_POST["id_".$dia."_".$hora];
           $materia = $_POST["materia_".$dia."_".$hora];
           $aula =  $_POST["aula_".$dia."_".$hora];
           $grupos =  $_POST["grupos_".$dia."_".$hora];

          // echo "DATOS HORA:id: ". $idHora." - materia:". $materia." - aula:". $aula." -grupos:".$grupos.'<br>';
           $objetoHora = new Hora($idHora,$materia,$aula,$grupos,$dia,$hora);
           array_push($horario,$objetoHora);


      }
}


$docenteDTO->setId($id);
$docenteDTO->setNombre($nombre);

$horarioDAO = new HorarioDao();

$horarioDAO->modificarHorario($id,$horario);


$resultado = $docente_dao->guardar($docenteDTO);

header("Location: listarDocentes.php");
die();




?>
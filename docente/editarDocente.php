
<?php
require_once '../comun/cabecera.php';
require_once '../docente/docenteDAO.php';
require_once '../docente/DocenteDTO.php';

 


$docente_dao = new docenteDAO();
$docenteDTO = new DocenteDTO();


if(isset($_POST["id"]))
      $id = $_POST["id"]; 
if(isset($_POST["nombre"]))
      $nombre = $_POST["nombre"]; 
if(isset($_POST["departamento"]))
      $departamento = $_POST["departamento"]; 

$docenteDTO->setId($id);
$docenteDTO->setNombre($nombre);
$docenteDTO->setDepartamento($departamento);


$resultado = $docente_dao->guardar($docenteDTO);

header("Location: listarDocentes.php");
die();




?>
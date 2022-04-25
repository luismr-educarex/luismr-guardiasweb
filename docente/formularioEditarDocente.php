<?php
// Motrar todos los errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  require_once '../comun/cabecera.php';
  require_once 'DocenteDao.php';

  $docente_dao = new DocenteDao();

  if(isset($_GET["id"]))
      $id = $_GET["id"]; 
  $docente = $docente_dao->cargar_docente_por_id($id);
?>





<body>

<div class="container   bloque_contenido">
       
   
<form class="form-inline" action="editarDocente.php" method="POST">
<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $docente['id'] ?>">

  
  <span class="textoDatosDocente"> Docente</span> <input type="input" class="form-control" id="nombre" name="nombre" value="<?php echo $docente['nombre'] ?>">

 
  <?php
      include 'mostrarFormularioHorario.php';

  ?>


  
  <button type="submit" class="btn btn-info">Grabar</button>
</form>
      
  </div>

</body>



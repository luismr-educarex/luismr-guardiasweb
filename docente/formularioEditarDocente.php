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
  $longitud = strlen($docente['nombre']);
?>





<body>

<div class="container   bloque_contenido">
       
   
<form class="form-inline" action="editarDocente.php" method="POST">

<div class="row">
        <div class="col-md-8 col-md-offset-2">
           
                <div class="d-flex">
                <span class="textoDatosDocente"> Docente</span> 
  <input type="input" size="<?php echo $longitud ?>" class="form-control" id="nombre" name="nombre" value="<?php echo $docente['nombre'] ?>">
                    <button type="submit" class="btn btn-info">Grabar</button>
                </div>
            
        </div>
    </div>




<div class="row">


 
</div>

<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $docente['id'] ?>">

  
  
 
  <?php
      include 'mostrarFormularioHorario.php';

  ?>


  
  <button type="submit" class="btn btn-info">Grabar</button>
</form>
      
  </div>

</body>



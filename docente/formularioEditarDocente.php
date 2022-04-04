 
<?php

  require_once '../comun/cabecera.php';
  require_once 'docenteDAO.php';

  $docente_dao = new docenteDAO();

  if(isset($_GET["id"]))
      $id = $_GET["id"]; 
  $docente = $docente_dao->cargar_docente_por_id($id);
?>





<body>

<div class="container">
       
   
<form action="editarDocente.php" method="POST">
<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $docente['id'] ?>">
  <div class="form-group">
    <label for="nombre">Nombre:</label>
    <input type="input" class="form-control" id="nombre" name="nombre" value="<?php echo $docente['nombre'] ?>">
  </div>
  <div class="form-group">
    <label for="dpto">Departamento:</label>
    <input type="input" class="form-control" id="dpto" name="departamento" value="<?php echo $docente['departamento'] ?>">
  </div>
  
  <button type="submit" class="btn btn-info">Grabar</button>
</form>
      
  </div>

</body>





<?php
require_once '../comun/cabecera.php';
require_once '../docente/DocenteDao.php';

$docente_dao = new DocenteDao();


$lista_docentes = $docente_dao->cargar_docentes();



echo '<div class="container bloque_contenido">
       
         <div class="row">
    
    <div class="col-sm-1 icono">
   
    </div>
    <div class="col-sm-2">
       <span class="texto_titulo">Docentes</span>
    </div>
    
    <div class="col-sm-4 menu_alumnos">
       
     
   <div class="buscador">
   <input class="form-control" id="bucador_docente" type="text" placeholder="Buscar..">  
    </div>
  </div>';  


  echo ' <table class="table" id="tabla_docente">
    <thead>
      <tr>
        <th class="texto_cabecera_tabla">nombre</th>
       
         <th></th>
          <th></th>
            <th></th>
         <th></th>
      </tr>
    </thead><tbody>';
         
    
    while($docente = $lista_docentes->fetch_assoc()) {

    echo '<tr data-docente="'.$docente["id"].'">
          <td class="textoTabla">'.$docente["nombre"].'</td>
       
           
          <td> <a href="../horario/obtenerHorasSemanaDocente.php?docente='.$docente["id"].'" class="btn btn-info btn-sm" role="button">HORARIO</a></td>
           <td><a href="../ausencias/obtenerAusenciasDocente.php?docente='.$docente["id"].'" class="btn btn-info btn-sm" role="button">AUSENCIAS</a></td>
          <td><a href="formularioEditarDocente.php?id='.$docente["id"].'" class="btn btn-info btn-sm" role="button">EDITAR</a></td>
          <td><a href="#" class="btn btn-info btn-sm" role="button">DAR DE BAJA</a></td>

        </tr>';
        
          }
    
    echo' </tbody>
  </table>
 
 
</div>'  ;   
        
      
?>
<script>

$(document).ready(function(){
  $("#bucador_docente").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tabla_docente tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>


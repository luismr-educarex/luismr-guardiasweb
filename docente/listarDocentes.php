

<?php
require_once '../comun/cabecera.php';
require_once '../docente/DocenteDao.php';

$docente_dao = new DocenteDao();


$lista_docentes = $docente_dao->cargar_docentes();



echo '<div class="container bloque_contenido">
       
         <div class="row">
    
  
    <div class="col-sm-3">
       <span class="texto_titulo">Docentes</span>
    </div>
    
    <div class="col-sm-4">
       
     
   <div class="buscador">
   <input class="form-control" id="bucador_docente" type="text" placeholder="Buscar..">  
    </div>
  </div>
  </div>';  


  echo ' <table class="table" id="tabla_docente">
    <thead>
      <tr>
        <th ></th>
       
         <th></th>
          <th></th>
            <th></th>
      
      </tr>
    </thead><tbody>';
         
    
    while($docente = $lista_docentes->fetch_assoc()) {

    echo '<tr data-docente="'.$docente["id"].'">
          <td class="textoTabla">'.$docente["nombre"].'</td>
       
           
          <td> <a href="../horario/obtenerHorasSemanaDocente.php?docente='.$docente["id"].'" class="btn boton_listado btn-sm" role="button">HORARIO</a></td>
           <td><a href="../ausencias/obtenerAusenciasDocente.php?docente='.$docente["id"].'&nombre='.$docente["nombre"].'" class="btn boton_listado btn-sm" role="button">AUSENCIAS</a></td>
          <td><a href="formularioEditarDocente.php?id='.$docente["id"].'" class="btn boton_listado btn-sm" role="button">EDITAR</a></td>
         

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


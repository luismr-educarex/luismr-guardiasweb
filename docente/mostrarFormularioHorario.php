<head>
  <title>GUARDIAS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    
<style>
   
   </style>    
    
</head>
<?php

  require_once '../comun/cabecera.php';
  require_once '../comun/fechas.php';

?>
<script>
                
$(document).ready(function(){
    
    
}); // fin de ready del documento

    
</script>

<?php

 require('../bd/conexion.php');
 require_once '../ausencias/Guardias.php';
 require_once '../DTO/HorasSemanasDocente.php';


/** RECUPERAR DATOS DEL DOCENTE */
$sql_docente="SELECT * FROM docente WHERE id=".$id; 
  $resultado_consulta_docente = mysqli_query($connection,$sql_docente) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_docente);
  $datos_docente = $resultado_consulta_docente->fetch_assoc();   
  $docente =  $datos_docente['nombre'];
 /** -------------------------------------- */ 

/** RECUPERAR HORARIO DEL DOCENTE */
 $sql_recuperar_horario="SELECT h.id idhora,h.dia,h.hora,materia,h.grupo,h.aula,nombre,g.id ausencia,g.tarea,g.observaciones
FROM horario h 
INNER JOIN docente d ON h.idProfesor=d.id 
LEFT JOIN guardia g ON h.id=g.horario
WHERE d.id=".$id."
ORDER BY h.dia,h.hora";
$horas_docente = mysqli_query($connection,$sql_recuperar_horario) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_recuperar_horario);
mysqli_close($connection);
$datosHoras = array();
if ($horas_docente->num_rows > 0) {
    while($hora = $horas_docente->fetch_assoc()) {       
        $datosHoras []= $hora;  
    }
    
} 

/** -------------------------------------- */



$codigo_html2 = mostrarHorariosDocentes2(obtenerHorario2($datosHoras),$id,$docente);

echo $codigo_html2;



/**Funcion que crea un array asociativo en el que cada elemento corresponde a un día de la semana 
 * $dias["LUNES"][{{2,1},{4,5},{6,3}}] -> En el lunes se tiene clase en tres días.
*/

function obtenerHorario2($lista_horas){
    
     $dias = array();
   
     $dias["LUNES"]=recuperaHoras2($lista_horas,1);
     $dias["MARTES"]=recuperaHoras2($lista_horas,2);
     $dias["MIERCOLES"]=recuperaHoras2($lista_horas,3);
     $dias["JUEVES"]=recuperaHoras2($lista_horas,4);
     $dias["VIERNES"]=recuperaHoras2($lista_horas,5);
    
    return $dias;
    
}


/**función que crea por cada día un array asociativo con las horas que hay clase
 * $clases[2][1] -> es la hora 2 del día 1, lunes.
 * $clases[4][5] -> es la hora 4 del día 5, viernes.
 * $clases[6][3] -> es la hora 6 del día 3, miercoles.
 * 
 * En cada posición se inserta un objeto de tipo HorasSemanasDocente en el que se recoge toda la información de esa hora
 * incluida la información de la ausencia en caso de que haya.
 */
function recuperaHoras2($listaHoras,$dia){
    
    
    $clases=array();
    $hora=1;
    $num_clases=sizeof($listaHoras); // total de horas de clase del docente

    for($num_clase=0;$num_clase<$num_clases;$num_clase++){
        if($listaHoras[$num_clase]['dia']==$dia){
             $horaDocente = new HorasSemanasDocente();
             $horaDocente->setId($listaHoras[$num_clase]['idhora']);
             $horaDocente->setDia($listaHoras[$num_clase]['dia']);
             $horaDocente->setHora($listaHoras[$num_clase]['hora']);
             $horaDocente->setContenido($listaHoras[$num_clase]['materia']);
             $horaDocente->setGrupo($listaHoras[$num_clase]['grupo']);
             $horaDocente->setAula($listaHoras[$num_clase]['aula']);
             //estos tres datos vienen de la tabla guardia
            
           
    
           //----------------------------

            $clases[$hora]=$horaDocente;
            $hora++;
        }
    }

    return $clases;
    
}


function mostrarHorariosDocentes2($horario,$id_docente){
    
     
     
    $html='
   
      <div class="horario">
    <div class="row">
    <div class="col-sm-4 docente" data-docente='.$id_docente.'></div>
   
     <div class="col-sm-7">
      
     
    
       <!--<a href="../docente/listarDocentes.php" class="btn btn-info btn-volver boton" role="button">Volver </a>-->
</div>
<div class="col-sm-1">
</div>
    </div>
    <div class="row">
      <div class="col-sm-2 bg-celda-info celdaTituloH">H</div>
       <div class="col-sm-2 bg-celda-info">
                   LUNES 
                
       </div> 
        <div class="col-sm-2 bg-celda-info">  MARTES
            
        </div>
         <div class="col-sm-2 bg-celda-info">  MIERCOLES
             
         
         </div>
          <div class="col-sm-2 bg-celda-info">  JUEVES
              
          </div>
           <div class="col-sm-2 bg-celda-info">  VIERNES
                 
           </div>  
    </div>'; 

      
           for($i=1;$i<7;$i++){
               
             $html =$html.'<div class="row">';
             $html =$html.'<div class="col-sm-2 bg-celda-info celdaHora">'.$i.'</div>';
             $html =$html.''.construirHTML_hora2($horario['LUNES'][$i]);
             $html =$html.''.construirHTML_hora2($horario['MARTES'][$i]);
             $html =$html.''.construirHTML_hora2($horario['MIERCOLES'][$i]);
             $html =$html.''.construirHTML_hora2($horario['JUEVES'][$i]);
             $html =$html.''.construirHTML_hora2($horario['VIERNES'][$i]);
             $html =$html.'</div>';
           }
        
  $html =$html.'  </div>';
    
    return $html;
}


//Rrecibe la semana y un objeto de tipo HorasSemanasDocente con la variable $info
function construirHTML_hora2($info){
    
    $idhora=$info->getId();
    $dia=$info->getDia();
    $hora=$info->getHora();
    $materia=$info->getContenido();
    $grupo=$info->getGrupo();
    $aula=$info->getAula();
  
   

    $html='<div class="col-sm-2 celda " id="celda_'.$dia.'_'.$hora.'"   
    data-dia="'.$dia.'" 
    data-hora="'.$hora.'" 
    data-idhora="'.$idhora.'">
                <input type="hidden" class="campo_edicion_horario" name="id_'.$dia.'_'.$hora.'" id="id_'.$dia.'_'.$hora.'" value="'.$idhora.'" />
                <input type="text" class="campo_edicion_horario" name="materia_'.$dia.'_'.$hora.'" id="materia_'.$dia.'_'.$hora.'" value="'.$materia.'" />
                <input type="text" class="campo_edicion_horario" name="aula_'.$dia.'_'.$hora.'" id="aula_'.$dia.'_'.$hora.'" value="'.$aula.'" />
                <input type="text" class="campo_edicion_horario" name="grupos_'.$dia.'_'.$hora.'" id="grupos_'.$dia.'_'.$hora.'" value="'.$grupo.'" />
                 
                
            </div>';
    
    
    return $html;
    
}

?>
  
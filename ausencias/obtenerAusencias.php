<?php

include 'AusenciasDAO.php';


$ausenciaDAO = new AusenciasDAO();

if(isset($_GET["semana"]))
      $semana = $_GET["semana"]; 
if(isset($_GET["dia"]))
      $dia = $_GET["dia"]; 
if(isset($_GET["fecha"]))
      $fecha = $_GET["fecha"]; 


$ausencias = $ausenciaDAO ->obtener_ausencias($semana,$dia);


// se pasa los resultados obtenidos de la consulta de la base de datos a un array asociativo.
$listaAusencias = array();
if ($ausencias->num_rows > 0) {
    while($ausencia = $ausencias->fetch_assoc()) {   
         
        $listaAusencias []= $ausencia;   
    } 
} 


//obtenemos el codigo html del cuadrante por día de las ausencias y guardias
$contenido = mostrarCuadranteAusencia($listaAusencias,$dia,$fecha,$semana);
echo $contenido;



//En esta función de obtiene un array asociativo bidimensional, por cada hora (1 hasta 6) obtenemos un array que almacena las ausencias de esa hora.
//Las lista por horas están formadas por objetos DTO de tipo Guardia.
function obtenerAusenciasPorHora($ausencias){
    
     $ausenciasPorHora = array();
     
     $ausenciasPorHora["1"]=recuperaHorasAusentes($ausencias,1);
     $ausenciasPorHora["2"]=recuperaHorasAusentes($ausencias,2);
     $ausenciasPorHora["3"]=recuperaHorasAusentes($ausencias,3);
     $ausenciasPorHora["4"]=recuperaHorasAusentes($ausencias,4);
     $ausenciasPorHora["5"]=recuperaHorasAusentes($ausencias,5);
     $ausenciasPorHora["6"]=recuperaHorasAusentes($ausencias,6);
    
    return $ausenciasPorHora;
    
}

//Obtenemos de la lista de ausencias solo las ausencias de la hora que se le pasa, usando para la lista objetos DTO de tipo Guardia
function recuperaHorasAusentes($lista,$hora){
    
    include_once '../ausencias/Guardias.php';
    $ausencias=array();
    $ausencia=0;
    $num_clases=sizeof($lista);
  
    for($num_clase=0;$num_clase<$num_clases;$num_clase++){
        if($lista[$num_clase]['hora']==$hora){
            $ausenciaGuardia = new Guardia();
            $ausenciaGuardia->setId($lista[$num_clase]['id']);
            $ausenciaGuardia->setIdHora($lista[$num_clase]['idhora']);
            $ausenciaGuardia->setNombreDocente($lista[$num_clase]['nombre']);
            $ausenciaGuardia->setDia($lista[$num_clase]['dia']);
            $ausenciaGuardia->setHora($lista[$num_clase]['hora']);
            $ausenciaGuardia->setTarea($lista[$num_clase]['tarea']);
            $ausenciaGuardia->setGrupo($lista[$num_clase]['grupo']);
            $ausenciaGuardia->setAula($lista[$num_clase]['aula']);
            $ausenciaGuardia->setIdProfGuardia($lista[$num_clase]['idProfGuardia']);
            $ausenciaGuardia->setObservaciones($lista[$num_clase]['observaciones']);
            $ausencias[$ausencia]=$ausenciaGuardia;
            $ausencia++;
        }
    }
    
    return $ausencias;
    
}



function mostrarCuadranteAusencia($lista,$dia,$fecha,$semana){

    
    $listaAusencias = obtenerAusenciasPorHora($lista,$dia);
    
     $html='
      <div class="w3-container contenedortabla">

      <div class="docente">GUARDIAS '.$fecha.'</div>

    <table class="w3-table">
    <tr>
      <th class="cabeceraTabla numeroHora">H</th>
      <th class="cabeceraTabla">DOCENTES AUSENTES</th>
      <th class="cabeceraTabla">GRUPO</th>
      <th class="cabeceraTabla">AULA</th>
      <th class="cabeceraTabla">TAREA</th>
      <th class="cabeceraTabla">OBSERVACIONES</th>
      <th class="cabeceraTabla">DOCENTES EN GUARDIA</th>
      <th class="cabeceraTabla">CONTADOR</th>
    </tr>'; 
    

    $horaActualLectiva =obtenerHoraActualdeDocencia();
    
    for($hora=1;$hora<7;$hora++){    
         
              $html =$html.imprimirFila($fecha,$semana,$dia,$hora,$horaActualLectiva,$listaAusencias);

    }
        
    $html =$html.'   </table></div> ';
    
    return $html;
    
}



function imprimirFila($fecha,$semana,$dia,$hora,$horaActualLectiva,$listaAusencias){    
    
    $ausencias = $listaAusencias[$hora];
    $num_ausencias=sizeof($ausencias);
    
    $html="";
    $numeroProfesoresAusentes=sizeof($listaAusencias[$hora]);
    $num_celda=1;

    $estiloFilaHoraActual="";
    if($hora==$horaActualLectiva){
        $estiloFilaHoraActual="horaActual";
    }
    
    if($num_ausencias==0){ //no hay ausencias
        
         $html =$html.' <tr id="'.$hora.'" class="fila2">';
          $html =$html.' <td class="fila2 numeroHora '.$estiloFilaHoraActual.'"><span class="hora">'.$hora.'</span></td>';
          $html =$html.' <td class="fila2 sinAusencias campoDocente '.$estiloFilaHoraActual.'" colspan="6">SIN AUSENCIAS</td>';
          $html =$html.' <td class="celda2final '.$estiloFilaHoraActual.'" ></td>';
         $html =$html.'</tr>';
    }
    
    else{ //hay ausencias
       
        for($num_ausencia=0;$num_ausencia<$num_ausencias;$num_ausencia++){
  
           $estiloFila="";
           if($num_celda==$num_ausencias) //última fila, último tr
           {
                $estiloFila="fila2";
           }
           
           $html =$html.' <tr class="'.$estiloFila.'">';
            
            if($num_celda==1) //primera celda, es la que muestra la hora
           {
              $html =$html.' <td rowspan="'.$num_ausencias.'" class="fila2 numeroHora"><span class="hora">'.$hora.'</span></td>';
           }  
        
            $estiloDocente="ausenciaSinGuardia";
            $estiloGrupo = "textoAusenciaSinGuardia"; 
            $estiloAula= "textoAusenciaSinGuardia"; 
            
            if($ausencias[$num_ausencia]->getIdProfGuardia()!=NULL && $ausencias[$num_ausencia]->getIdProfGuardia()!=0 ){
               $estiloDocente = "ausenciaConGuardia"; 
                $estiloGrupo = "textoAusenciaConGuardia"; 
                 $estiloAula= "textoAusenciaConGuardia"; 
            }
         
            $html =$html.'<td class="celda2 datosAusencia campoDocentesAusentes campoDocente '.$estiloFilaHoraActual.'" data-idAusencia="'.$ausencias[$num_ausencia]->getId().'" ><div id="'.$ausencias[$num_ausencia]->getId().'"  class="'.$estiloDocente.'">'.$ausencias[$num_ausencia]->getNombreDocente().'</div></td>';
           
            $html =$html.'<td id="grupo_'.$ausencias[$num_ausencia]->getId().'" class="celda2 '.$estiloGrupo.' campoGrupos '.$estiloFilaHoraActual.'">'.$ausencias[$num_ausencia]->getGrupo().'</td>';
            
            
            $html =$html.'<td id="aula_'.$ausencias[$num_ausencia]->getId().'" class="celda2 '.$estiloAula.' campoAula '.$estiloFilaHoraActual.'"> '.$ausencias[$num_ausencia]->getAula().'</td>';
            
            $iconotarea='../imagenes/icono_tarea2.png';
    
            if($ausencias[$num_ausencia]->getTarea()==1){
             $html = $html.'<td class="celda2 campoTarea '.$estiloFilaHoraActual.'"><img src="'.$iconotarea.'" width="30px" height="30px"/></td>';
            }else{
             $html =$html.'<td class="celda2 campoTarea '.$estiloFilaHoraActual.'"></td>';
            }
            
            $html =$html.'<td class="celda2 campoObservaciones observaciones '.$estiloFilaHoraActual.'">'.$ausencias[$num_ausencia]->getObservaciones().'</td>';
            $html =$html.'<td class="celda2 campoDocentesAusentes '.$estiloFilaHoraActual.'">'.mostrarDocentesEnGuardia($ausencias[$num_ausencia]->getDia(),$hora,$ausencias[$num_ausencia]->getId(),$ausencias[$num_ausencia]->getIdProfGuardia()).'</td>';
           
            
          
            if($num_celda==($num_ausencias-1)) //ultima celda, es la que muestra el boton
            {
                $html =$html.'<td rowspan="'.$num_ausencias.'" class="celda2final campoContador '.$estiloFilaHoraActual.'"><a class="botonListaGuardias" href="listarGuardiasHechas.php?semana='.$semana.'&fecha='.$fecha.'&dia='.$dia.'&hora='.$hora.'"><img src="../imagenes/icono_abacus.png" class="icono_abacus"></img></a></td>';
           
            }  
            if($num_ausencias==1) //ultima celda, es la que muestra el boton
            {
                $html =$html.'<td rowspan="'.$num_ausencias.'" class="celda2final campoContador '.$estiloFilaHoraActual.'"><a class="botonListaGuardias" href="listarGuardiasHechas.php?semana='.$semana.'&fecha='.$fecha.'&dia='.$dia.'&hora='.$hora.'"><img src="../imagenes/icono_abacus.png" class="icono_abacus"></img></a></td>';
           
            }  

            $html =$html.'</tr>';
        
            $num_celda++;
        
    }
    }
    
    
    
    return $html;
}


function mostrarDocentesEnGuardia($dia,$hora,$idAusencia,$idDocenteGuardia){
    
   include_once '../docente/DocenteDao.php';

    $docenteDAO = new DocenteDao();

    $docentesEnGuardia = $docenteDAO ->cargar_docentes_guardias($dia,$hora);
    
    $listaGuardias = array();
    if ($docentesEnGuardia->num_rows > 0) {
        while($guardia = $docentesEnGuardia->fetch_assoc()) {       
            $listaGuardias []= $guardia;   
        } 
    } 

    
    $html='';
    
          if (sizeof($listaGuardias) > 0) {
               $html = $html.'<div><select class="selectorDocenteGuardia select" name="docenteGuardia" id="docenteGuardia_'.$idAusencia.'" onchange="seleccionarDocenteGuardia(this.value,'.$idAusencia.')" data-idausencia="'.$idAusencia.'">
               <option value="0">DOCENTE DE GUARDIA</option>';
            $num_guardia=0;
            while($num_guardia<sizeof($listaGuardias)) {   
                 $actual = "";
               
               
                  if($listaGuardias[$num_guardia]["idProfesor"]==$idDocenteGuardia){
                        $actual="selected";
                  }


                 $html = $html.'<option value="'.$listaGuardias[$num_guardia]["id"].'" '.$actual.'>'.$listaGuardias[$num_guardia]["nombre"].' </option>';   
                $num_guardia++;
            }   
            
           $html = $html.'</select></div>';
        
        
     }
    
    
    return $html;
} 


function obtenerHoraActualdeDocencia(){
   

    $time = time();

$horaActual =strtotime(date('H:i',$time));


$datos = file_get_contents("../configuracion/horas.json");
$horario = json_decode($datos,true);
$horas = $horario['horas'];

$horalectiva = 0;
$horaEncontrada=false;

foreach($horas as $hora){
    $hora_inicio=$hora['inicio'];
    $hora_fin=$hora['fin'];


    if(($horaActual>=strtotime($hora_inicio,$time)) && ($horaActual<=strtotime($hora_fin,$time)) && !$horaEncontrada)
    {
        $horalectiva = $hora['hora'];
        $horaEncontrada=true;
        
    } 
}
    


return $horalectiva;

}

?>

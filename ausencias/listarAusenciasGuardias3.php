<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../comun/cabecera.php';
require_once 'AusenciasDAO.php';
require_once 'Guardias.php';
require_once '../comun/fechas.php';
require_once '../DTO/HorasSemanasDocente.php';
require_once '../docente/DocenteDao.php';

?>
<script>
$(document).ready(function() {	
    function comprobarHora() {

        $.ajax({
            type: "POST",
            url: "../comprobarHora.php",
            success: function(data) {
                console.info(data);
                fila="#"+data;
                $(fila).addClass("horaActual");
               
            
                    if(data>1){
                        filaAnterior="#"+(data-1);
                        $(filaAnterior).removeClass("horaActual");
                    }
                
            }
        });
       
    }
    setInterval(comprobarHora, 5000);
});
</script>
<?php


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

      <div class="docente">'.$fecha.'</div>

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
    
    for($hora=1;$hora<7;$hora++){    
         
              $html =$html.imprimirFila($fecha,$semana,$dia,$hora,$listaAusencias);

    }
          
    $html =$html.'   </table></div> ';
    
    return $html;
}



function imprimirFila($fecha,$semana,$dia,$hora,$listaAusencias){    
    
    $ausencias = $listaAusencias[$hora];
    $num_ausencias=sizeof($ausencias);
    
    $html="";
    $numeroProfesoresAusentes=sizeof($listaAusencias[$hora]);
    $num_fila=1;
    
    if($numeroProfesoresAusentes==0){ //no hay ausencias
        
         $html =$html.' <tr id="'.$hora.'" class="fila2">';
          $html =$html.' <td class="fila2 numeroHora"><span class="hora">'.$hora.'</span></td>';
          $html =$html.' <td class="fila2 sinAusencias campoDocente" colspan="6">SIN AUSENCIAS</td>';
          $html =$html.' <td class="celda2final" ></td>';
         $html =$html.'</tr>';
    }
    
    else{ //hay ausencias
       
        for($num_ausencia=0;$num_ausencia<$num_ausencias;$num_ausencia++){
  
           $estiloFila="";
           if($num_fila==$numeroProfesoresAusentes) //última fila, último tr
           {
                $estiloFila="fila2";
           }
           
           $html =$html.' <tr id="'.$hora.'" class="'.$estiloFila.'">';
            
            if($num_fila==1) //primera fila, primer tr
           {
              $html =$html.' <td rowspan="'.$numeroProfesoresAusentes.'" class="fila2 numeroHora"><div class="hora" style="margin-top:'.$numeroProfesoresAusentes.'em">'.$hora.'</div><p></p></td>';
           }  
        
            $estiloDocente="ausenciaSinGuardia";
            $estiloGrupo = "textoAusenciaSinGuardia"; 
            $estiloAula= "textoAusenciaSinGuardia"; 
            
            if($ausencias[$num_ausencia]->getIdProfGuardia()!=NULL && $ausencias[$num_ausencia]->getIdProfGuardia()!=0 ){
               $estiloDocente = "ausenciaConGuardia"; 
                $estiloGrupo = "textoAusenciaConGuardia"; 
                 $estiloAula= "textoAusenciaConGuardia"; 
            }
         
            $html =$html.'<td class="celda2 datosAusencia campoDatosLargo campoDocente" data-idAusencia="'.$ausencias[$num_ausencia]->getId().'" ><div id="'.$ausencias[$num_ausencia]->getId().'"  class="'.$estiloDocente.'">'.$ausencias[$num_ausencia]->getNombreDocente().'</div></td>';
        
            $html =$html.'<td id="grupo_'.$ausencias[$num_ausencia]->getId().'" class="celda2 '.$estiloGrupo.' campoDatosAusencia">'.$ausencias[$num_ausencia]->getGrupo().'</td>';
            
            
            $html =$html.'<td id="aula_'.$ausencias[$num_ausencia]->getId().'" class="celda2 '.$estiloAula.' campoDatosAusencia"> '.$ausencias[$num_ausencia]->getAula().'</td>';
            
            $iconotarea='../imagenes/tarea.png';
    
            if($ausencias[$num_ausencia]->getTarea()==1){
             $html = $html.'<td class="celda2 campoDatosCorto"><img src="'.$iconotarea.'" width="30px" height="30px"/></td>';
            }else{
             $html =$html.'<td class="celda2 campoDatosCorto"></td>';
            }
            
            $html =$html.'<td class="celda2 campoDatosLargo observaciones">'.$ausencias[$num_ausencia]->getObservaciones().'</td>';
            $html =$html.'<td class="celda2 campoDatosLargo">'.mostrarDocentesEnGuardia($ausencias[$num_ausencia]->getDia(),$hora,$ausencias[$num_ausencia]->getId(),$ausencias[$num_ausencia]->getIdProfGuardia()).'</td>';
           

            if($num_fila==1) //primera fila, primer tr
            {
                $html =$html.'<td  rowspan="'.$numeroProfesoresAusentes.'" class="celda2final campoDatosCorto"><div style="margin-top:'.($numeroProfesoresAusentes-1).'em"><a class="botonListaGuardias"  href="listarGuardiasHechas.php?semana='.$semana.'&fecha='.$fecha.'&dia='.$dia.'&hora='.$hora.'"><img src="../imagenes/icono_abacus.png" class="icono_abacus"></img></a></div></td>';

            }  
           
/*
            if($num_fila==($num_ausencias-1)) //ultima celda, es la que muestra el boton
            {
                $html =$html.'<td  rowspan="'.$numeroProfesoresAusentes.'" class="celda2final campoDatosCorto"><a class="botonListaGuardias" href="listarGuardiasHechas.php?semana='.$semana.'&fecha='.$fecha.'&dia='.$dia.'&hora='.$hora.'"><i class="glyphicon glyphicon-equalizer" style="font-size:36px"></i></a></td>';

            }  
            if($num_ausencias==1) //ultima celda, es la que muestra el boton
            {
                $html =$html.'<td rowspan="'.$numeroProfesoresAusentes.'" class="celda2final campoDatosCorto"><a class="botonListaGuardias" href="listarGuardiasHechas.php?semana='.$semana.'&fecha='.$fecha.'&dia='.$dia.'&hora='.$hora.'"><i class="glyphicon glyphicon-equalizer" style="font-size:36px"></i></a></td>';

            } */ 
            $html =$html.'</tr>';
        
            $num_fila++;
        
    }//fin for

    
    }
    
    
    
    return $html;
}


function mostrarDocentesEnGuardia($dia,$hora,$idAusencia,$idDocenteGuardia){
    

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
               $html = $html.'<div><select class="selectorDocenteGuardia select" name="docenteGuardia" id="docenteGuardia" data-idausencia="'.$idAusencia.'">
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


?>

<script>
    
$(document).ready(function(){
  $(".selectorDocenteGuardia").change(function(){
   
      
        var url = "guardarDocenteGuardia.php";   
        console.info('selecciona docente de guardia');
      

       var ausencia = $(this).data('idausencia');
       var docenteGuardia = $(this).val();
      
       console.info(ausencia);
       console.info(docenteGuardia);
        
       var valores = {"ausencia":ausencia,
                              "docenteGuardia":docenteGuardia
                             };

    $.ajax({                        
       type: "POST",                 
       url: url,                    
       data: valores,
       success: function(data)            
       {

         elemento = '#'+ausencia;
         grupo = '#grupo_'+ausencia;
        aula = '#aula_'+ausencia;
           
           
         if(docenteGuardia!=0){   
         //if ($(elemento).hasClass('ausenciaSinGuardia')){ //Si está marcado ausencia sin guardia
                         
                         $(elemento).addClass("ausenciaConGuardia");
                         $(elemento).removeClass("ausenciaSinGuardia");
                         $(grupo).addClass("textoAusenciaConGuardia");
                         $(grupo).removeClass("textoAusenciaSinGuardia");
                          $(aula).addClass("textoAusenciaConGuardia");
                         $(aula).removeClass("textoAusenciaSinGuardia");
                        
         }
           
         else{ //Si está marcado ausencia sin guardia
                        $(elemento).addClass("ausenciaSinGuardia");
                         $(elemento).removeClass("ausenciaConGuardia");
                       $(grupo).addClass("textoAusenciaSinGuardia");
                         $(grupo).removeClass("textoAusenciaConGuardia");
                         $(aula).addClass("textoAusenciaSinGuardia");
                         $(aula).removeClass("textoAusenciaConGuardia");
               
                    }   
           
         }   
     });
  });
});


</script>


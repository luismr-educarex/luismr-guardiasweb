

<?php

require_once '../comun/cabecera.php';
require_once 'AusenciasDAO.php';
require_once 'Guardias.php';
require_once '../comun/fechas.php';
require_once '../DTO/HorasSemanasDocente.php';
require_once '../docente/DocenteDAO.php';


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
$contenido = mostrarCuadranteAusencia($listaAusencias,$fecha);
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



function mostrarCuadranteAusencia($lista,$fecha){

    
     $listaAusencias = obtenerAusenciasPorHora($lista);
    
     $html='
    <div class="container bloque_contenido">
      <div class="horario">
    <div class="row">
    <div class="col-sm-4 docente">
      DIA:'.$fecha.'
    </div> 
    <div class="col-sm-8">    

</div>
    </div>
    <div class="row">
        <div class="col-sm-1 bg-celda-info celdaTituloH">H</div>
        <div class="col-sm-2 bg-celda-info"> DOCENTE</div>
        <div class="col-sm-2 bg-celda-info"> GRUPO</div>
        <div class="col-sm-2 bg-celda-info"> AULA </div>
        <div class="col-sm-1 bg-celda-info"> TAREA </div>
        <div class="col-sm-2 bg-celda-info"> OBSERVACIONES</div>  
        <div class="col-sm-2 bg-celda-info">GUARDIAS </div>
    </div>'; 
    $numeroProfesoresAusentes=sizeof($listaAusencias);
    for($hora=1;$hora<7;$hora++){ 
           $html =$html.'<div class="row">';
              $html =$html.'<div class="col-sm-1 bg-celda-info celdaHora">'.$hora.'</div>';
              $html =$html.'<div class="col-sm-11 celda_ausencia_cierre">';
              $html =$html.'<div class="row filaAusencia">';
              $html =$html.imprimirFila($hora,$listaAusencias);
              $html =$html.'</div>';
              $html =$html.'</div>';
           $html =$html.'</div>';
    }
          
    $html =$html.'  </div></div>';
    
    return $html;
}



function imprimirFila($hora,$listaAusencias){    
    
    $ausencias = $listaAusencias[$hora];
    $num_ausencias=sizeof($ausencias);
    
    $html="";
    
    for($num_ausencia=0;$num_ausencia<$num_ausencias;$num_ausencia++){
  
            $html =$html.'<div class="col-sm-2 datosAusencia" data-idAusencia="'.$ausencias[$num_ausencia]->getId().'"><div class="elemento">'.$ausencias[$num_ausencia]->getNombreDocente().'</div></div>';
            $html =$html.'<div class="col-sm-2 ">'.$ausencias[$num_ausencia]->getGrupo().'</div>';
            $html =$html.'<div class="col-sm-2 "> '.$ausencias[$num_ausencia]->getAula().'</div>';
            
            $iconotarea='../imagenes/icono_tarea2.png';
    
            if($ausencias[$num_ausencia]->getTarea()==1){
             $html = $html.'<div class="col-sm-2 "><img src="'.$iconotarea.'" width="20px" height="20px"/></div>';
            }else{
             $html =$html.'<div class="col-sm-2 "></div>';
            }
            
            $html =$html.'<div class="col-sm-2 "> '.$ausencias[$num_ausencia]->getObservaciones().'</div>';
            $html =$html.'<div class="col-sm-2 "> '.mostrarDocentesEnGuardia($ausencias[$num_ausencia]->getDia(),$hora,$ausencias[$num_ausencia]->getId(),$ausencias[$num_ausencia]->getIdProfGuardia()).'</div>';
        
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
               $html = $html.'<div><select class="selectorDocenteGuardia" name="docenteGuardia" id="docenteGuardia" data-idausencia="'.$idAusencia.'">
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
       console.info($(this).val());
        
       var valores = {"ausencia":ausencia,
                              "docenteGuardia":docenteGuardia
                             };
    $.ajax({                        
       type: "POST",                 
       url: url,                    
       data: valores,
       success: function(data)            
       {
         $('#resp').html(data);           
       }
     });
  });
});


</script>
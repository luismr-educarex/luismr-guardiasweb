<?php

require_once '../comun/cabecera.php';
require_once '../comun/fechas.php';
require_once '../comun/funciones.php';


$fecha = new DateTime(); 
$semana= obtenerNumeroSemana($fecha);
$semanaDias = obtenerVectorDiasSemana();
$semanaAnteriorDias = obtenerVectorDiasSemanaAnterior();

$num_dias=sizeof($semanaDias);
  

   echo '<div class="container bloque_contenido">
   <div class="horario">';


 echo  '<div class="row">';
    for($num_dia=0;$num_dia<$num_dias;$num_dia++){
        
        echo  '<div class="col-sm-2 bg-celda-info"><a href="../ausencias/listarAusenciasGuardias3.php?semana='.($semana-1).'&dia='.($num_dia+1).'&fecha='.$semanaAnteriorDias[$num_dia].'"> <div class="button button2" data-semana='.($semana-1).' data-dia='.($num_dia+1).'> '.$semanaAnteriorDias[$num_dia].'</div></a></div>';
        
    }
    
  echo'</div>';
   
   echo  '<div class="row">';
    for($num_dia=0;$num_dia<$num_dias;$num_dia++){
        
        $tipoBoton = "button2";
        if(strtotime($semanaDias[$num_dia]) == strtotime(date("Y-m-d"))){
            $tipoBoton = "button3";
        }
        
         echo  '<div class="col-sm-2 bg-celda-info"><a href="../ausencias/listarAusenciasGuardias3.php?semana='.$semana.'&dia='.($num_dia+1).'&fecha='.$semanaDias[$num_dia].'"> <div class="button '.$tipoBoton.'" data-semana='.$semana.' data-dia='.($num_dia+1).'> '.$semanaDias[$num_dia].'</div></a></div>';
        
        
    }
    
  echo'</div>';

 



  echo'</div></div>';



?>

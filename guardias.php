<?php 

require_once 'cabeceraTablon.php';

?>

<div class="container bloque_contenido">
      <div class="horario">
    <div class="row">
    <div class="col-sm-12 docente">
          <?php  ?>
    </div>
    
    </div>
    <div class="row">
      <div class="col-sm-2 bg-celda-info celdaTituloH">H</div>
        <div class="col-sm-2 bg-celda-info">AUSENCIA</div> 
        <div class="col-sm-2 bg-celda-info">GRUPO</div>
        <div class="col-sm-2 bg-celda-info">AULA</div>
        <div class="col-sm-2 bg-celda-info">TAREAS/OBSERVACIONES</div>
        <div class="col-sm-2 bg-celda-info">GUARDIAS</div>  
    </div>
    
 <?php 
   $html = "";
    for($i=1;$i<=6;$i++){
        
        $html = $html.'<div class="row">';
         
         for($j=1;$j<=6;$j++){
             
             if($j==1){
                 $html =$html.'<div class="col-sm-2 bg-celda-info celdaHora">'.$i.'  </div>';
      
             }else{
                 $html = $html.'<div class="col-sm-2 celda horaDocencia" id="celda_'.$i.'_'.$j.'" data-dia="" data-hora="'.$i.'">
                        <div class="datos_hora"></div>
                        <div class="contenido_hora" id="hora_'.$j.'" data-dia="'.$i.'" data-hora="'.$j.'">
                             <div class="texto_observacion" id="texto_'.$i.'_'.$j.'">
                                 <div class="observacion" id="observaciones_'.$i.'_'.$j.'" data-dia="'.$i.'" data-hora="'.$j.'">
                                     </div>
                            </div>   
                        </div> 
                  </div>';
             }   
         }

        $html = $html.'</div>'; 
        
    }
          
    echo $html;
    

?>
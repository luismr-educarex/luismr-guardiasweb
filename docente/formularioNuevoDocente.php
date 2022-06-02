<?php
require_once '../comun/cabecera.php';

echo mostrarHorariosDocentes();

function mostrarHorariosDocentes(){
    
     
     
    $html='
    <form class="form-inline" action="editarDocente.php" method="POST">
     
    <div class="horario">

     
    <div class="row">
    <div class="col-sm-10" ></div>
    <div class="d-flex">
    <span class="textoDatosDocente"> Docente</span> 
<input type="input" size="50" class="form-control" id="nombre" name="nombre" >
        <button type="submit" class="btn btn-info">Grabar</button>
    </div>
     <div class="col-sm-1">
      
     
    
      
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

      
           for($hora=1;$hora<7;$hora++){
               
             $html =$html.'<div class="row">';
             $html =$html.'<div class="col-sm-2 bg-celda-info celdaHora">'.$hora.'</div>';

             for($dia=1;$dia<=5;$dia++){
                $html =$html.''.construirHTML_hora($dia,$hora);
             }
            
             $html =$html.'</div>';
           }
        
          

  $html =$html.'  </div>';
  $html =$html.'</form>';
    
    return $html;
}


//Rrecibe la semana y un objeto de tipo HorasSemanasDocente con la variable $info
function construirHTML_hora($dia,$hora){
    
 
  
   

    $html='<div class="col-sm-2 celda " id="celda_'.$dia.'_'.$hora.'"   
    data-dia="'.$dia.'" 
    data-hora="'.$hora.'">
                <input type="hidden" class="campo_edicion_horario" name="id_'.$dia.'_'.$hora.'" id="id_'.$dia.'_'.$hora.'"  />
                <input type="text" placeholder="materia" class="campo_edicion_horario" name="materia_'.$dia.'_'.$hora.'" id="materia_'.$dia.'_'.$hora.'"  />
                <input type="text" placeholder="aula" class="campo_edicion_horario" name="aula_'.$dia.'_'.$hora.'" id="aula_'.$dia.'_'.$hora.'"  />
                <input type="text" placeholder="grupo" class="campo_edicion_horario" name="grupos_'.$dia.'_'.$hora.'" id="grupos_'.$dia.'_'.$hora.'"  />
                 
                
            </div>';
    
    
    return $html;
    
}



?>
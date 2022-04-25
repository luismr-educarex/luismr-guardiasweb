<head>
  <title>GUARDIAS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    
<style>
   
   </style>    
    
</head>
<?php

  require_once 'cabecera.php';
?>
<script>
        
var horario, x;
horario = {
  "LUNES":[ 0,0,0,0,0,0 ],
   "MARTES":[ 0,0,0,0,0,0 ],
    "MIERCOLES":[ 0,0,0,0,0,0 ],
     "JUEVES":[ 0,0,0,0,0,0 ],
      "VIERNES":[ 0,0,0,0,0,0 ]
};
//x = horario.LUNES[2];        
var dias = ["LUNES","MARTES","MIERCOLES","JUEVES","VIERNES"];       
        
$(document).ready(function(){
    
            $('.icono_tarea').hide();
            $('.celda').on("click",function(){
                var dia = $(this).data('dia');
                var hora = $(this).data('hora');
               

                celda=this.id;
                id="#icono_tarea_"+dia+"_"+hora;
                 if (!$(this).hasClass('horaLibre')){ 
                     if (!$(this).hasClass('ausencia')){ // si no está activa
                        $(this).addClass('ausencia'); 
                        $(id).show(); 
                        registrarAusencia(dia,hora-1,1);
                    }else{
                        $(this).removeClass('ausencia'); // se activa la celda
                        $(id).hide();
                        registrarAusencia(dia,hora-1,0);
                    }
                
                 }
               
                
                console.log(horario);
            });
    
            $('.boton_seleccion_todo').on("click",function(){
                var dia = $(this).data('dia');
                
                if (!$(this).hasClass('todoSeleccion')){ 
                     registrarTodoDiaAusencia(dia);
                      $(this).addClass('todoSeleccion'); 
                }
                else{
                     anularTodoDiaAusencia(dia);
                     $(this).removeClass('todoSeleccion'); 
                }
               
                console.log(horario);
                
            });
    
    
            $('.icono_tarea').on("click",function(){
                
                var dia = $(this).parent().data('dia');
                var hora = $(this).parent().data('hora');
                id_imagen='#imagen_'+dia+'_'+hora;
                
                if (!$(id_imagen).hasClass('sin_tarea')){ 
                    alert('entra1');
                     $(id_imagen).attr('src', 'imagenes/icono_sin_tarea.png');
                     $(this).addClass('sin_tarea'); 
                }
                else{
                    alert('entra2');
                     $(id_imagen).attr('src', 'imagenes/icono_tarea2.png');
                     $(this).removeClass('sin_tarea'); 
                 
                }
               
                console.log(horario);
                
            });
    
    /*
        $(".observacion").click(function() {
             var dia = $(this).data('dia');
             var hora = $(this).data('hora');
             id="#observaciones_"+dia+"_"+hora;
             alert(id);
             contenido = $(id).html();
            $("#text-edit").val(contenido);
     
            
         $("#edit-popup").dialog({
                height: 200,
                width: 500,
                modal: true,
                buttons: {
                    "Aceptar": function () {
                    nuevoContenido = $("#text-edit").val();
                        alert(id);
                $(id).html(nuevoContenido);
                        $(this).dialog("close");
                }
            }
        });
});*/
    
    
    
    
    
});
        function registrarTodoDiaAusencia(dia){
            
            for(hora=0;hora<6;hora++){ 
                 id_celda="#celda_"+dia+"_"+(hora+1);
                 if(!$(id_celda).hasClass('horaLibre')){
                     horario[dias[dia-1]][hora]=1;
                     $(id_celda).addClass('ausencia'); 
                     id_icono="#icono_tarea_"+dia+"_"+(hora+1);
                     $(id_icono).show(); 
                 }
               
            }
        }
        
        function anularTodoDiaAusencia(dia){
        
            for(hora=0;hora<6;hora++){   
                 id_celda="#celda_"+dia+"_"+(hora+1);
                 if($(id_celda).hasClass('ausencia')){
                     horario[dias[dia-1]][hora]=0;
                     $(id_celda).removeClass('ausencia'); 
                     id_icono="#icono_tarea_"+dia+"_"+(hora+1);
                     $(id_icono).hide(); 
                 }
               
            }
        }
        
        
        function registrarAusencia(dia,hora,ausencia){
            
            id_celda="#celda_"+dia+"_"+(hora+1);
            if(!$(id_celda).hasClass('horaLibre')){
                switch(dia){
                    case 1:
                           horario.LUNES[hora]=ausencia;
                        break;
                    case 2:
                           horario.MARTES[hora]=ausencia;
                        break;
                    case 3:
                           horario.MIERCOLES[hora]=ausencia;
                        break;
                    case 4:
                           horario.JUEVES[hora]=ausencia;
                        break;
                    case 5:
                           horario.VIERNES[hora]=ausencia;
                        break;
                }
            }
            
        }
        
    </script>
<?php

  require_once 'cabecera.php';
  require('./bd/conexion.php');

  if(isset($_GET["docente"]))
      $id_docente = $_GET["docente"]; 
  
  $sql_recuperar_horario="SELECT dia,hora,materia,grupo,aula,nombre FROM horario h INNER JOIN docente d ON h.idProfesor=d.id WHERE d.id=".$id_docente; 

  $sql_docente="SELECT * FROM docente WHERE id=".$id_docente; 
    
    
  $horas_docente = mysqli_query($connection,$sql_recuperar_horario) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_recuperar_horario);
  $resultado_consulta_docente = mysqli_query($connection,$sql_docente) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_docente);
  $datos_docente = $resultado_consulta_docente->fetch_assoc();   
  $docente =  $datos_docente['nombre'];

$datos = array();

if ($horas_docente->num_rows > 0) {
    while($hora = $horas_docente->fetch_assoc()) {       
        $datos []= $hora;  
    }
    
} else {
    echo "0 results";
}
  
  $codigo_html = mostrarHorariosDocentes(obtenerHorario($datos),$docente);


  echo $codigo_html;
  echo ' <div id="edit-popup" style="display: none">
                <textarea id="text-edit" style="width:100%;height:100%"/>
            </div>';

  mysqli_close($connection);

function obtenerHorario($lista_horas){
    
    
     $horas = array();
   
     $horas["LUNES"]=recuperaHoras($lista_horas,1);
     $horas["MARTES"]=recuperaHoras($lista_horas,2);
     $horas["MIERCOLES"]=recuperaHoras($lista_horas,3);
     $horas["JUEVES"]=recuperaHoras($lista_horas,4);
     $horas["VIERNES"]=recuperaHoras($lista_horas,5);
    
    return $horas;
    
}

function recuperaHoras($lista,$dia){
    
    $clases=array();
    $hora=1;
    $num_clases=sizeof($lista);
    $contador=0;
    for($num_clase=0;$num_clase<$num_clases;$num_clase++){
        if($lista[$num_clase]['dia']==$dia){
            $clases[$hora]=$lista[$num_clase]['materia'];
            $hora++;
        }
    }

    return $clases;
    
}


function mostrarHorariosDocentes($horario,$docente){
    
    $html='
    <div class="container bloque_contenido">
      <div class="horario">
    <div class="row">
    <div class="col-sm-4 docente">
      '.$docente.'
    </div>
    <div class="col-sm-4">
    <a href="listarDocentes.php" class="btn btn-primary btn-volver" role="button">REGISTRAR AUSENCIAS</a>
    </div>
     <div class="col-sm-4">
      
     
    
       <a href="listarDocentes.php" class="btn btn-warning btn-volver" role="button">Volver </a>
</div>
    </div>
    <div class="row">
      <div class="col-sm-2 bg-celda-info celdaTituloH">H</div>
       <div class="col-sm-2 bg-celda-info">
                LUNES 
                <img class="boton_seleccion_todo" data-dia="1" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
       </div> 
        <div class="col-sm-2 bg-celda-info">MARTES
              <img class="boton_seleccion_todo" data-dia="2" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
        </div>
         <div class="col-sm-2 bg-celda-info">MIERCOLES
              <img class="boton_seleccion_todo" data-dia="3" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
         
         </div>
          <div class="col-sm-2 bg-celda-info">JUEVES
                  <img class="boton_seleccion_todo" data-dia="4" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
          </div>
           <div class="col-sm-2 bg-celda-info">VIERNES
                  <img class="boton_seleccion_todo" data-dia="5" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
           </div>  
    </div>'; 

            $lunes=$horario['LUNES'];
            $martes=$horario['MARTES'];
            $miercoles=$horario['MIERCOLES'];
            $jueves=$horario['JUEVES'];
            $viernes=$horario['VIERNES'];
           
           for($i=1;$i<7;$i++){
               
               $html =$html.'<div class="row">';
               $html =$html.'<div class="col-sm-2 bg-celda-info celdaHora">'.$i.'</div>';
        
               $html =$html.''.construirHTML_hora(1,$i,$lunes[$i]);
                $html =$html.''.construirHTML_hora(2,$i,$martes[$i]);
                $html =$html.''.construirHTML_hora(3,$i,$miercoles[$i]);
                $html =$html.''.construirHTML_hora(4,$i,$jueves[$i]);
                $html =$html.''.construirHTML_hora(5,$i,$viernes[$i]);
               $html =$html.'</div>';
           }
        
  $html =$html.'  </div></div>';
    
    return $html;
}
function construirHTML_hora($dia,$hora,$contenido_hora){
    
    if($contenido_hora=='LIBRE'){
        $estiloHora='horaLibre';
        $contenido_hora='';
    }else if($contenido_hora=='GUARDIA'){
         $estiloHora='horaGuardia';
    }
    else{
        $estiloHora='horaDocencia';
    }
    
    $html='<div class="col-sm-2 celda '.$estiloHora.'" id="celda_'.$dia.'_'.$hora.'"  data-dia="'.$dia.'" data-hora="'.$hora.'">'.$contenido_hora.'
    <div class="icono_tarea" id="icono_tarea_'.$dia.'_'.$hora.'">
      <img id="imagen_'.$dia.'_'.$hora.'" class="sin_tarea" src="imagenes/icono_sin_tarea.png" width="40px" height="40px"/>
    </div>
    <div class="observacion" id="observaciones_'.$dia.'_'.$hora.'" data-dia="'.$dia.'" data-hora="'.$hora.'">
    </div>
    </div>';
    
    return $html;
    
}

?>
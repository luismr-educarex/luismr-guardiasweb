<head>
  <title>GUARDIAS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    
<style>
   
   </style>    
    
</head>
<?php

  require_once 'cabecera.php';
  require_once 'fechas.php';

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

tareas = {
  "LUNES":[ 0,0,0,0,0,0 ],
   "MARTES":[ 0,0,0,0,0,0 ],
    "MIERCOLES":[ 0,0,0,0,0,0 ],
     "JUEVES":[ 0,0,0,0,0,0 ],
      "VIERNES":[ 0,0,0,0,0,0 ]
};
    
observaciones = {
  "LUNES":[ 0,0,0,0,0,0 ],
   "MARTES":[ 0,0,0,0,0,0 ],
    "MIERCOLES":[ 0,0,0,0,0,0 ],
     "JUEVES":[ 0,0,0,0,0,0 ],
      "VIERNES":[ 0,0,0,0,0,0 ]
};
//x = horario.LUNES[2];        
var dias = ["LUNES","MARTES","MIERCOLES","JUEVES","VIERNES"];       
        
$(document).ready(function(){
    
    //$('.opciones').hide();
    
/*Función que se ejecuta al hacer click sobre una celda
Debe de aparecer los iconos de tarea y observaciones.
Además cambia el color del fondo de la celda para indicar que hay ausencia en esa hora.
Las horas que están libres no se hace nada aunque se haga click sobre ellas.
*/
    $("div[id^='hora']").on("click",function(){
                var dia = $(this).data('dia');
                var hora = $(this).data('hora');
        
                var semana  =  $('.horario').data('semana');
                var docente  =  $('.docente').data('docente');

                console.info(semana); 
                console.info(docente); 
                icono_tarea="#icono_tarea_"+dia+"_"+hora;
                opciones_hora="#opciones_"+dia+"_"+hora;
                console.info(opciones_hora);
                
                 if (!$(this).parent().hasClass('horaLibre')){ //Si no es hora libre = hora lectiva
                     
                     if (!$(this).parent().hasClass('ausencia')){ //Si no está marcado como ausencia
                         $(this).parent().addClass('ausencia'); // set a ausencia
                         //$(opciones_hora).show();  //mostramos opciones de ausencia:tarea y observaciones
                         $(opciones_hora).addClass("hayTareas");
                         $(opciones_hora).removeClass("sinOpciones");
                         registrarAusencia(docente,semana,dia,hora-1,1);
                    }else{
                        $(this).parent().removeClass('ausencia'); // quitamos ausencia
                        //$(opciones_hora).hide();  // oculatamos opciones de ausencia
                        $(opciones_hora).removeClass("hayTareas");
                        $(opciones_hora).addClass("sinOpciones");
                        //registrarAusencia(docente,semana,dia,hora-1,0);
                        eliminarDatosDeAusencia(docente,semana,dia,hora-1);
                        id_imagen='#imagen_tarea_'+dia+'_'+hora;
                        icono='#icono_tarea_'+dia+'_'+hora;
                        cambiarIconoNoHayTarea(icono,id_imagen);
               
                    }
                
                 } // fin if comprobar hora libre
                 
                console.log(horario);
        
    }); // fin click en div hora_d_h donde d es el dia y h la hora
    
    $('.boton_seleccion_todo').on("click",function(){
                var dia = $(this).data('dia');
                var semana  =  $('.horario').data('semana');
                var docente  =  $('.docente').data('docente');
                var fecha= $('.docente').data('fecha');
        
                
                if (!$(this).hasClass('todoSeleccion')){ 
                     registrarTodoDiaAusencia(docente,semana,dia);
                     $(this).addClass('todoSeleccion'); 
                }
                else{
                     anularTodoDiaAusencia(docente,semana,dia);
                     $(this).removeClass('todoSeleccion'); 
                }
               
                console.log(horario);
                
            });
    
  /* Función que al hacer click sobre el icono de tarea 
  comprueba si hay o no tarea y en función de ello cambia el icono 
  para indicar lo deseado.
  */  
    $('.icono_tarea').on("click",function(event){
                
                var dia = $(this).parent().parent().data('dia');
                var hora = $(this).parent().parent().data('hora');
        
                var semana  =  $('.horario').data('semana');
                var docente  =  $('.docente').data('docente');
        
                id_imagen='#imagen_tarea_'+dia+'_'+hora;
                icono='#icono_tarea_'+dia+'_'+hora;
                
                if ($(this).hasClass('sin_tarea')){ 
                    cambiarIconoHayTarea(icono,id_imagen);
                    guardar_tarea(docente,semana,dia,hora);
                }
                else{
                    cambiarIconoNoHayTarea(icono,id_imagen);
                    borrar_tarea(docente,semana,dia,hora);
                }
                //OBLIGATORIO:Esto se debe de hacer para que el div que está debajo, no capture el evento click que se hace sobre el icono de tarea
                event.stopPropagation();
                console.log(horario);
                
    }); // fin click en icono tarea
       

  $(".icono_obsevaciones").click(function(event) {

            console.info("Entra en observaciones");
             
             var dia = $(this).data('dia');
             var hora = $(this).data('hora');
             
            id_celda="#celda_"+dia+"_"+hora;
            id_observacion="#observaciones_"+dia+"_"+hora;
         
            $("#formObservaciones #dia").val(dia);
            $("#formObservaciones #hora").val(hora);
    
            contenido = $(id_observacion).html();
            $("#formObservaciones #campo_observaciones").val(contenido);
    
            
            //Se convierte el div en editable para que se pueda introducir texto
            // y recoger las observaciones para esa guardia.
            //$(id_observacion).attr('contenteditable','true');
            

  }); // fin click sobre icono observaciones
    
    
}); // fin de ready del documento

    
function cambiarIconoHayTarea(icono,imagen){      
         $(imagen).attr('src', 'imagenes/icono_tarea2.png');
         $(icono).removeClass('sin_tarea'); 
}
    
function cambiarIconoNoHayTarea(icono,imagen){ 
          $(imagen).attr('src', 'imagenes/icono_sin_tarea.png');
          $(icono).addClass('sin_tarea');  
}
       
function cambiarIconoHayObservaciones(imagen){  
         $(imagen).attr('src', 'imagenes/icono_observaciones.png');
}

function cambiarIconoNoHayObservaciones(imagen){
         $(imagen).attr('src', 'imagenes/icono_sin_observaciones.png');  
}
    
function registrarTodoDiaAusencia(docente,semana,dia){
            console.info("se ha seleccionado registrar ausencia en todas las horas del día");
            for(hora=0;hora<6;hora++){ 
                 id_celda="#celda_"+dia+"_"+(hora+1);
                 if(!$(id_celda).hasClass('horaLibre')){
                     horario[dias[dia-1]][hora]=1;
                     guardar_ausencia(docente,semana,dia,hora+1);
                     $(id_celda).addClass('ausencia'); 
                     //id_icono="#icono_tarea_"+dia+"_"+(hora+1);
                     opciones_hora="#opciones_"+dia+"_"+(hora+1);
                     $(opciones_hora).show(); 
                     console.info(opciones_hora);
                 }
               
            }
        }
        
function anularTodoDiaAusencia(docente,semana,dia){
           console.info("se ha seleccionado anular ausencia en todas las horas del día");
            for(hora=0;hora<6;hora++){   
                 id_celda="#celda_"+dia+"_"+(hora+1);
                 if($(id_celda).hasClass('ausencia')){
                     //actualizamos en el array horario el día y hora
                     horario[dias[dia-1]][hora]=0;
                     borrar_ausencia(docente,semana,dia,hora+1);
                     $(id_celda).removeClass('ausencia'); 
                     //id_icono="#icono_tarea_"+dia+"_"+(hora+1);
                     opciones_hora="#opciones_"+dia+"_"+(hora+1);
                     $(opciones_hora).hide();  
                     console.info(opciones_hora);
                 }
               
            }
}

    
    
function eliminarDatosDeAusencia(docente,semana,dia,hora){
    
    observaciones = '#observaciones_'+dia+'_'+hora;  
    imagen_tarea='#imagen_tarea_'+dia+'_'+hora;
    icono='#icono_tarea_'+dia+'_'+hora;
    imagen_observaciones='#imagen_observacion_'+dia+'_'+hora;
    $(observaciones).html('');
    cambiarIconoNoHayTarea(icono,imagen_tarea);
    cambiarIconoNoHayObservaciones(imagen_observaciones);
    borrar_ausencia(docente,semana,dia,hora+1);
}

// Función que se ejecuta al cerrar la modal para introducir las observaciones le la hora de guardia. Se recupera las observaciones, el dia y la hora. Estos dos últimos son campos ocultos que se introducen cuando se abre la modal.  
// Se actualiza la capa observaciones_d_h (d=dia,h=hora) con el texto introducido en la modal.
//Si hay texto en las observaciones se debe cambiar el icono de observaciones para que refleje que hay observaciones.
function escribirObservacion() {

        var observacion = $('#campo_observaciones').val();
        var dia = $('#dia').val();
        var hora = $('#hora').val();
    
        var semana  =  $('.horario').data('semana');
        var docente  =  $('.docente').data('docente');

        //limpiamos campos y cerramos modal.
        $('#campo_observaciones').val('');
        $('#dia').val('');
        $('#hora').val('');
        $('#modal_observaciones').modal('hide');

        observaciones = '#observaciones_'+dia+'_'+hora;

        $(observaciones).html(observacion);

        imagen='#imagen_observacion_'+dia+'_'+hora;
        if(observacion!=''){
            cambiarIconoHayObservaciones(imagen);
            guardar_observacion(docente,semana,dia,hora,observacion);

        }else{
            cambiarIconoNoHayObservaciones(imagen);
            borrar_observacion(docente,semana,dia,hora);
        }

        $('.submitBtn').removeAttr("disabled");
        $('.modal-body').css('opacity', '');
            

}
    
    

        
        
function registrarAusencia(docente,semana,dia,hora,ausencia,tarea,observaciones){
            
            id_celda="#celda_"+dia+"_"+(hora+1);
            if(!$(id_celda).hasClass('horaLibre')){
                switch(dia){
                    case 1:
                        horario.LUNES[hora]=ausencia;
                         tareas.LUNES[hora]=tarea;
                         //observaciones.LUNES[hora]=observaciones;
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
    
            guardar_ausencia(docente,semana,dia,hora+1);
            
}
     
function guardar_ausencia(docente,semana,dia,hora){

        
               var valores = {"profesor":docente,
                              "semana":semana,
                              "dia":dia,
                              "hora":hora
                             };

                //var contloader=document.getElementById('contenedorMensajes');
                //var mostrarResultado = document.getElementById('resultado');

                $.ajax({
                    url: "./ausencias/guardarAusencia.php",
                    type: 'POST',
                    dataType: "html",
                    data: valores,
                    beforeSend: function() {
                    },
                    success: function(data,textStatus, xhr) {
                
                       //mostrarResultado.innerHTML(data.text);
                       console.info(data);
                       //contloader.innerHTML ='<div id="msg" class="exito mensajes">RESULTADO GUARDADO CORRECTAMENTE</div>'; 
                       //document.getElementById('msg').style.display = 'block'; 
                       //$(".mensajes").fadeOut(3000);  
                       //setTimeout(volverListado,2000);

                    },
                    error: function(xhr, textStatus, errorThrown) {

                          alert( xhr.responseText);
                          alert(textStatus);
                          alert(errorThrown);
                    }
                });

                return false;
            }
    
function guardar_tarea(docente,semana,dia,hora){

        
               var valores = {"profesor":docente,
                              "semana":semana,
                              "dia":dia,
                              "hora":hora
                             };

                $.ajax({
                    url: "./ausencias/guardarTarea.php",
                    type: 'POST',
                    dataType: "html",
                    data: valores,
                    beforeSend: function() {
                      
                    },
                    success: function(data,textStatus, xhr) {
                            
                       console.info(data);
                     
                    },
                    error: function(xhr, textStatus, errorThrown) {

                          alert( xhr.responseText);
                          alert(textStatus);
                          alert(errorThrown);
                          console.info(xhr.responseText);
                    }
                });

                return false;
            }

function guardar_observacion(docente,semana,dia,hora,observaciones){

        
               var valores = {"profesor":docente,
                              "semana":semana,
                              "dia":dia,
                              "hora":hora,
                              "observaciones":observaciones
                             };

                $.ajax({
                    url: "./ausencias/guardarObservacion.php",
                    type: 'POST',
                    dataType: "html",
                    data: valores,
                    beforeSend: function() {
                        console.info(observaciones);
                      
                    },
                    success: function(data,textStatus, xhr) {
                            
                       console.info(data);
                     
                    },
                    error: function(xhr, textStatus, errorThrown) {

                          alert( xhr.responseText);
                          alert(textStatus);
                          alert(errorThrown);
                          console.info(xhr.responseText);
                    }
                });

                return false;
            }

    
function borrar_ausencia(docente,semana,dia,hora){

        
               var valores = {"profesor":docente,
                              "semana":semana,
                              "dia":dia,
                              "hora":hora
                             };


                $.ajax({
                    url: "./ausencias/borrarAusencia.php",
                    type: 'POST',
                    dataType: "html",
                    data: valores,
                    beforeSend: function() {
             
                    },
                    success: function(data,textStatus, xhr) {
                
                       console.info(data);

                    },
                    error: function(xhr, textStatus, errorThrown) {

                          alert( xhr.responseText);
                          alert(textStatus);
                          alert(errorThrown);
                    }
                });

                return false;
            }

function borrar_tarea(docente,semana,dia,hora){

        
               var valores = {"profesor":docente,
                              "semana":semana,
                              "dia":dia,
                              "hora":hora
                             };


                $.ajax({
                    url: "./ausencias/borrarTarea.php",
                    type: 'POST',
                    dataType: "html",
                    data: valores,
                    beforeSend: function() {
             
                    },
                    success: function(data,textStatus, xhr) {
                
                       console.info(data);

                    },
                    error: function(xhr, textStatus, errorThrown) {

                          alert( xhr.responseText);
                          alert(textStatus);
                          alert(errorThrown);
                    }
                });

                return false;
            }
    
    </script>

<!-- Modal -->
  <div class="modal fade" id="modal_observaciones" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title">Observaciones</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form role="form" id="formObservaciones">
          <div class="form-group">
            
              <input type="text" class="form-control" name="campo_observaciones" id="campo_observaciones">
            </div>
            <input type="hidden" name="dia" id="dia" value="" />
            <input type="hidden" name="hora" id="hora" value="" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal" onclick="escribirObservacion()" data-backdrop="false">Aceptar</button>
        </div>
      </div>
      
    </div>
  </div>
    <!-- FIN Modal -->
<?php

  require('./bd/conexion.php');
 require_once './ausencias/Guardias.php';

  if(isset($_GET["docente"]))
      $id_docente = $_GET["docente"]; 

  
  
  $sql_recuperar_horario="SELECT dia,hora,materia,grupo,aula,nombre FROM horario h INNER JOIN docente d ON h.idProfesor=d.id WHERE d.id=".$id_docente; 

  $sql_docente="SELECT * FROM docente WHERE id=".$id_docente; 


 $fecha = new DateTime();
    
 $semana= obtenerNumeroSemana($fecha);
 $sql_recuperar_ausencias="SELECT dia,hora,grupo,aula,idProfesor,nombre,tarea,observaciones FROM guardia g INNER JOIN docente d ON g.idProfesor=d.id WHERE d.id=".$id_docente.' AND g.semana='.$semana; 
    
    
  $horas_docente = mysqli_query($connection,$sql_recuperar_horario) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_recuperar_horario);
  $resultado_consulta_docente = mysqli_query($connection,$sql_docente) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_docente);
  $datos_docente = $resultado_consulta_docente->fetch_assoc();   
  $docente =  $datos_docente['nombre'];

  $horas_ausencias = mysqli_query($connection,$sql_recuperar_ausencias) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_recuperar_ausencias);

$datosHoras = array();

if ($horas_docente->num_rows > 0) {
    while($hora = $horas_docente->fetch_assoc()) {       
        $datosHoras []= $hora;  
    }
    
} 

$datosAusencias = array();
if ($horas_ausencias->num_rows > 0) {
    while($ausencia = $horas_ausencias->fetch_assoc()) {       
        $datosAusencias []= $ausencia;  
    }
    
} 


  $codigo_html = mostrarHorariosDocentes(obtenerHorario($datosHoras),$id_docente,$docente,obtenerAusencias($datosAusencias),obtenerInformacion($datosAusencias));


  echo $codigo_html;
  echo ' <div id="edit-popup" style="display: none">
                <textarea id="text-edit" style="width:100%;height:100%"/>
            </div>';

  mysqli_close($connection);


//Este método crea un array asociativo por días multidimensional. De tal forma que cada día tiene un array de 6 posiciones donde si la posicion vale 0 significa que no hay ausencia y si vale 1 significa que el profesor falta ese día a esa hora.
function obtenerAusencias($lista_ausencias){
     $ausencias = array();
   
     $ausencias["LUNES"]=recuperaAusencias($lista_ausencias,1);
     $ausencias["MARTES"]=recuperaAusencias($lista_ausencias,2);
     $ausencias["MIERCOLES"]=recuperaAusencias($lista_ausencias,3);
     $ausencias["JUEVES"]=recuperaAusencias($lista_ausencias,4);
     $ausencias["VIERNES"]=recuperaAusencias($lista_ausencias,5);
    
    return $ausencias;
}


function obtenerHorario($lista_horas){
    
    
     $horas = array();
   
     $horas["LUNES"]=recuperaHoras($lista_horas,1);
     $horas["MARTES"]=recuperaHoras($lista_horas,2);
     $horas["MIERCOLES"]=recuperaHoras($lista_horas,3);
     $horas["JUEVES"]=recuperaHoras($lista_horas,4);
     $horas["VIERNES"]=recuperaHoras($lista_horas,5);
    
    return $horas;
    
}


function obtenerInformacion($lista_ausencias){
    
    
   $infoausencias = array();
   
    $infoausencias["LUNES"]=recuperaDatos($lista_ausencias,1);
    $infoausencias["MARTES"]=recuperaDatos($lista_ausencias,2);
    $infoausencias["MIERCOLES"]=recuperaDatos($lista_ausencias,3);
    $infoausencias["JUEVES"]=recuperaDatos($lista_ausencias,4);
    $infoausencias["VIERNES"]=recuperaDatos($lista_ausencias,5);
    $infoausencias["VIERNES"]=recuperaDatos($lista_ausencias,6);
    
    
    return $infoausencias;
}

function recuperaHoras($lista,$dia){
    
    $clases=array();
    $hora=1;
    $num_clases=sizeof($lista);
    for($num_clase=0;$num_clase<$num_clases;$num_clase++){
        if($lista[$num_clase]['dia']==$dia){
            $clases[$hora]=$lista[$num_clase]['materia'];
            $hora++;
        }
    }

    return $clases;
    
}


function recuperaDatos($lista,$dia){
    
    $datos=array();
    for($i=0;$i<6;$i++){
      $datos[$i]= new Guardia();
    }
    
     $num_clases=sizeof($lista);
     for($elemento=0;$elemento<$num_clases;$elemento++){
        if($lista[$elemento]['dia']==$dia){
            
             $dia = $lista[$elemento]['dia'];
             $hora = $lista[$elemento]['hora'];
             $tarea = $lista[$elemento]['tarea'];
             $observaciones = $lista[$elemento]['observaciones'];
             $aula = $lista[$elemento]['aula'];
             $grupo = $lista[$elemento]['grupo'];
            
            $datos[$hora-1]->setDia($dia);
            $datos[$hora-1]->setHora($hora);
            $datos[$hora-1]->setTarea($tarea);
            $datos[$hora-1]->setObservaciones($observaciones);
            $datos[$hora-1]->setAula($aula);
            $datos[$hora-1]->setGrupo($grupo);
           

        }
       
    }
    
     return $datos;
    
}

function recuperaAusencias($lista,$dia){
    
    $faltas=array();
    //se inicializa a 0 el valor de todos los elementos del array falta. Por defecto en todas las horas no hay ausencia.
    for($i=0;$i<6;$i++){
      $faltas[$i]=0;
    }

       
    $hora=1;
    $num_clases=sizeof($lista);
 
    //En el array lista solo vienen los días en los que hay ausencias, con lo cual se debe preguntar y modificar el array faltas en la posición correspondiente a 1 en caso de que exista ausencia en el array lista.
    for($elemento=0;$elemento<$num_clases;$elemento++){
        if($lista[$elemento]['dia']==$dia){
            $hora = $lista[$elemento]['hora'];
            //se actualiza el array faltas en los días presentes en el array lista que contiene solamente los día en los que hay ausencia o falta.
            $faltas[$hora-1]=1;

        }
       
    }

    return $faltas;
    
}


function mostrarHorariosDocentes($horario,$id_docente,$docente,$ausencias,$informacion){
    
     $vectorDias = obtenerVectorDiasSemana();  
     $fecha = new DateTime();
     $semana= obtenerNumeroSemana($fecha);
     
    $html='
    <div class="container bloque_contenido">
      <div class="horario" data-semana='.$semana.'>
    <div class="row">
    <div class="col-sm-4 docente" data-docente='.$id_docente.'>
      DOCENTE: '.$docente.'- SEMANA'.$semana.'
    </div>
   
     <div class="col-sm-6">
      
     
    
       <a href="../docente/listarDocentes.php" class="btn btn-warning btn-volver" role="button">Volver </a>
</div>
<div class="col-sm-2">
</div>
    </div>
    <div class="row">
      <div class="col-sm-2 bg-celda-info celdaTituloH">H</div>
       <div class="col-sm-2 bg-celda-info">
                  '.$vectorDias[0].' LUNES 
                <img class="boton_seleccion_todo" data-dia="1" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
       </div> 
        <div class="col-sm-2 bg-celda-info"> '.$vectorDias[1].' MARTES
              <img class="boton_seleccion_todo" data-dia="2" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
        </div>
         <div class="col-sm-2 bg-celda-info"> '.$vectorDias[2].' MIERCOLES
              <img class="boton_seleccion_todo" data-dia="3" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
         
         </div>
          <div class="col-sm-2 bg-celda-info"> '.$vectorDias[3].' JUEVES
                  <img class="boton_seleccion_todo" data-dia="4" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
          </div>
           <div class="col-sm-2 bg-celda-info"> '.$vectorDias[4].' VIERNES
                  <img class="boton_seleccion_todo" data-dia="5" src="imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
           </div>  
    </div>'; 

            $lunes=$horario['LUNES'];
            $martes=$horario['MARTES'];
            $miercoles=$horario['MIERCOLES'];
            $jueves=$horario['JUEVES'];
            $viernes=$horario['VIERNES'];
    
            $ausencialunes=$ausencias['LUNES'];
            $ausenciamartes=$ausencias['MARTES'];
            $ausenciamiercoles=$ausencias['MIERCOLES'];
            $ausenciajueves=$ausencias['JUEVES'];
            $ausenciaviernes=$ausencias['VIERNES'];
    
            $infolunes=$informacion['LUNES'];
            $infomartes=$informacion['MARTES'];
            $infomiercoles=$informacion['MIERCOLES'];
            $infojueves=$informacion['JUEVES'];
            $infoviernes=$informacion['VIERNES'];
            
    
           for($i=1;$i<7;$i++){
               
               $html =$html.'<div class="row">';
               $html =$html.'<div class="col-sm-2 bg-celda-info celdaHora">'.$i.'</div>';
        
               $html =$html.''.construirHTML_hora($vectorDias[0],$semana,1,$i,$lunes[$i],$ausencialunes[$i-1],$infolunes[$i-1]);
                $html =$html.''.construirHTML_hora($vectorDias[1],$semana,2,$i,$martes[$i],$ausenciamartes[$i-1],$infomartes[$i-1]);
                $html =$html.''.construirHTML_hora($vectorDias[2],$semana,3,$i,$miercoles[$i],$ausenciamiercoles[$i-1],$infomiercoles[$i-1]);
                $html =$html.''.construirHTML_hora($vectorDias[3],$semana,4,$i,$jueves[$i],$ausenciajueves[$i-1],$infojueves[$i-1]);
                $html =$html.''.construirHTML_hora($vectorDias[4],$semana,5,$i,$viernes[$i],$ausenciaviernes[$i-1],$infoviernes[$i-1]);
               $html =$html.'</div>';
           }
        
  $html =$html.'  </div></div>';
    
    return $html;
}
function construirHTML_hora($fecha,$semana,$dia,$hora,$contenido_hora,$contenido_ausencia,$info){
   
    $estiloHora='';
    $estiloOpciones = 'sinOpciones';
    
    if($contenido_hora=='LIBRE'){
        $estiloHora='horaLibre';
        $contenido_hora='';
    }else if($contenido_hora=='GUARDIA'){
         $estiloHora='horaGuardia';
         if($contenido_ausencia==1){
             $estiloHora = $estiloHora.' ausencia';
         }
    }
    else{
        $estiloHora='horaDocencia';
        
         if($contenido_ausencia==1){
             $estiloHora = $estiloHora.' ausencia';
             $estiloOpciones = 'hayTareas';
         }
    }
    
    
    
    $html='<div class="col-sm-2 celda '.$estiloHora.'" id="celda_'.$dia.'_'.$hora.'"   data-fecha="'.$fecha.'" data-dia="'.$dia.'" data-hora="'.$hora.'">
                <div class="datos_hora">'.$contenido_hora.'</div>
                
                
                <div class="opciones '.$estiloOpciones.'" id="opciones_'.$dia.'_'.$hora.'">
                  <div class="icono_tarea" id="icono_tarea_'.$dia.'_'.$hora.'">
                   <img id="imagen_tarea_'.$dia.'_'.$hora.'" src="imagenes/icono_sin_tarea.png" width="40px" height="40px"/>
                 </div>
                    
                 <div class="icono_obsevaciones" id="icono_observaciones_'.$dia.'_'.$hora.'" data-dia="'.$dia.'" data-hora="'.$hora.'" data-target="#modal_observaciones" data-toggle="modal">
                   <img id="imagen_observacion_'.$dia.'_'.$hora.'" src="imagenes/icono_sin_observaciones.png" width="40px" height="40px"/>
                 </div>
                
                </div>
                <div class="contenido_hora" id="hora_'.$dia.'_'.$hora.'" data-dia="'.$dia.'" data-hora="'.$hora.'">
                     <div class="texto_observacion" id="texto_'.$dia.'_'.$hora.'">
                    
                         <div class="observacion" id="observaciones_'.$dia.'_'.$hora.'" data-dia="'.$dia.'" data-hora="'.$hora.'">
                            '.$info->getObservaciones().'
                             </div>
                    </div>   
                </div>
                 
                
            </div>';
    
    
    return $html;
    
}

?>
  
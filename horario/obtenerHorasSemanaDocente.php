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
                var idhora = $(this).data('idhora');
                var fecha = $(this).data('fecha');

                var semana  =  $('.horario').data('semana');
                var docente  =  $('.docente').data('docente');
                
                console.info(semana); 
                console.info(docente); 
                console.info(idhora);
               console.info(fecha);
        
                icono_tarea="#icono_tarea_"+dia+"_"+hora;
                opciones_hora="#opciones_"+dia+"_"+hora;
                console.info(opciones_hora);
                
                 if (!$(this).parent().hasClass('horaLibre')){ //Si no es hora libre = hora lectiva
                     
                     if (!$(this).parent().hasClass('ausencia')){ //Si no está marcado como ausencia
                         $(this).parent().addClass('ausencia'); // set a ausencia
                         //$(opciones_hora).show();  //mostramos opciones de ausencia:tarea y observaciones
                         $(opciones_hora).addClass("hayTareas");
                         $(opciones_hora).removeClass("sinOpciones");
                         console.info(idhora);
                         registrarAusencia(docente,semana,dia,hora-1,1,idhora,fecha);
                    }else{
                        $(this).parent().removeClass('ausencia'); // quitamos ausencia
                        //$(opciones_hora).hide();  // oculatamos opciones de ausencia
                        $(opciones_hora).removeClass("hayTareas");
                        $(opciones_hora).addClass("sinOpciones");
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

            console.info("Observaciones");

             var dia = $(this).data('dia');
             var hora = $(this).data('hora');
             
            id_celda="#celda_"+dia+"_"+hora;
            id_observacion="#observaciones_"+dia+"_"+hora;
         
            $("#formObservaciones #dia").val(dia);
            $("#formObservaciones #hora").val(hora);
    
            contenido = $(id_observacion).html();
            //IMPORTANTE PARA QUITAR LOS ESPACIO EN BLANCO DELANTE DEL TEXTO.
            //TAMBIÉN AYUDA A POSICIONAR EL CURSOR AL INICIO DEL INPUT.
            //contenido = contenido.replace(/^\s+|\s+$/g, "");
            contenido = $.trim(contenido);
            $("#formObservaciones #campo_observaciones").val(contenido);
    
    
            //Se convierte el div en editable para que se pueda introducir texto
            // y recoger las observaciones para esa guardia.
            //$(id_observacion).attr('contenteditable','true');
            

  }); // fin click sobre icono observaciones
    
    
}); // fin de ready del documento

    
function cambiarIconoHayTarea(icono,imagen){      
         $(imagen).attr('src', '../imagenes/icono_tarea2.png');
         $(icono).removeClass('sin_tarea'); 
}
    
function cambiarIconoNoHayTarea(icono,imagen){ 
          $(imagen).attr('src', '../imagenes/icono_sin_tarea.png');
          $(icono).addClass('sin_tarea');  
}
       
function cambiarIconoHayObservaciones(imagen){  
         $(imagen).attr('src', '../imagenes/icono_observaciones.png');
}

function cambiarIconoNoHayObservaciones(imagen){
         $(imagen).attr('src', '../imagenes/icono_sin_observaciones.png');  
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

        observacion = $.trim(observacion);
        $(observaciones).html(observacion);

        imagen='#imagen_observacion_'+dia+'_'+hora;
        if(observacion!=''){
            console.info('Observacion:'+observacion);
            console.info('Longitud:'+observacion.length);
            cambiarIconoHayObservaciones(imagen);
            guardar_observacion(docente,semana,dia,hora,observacion);
            if(observacion==''){
                cambiarIconoNoHayObservaciones(imagen);
                //borrar_observacion(docente,semana,dia,hora);
            }

        }else{
              console.info('observacion vacía');
            cambiarIconoNoHayObservaciones(imagen);
            //borrar_observacion(docente,semana,dia,hora);
        }

        $('.submitBtn').removeAttr("disabled");
        $('.modal-body').css('opacity', '');
            

}
    
    

        
        
function registrarAusencia(docente,semana,dia,hora,ausencia,idhora,fecha){
            console.info(idhora);
            id_celda="#celda_"+dia+"_"+(hora+1);
            if(!$(id_celda).hasClass('horaLibre')){
                switch(dia){
                    case 1:
                        horario.LUNES[hora]=ausencia;
                        //tareas.LUNES[hora]=tarea;
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
    
            guardar_ausencia(docente,semana,dia,hora+1,idhora,fecha);
            
}
     
function guardar_ausencia(docente,semana,dia,hora,idhora,fecha){

        
               var valores = {"profesor":docente,
                              "semana":semana,
                              "dia":dia,
                              "hora":hora,
                              "idhora":idhora,
                              "fecha":fecha
                             };

                //var contloader=document.getElementById('contenedorMensajes');
                //var mostrarResultado = document.getElementById('resultado');

                $.ajax({
                    url: "../ausencias/guardarAusencia.php",
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
                    url: "../ausencias/guardarTarea.php",
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
                    url: "../ausencias/guardarObservacion.php",
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
                    url: "../ausencias/borrarAusencia.php",
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
                    url: "../ausencias/borrarTarea.php",
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
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="borrarObservacion()" data-backdrop="false">Borrar</button>    
          <button type="button" class="btn btn-info" data-dismiss="modal" onclick="escribirObservacion()" data-backdrop="false">Aceptar</button>
        </div>
      </div>
      
    </div>
  </div>
    <!-- FIN Modal -->
<?php

 require('../bd/conexion.php');
 require_once '../ausencias/Guardias.php';
 require_once '../DTO/HorasSemanasDocente.php';
 require_once '../DTO/ausencia.php';


  if(isset($_GET["docente"]))
      $id_docente = $_GET["docente"]; 

  
  

/** RECUPERAR DATOS DEL DOCENTE */
$sql_docente="SELECT * FROM docente WHERE id=".$id_docente; 
  $resultado_consulta_docente = mysqli_query($connection,$sql_docente) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_docente);
  $datos_docente = $resultado_consulta_docente->fetch_assoc();   
  $docente =  $datos_docente['nombre'];
 /** -------------------------------------- */ 

  $sql_recuperar_horario="SELECT h.id idhora,dia,hora,materia,grupo,aula,nombre FROM horario h INNER JOIN docente d ON h.idProfesor=d.id WHERE d.id=".$id_docente; 


/** RECUPERAR HORARIO DEL DOCENTE */
 $sql_recuperar_horario="SELECT h.id idhora,h.dia,h.hora,materia,h.grupo,h.aula,nombre,g.id ausencia,g.tarea,g.observaciones
FROM horario h 
INNER JOIN docente d ON h.idProfesor=d.id 
LEFT JOIN guardia g ON h.id=g.horario
WHERE d.id=".$id_docente."
ORDER BY h.dia,h.hora";
$horas_docente = mysqli_query($connection,$sql_recuperar_horario) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_recuperar_horario);

$datosHoras = array();
if ($horas_docente->num_rows > 0) {
    while($hora = $horas_docente->fetch_assoc()) {       
        $datosHoras []= $hora;  
    }
    
} 

/** -------------------------------------- */

/** RECUPERAR AUSENCIAS DEL DOCENTE EN LA SEMANA ACTUAL */
$fecha = new DateTime();
$semana= obtenerNumeroSemana($fecha);

$sql_recuperar_ausencias="SELECT g.id,horario,dia,hora,grupo,aula,idProfesor,nombre,tarea,observaciones FROM guardia g INNER JOIN docente d ON g.idProfesor=d.id WHERE d.id=".$id_docente.' AND g.semana='.$semana; 
$horas_ausencias = mysqli_query($connection,$sql_recuperar_ausencias) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_recuperar_ausencias);
  
$datosAusencias = array();
if ($horas_ausencias->num_rows > 0) {
    while($ausencia = $horas_ausencias->fetch_assoc()) {       
        $datosAusencias []= $ausencia;  
    }
    
} 

/** -------------------------------------- */


//$codigo_html = mostrarHorariosDocentes(obtenerHorario($datosHoras),$id_docente,$docente,obtenerAusencias($datosAusencias),obtenerInformacion($datosAusencias));

$codigo_html2 = mostrarHorariosDocentes2(obtenerHorario2($datosHoras,$datosAusencias),$id_docente,$docente);


  echo $codigo_html2;

  //echo $codigo_html;
  echo ' <div id="edit-popup" style="display: none">
                <textarea id="text-edit" style="width:100%;height:100%"/>
            </div>';

  mysqli_close($connection);



/**Funcion que crea un array asociativo en el que cada elemento corresponde a un día de la semana 
 * $dias["LUNES"][{{2,1},{4,5},{6,3}}] -> En el lunes se tiene clase en tres días.
*/

function obtenerHorario2($lista_horas,$lista_ausencias){
    
     $dias = array();
   
     $dias["LUNES"]=recuperaHoras2($lista_horas,$lista_ausencias,1);
     $dias["MARTES"]=recuperaHoras2($lista_horas,$lista_ausencias,2);
     $dias["MIERCOLES"]=recuperaHoras2($lista_horas,$lista_ausencias,3);
     $dias["JUEVES"]=recuperaHoras2($lista_horas,$lista_ausencias,4);
     $dias["VIERNES"]=recuperaHoras2($lista_horas,$lista_ausencias,5);
    
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
function recuperaHoras2($listaHoras,$listaAusencias,$dia){
    
    
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
             $ausencia = recuperarAusencia($listaAusencias,$dia,$hora);
             if($ausencia!=null){ // si hay ausencia
              $horaDocente->setAusencia($ausencia->getId());
              $horaDocente->setTarea($ausencia->getTarea());
              $horaDocente->setObservaciones($ausencia->getObservaciones());

             }
           
            //$horaDocente->setAusencia($listaHoras[$num_clase]['ausencia']);
            //$horaDocente->setTarea($listaHoras[$num_clase]['tarea']);
            //$horaDocente->setObservaciones($listaHoras[$num_clase]['observaciones']);
           //----------------------------

            $clases[$hora]=$horaDocente;
            $hora++;
        }
    }

    return $clases;
    
}


function recuperarAusencia($listaAusencias,$dia,$hora){

  $ausencia=null;
 
  $num_ausencias=sizeof($listaAusencias); // total de horas de clase del docente

  for($num_ausencia=0;$num_ausencia<$num_ausencias;$num_ausencia++){
      if($listaAusencias[$num_ausencia]['dia']==$dia && $listaAusencias[$num_ausencia]['hora']==$hora){ // si el array de ausencias hay un elemento que coincide en día y hora , significa que hay ausencia,
        // entonces devolvemos el valor de 
        $ausencia = new Ausencia();
        $ausencia->setId($listaAusencias[$num_ausencia]['id']);
        $ausencia->setDia($listaAusencias[$num_ausencia]['dia']);
        $ausencia->setHora($listaAusencias[$num_ausencia]['hora']);
        $ausencia->setTarea($listaAusencias[$num_ausencia]['tarea']);
        $ausencia->setObservaciones($listaAusencias[$num_ausencia]['observaciones']);

      }
  }

  return $ausencia;

}


function mostrarHorariosDocentes2($horario,$id_docente,$docente){
    
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
   
     <div class="col-sm-7">
      
     
    
       <!--<a href="../docente/listarDocentes.php" class="btn btn-info btn-volver boton" role="button">Volver </a>-->
</div>
<div class="col-sm-1">
</div>
    </div>
    <div class="row">
      <div class="col-sm-2 bg-celda-info celdaTituloH">H</div>
       <div class="col-sm-2 bg-celda-info">
                  '.$vectorDias[0].' LUNES 
                <img class="boton_seleccion_todo" data-dia="1" src="../imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
       </div> 
        <div class="col-sm-2 bg-celda-info"> '.$vectorDias[1].' MARTES
              <img class="boton_seleccion_todo" data-dia="2" src="../imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
        </div>
         <div class="col-sm-2 bg-celda-info"> '.$vectorDias[2].' MIERCOLES
              <img class="boton_seleccion_todo" data-dia="3" src="../imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
         
         </div>
          <div class="col-sm-2 bg-celda-info"> '.$vectorDias[3].' JUEVES
                  <img class="boton_seleccion_todo" data-dia="4" src="../imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
          </div>
           <div class="col-sm-2 bg-celda-info"> '.$vectorDias[4].' VIERNES
                  <img class="boton_seleccion_todo" data-dia="5" src="../imagenes/icono_seleccionar_todo.png" width="30px" height="30px"/>
           </div>  
    </div>'; 

      
           for($i=1;$i<7;$i++){
               
             $html =$html.'<div class="row">';
             $html =$html.'<div class="col-sm-2 bg-celda-info celdaHora">'.$i.'</div>';
             $html =$html.''.construirHTML_hora2($vectorDias[0],$semana,$horario['LUNES'][$i]);
             $html =$html.''.construirHTML_hora2($vectorDias[1],$semana,$horario['MARTES'][$i]);
             $html =$html.''.construirHTML_hora2($vectorDias[2],$semana,$horario['MIERCOLES'][$i]);
             $html =$html.''.construirHTML_hora2($vectorDias[3],$semana,$horario['JUEVES'][$i]);
             $html =$html.''.construirHTML_hora2($vectorDias[4],$semana,$horario['VIERNES'][$i]);
             $html =$html.'</div>';
           }
        
  $html =$html.'  </div></div>';
    
    return $html;
}


//Rrecibe la semana y un objeto de tipo HorasSemanasDocente con la variable $info
function construirHTML_hora2($fecha,$semana,$info){
    
    $idhora=$info->getId();
    $dia=$info->getDia();
    $hora=$info->getHora();
    $contenido_hora=$info->getContenido();
    $ausencia=$info->getAusencia();
    $grupo=$info->getGrupo();
    $aula=$info->getAula();
    $tarea = $info->getTarea();
    $observaciones = $info->getObservaciones();
   
    $estiloHora='';
    $estiloOpciones = 'sinOpciones';
    $iconotarea = '../imagenes/icono_sin_tarea.png';
     $iconoObservaciones = '../imagenes/icono_sin_observaciones.png';
    
    
    if($contenido_hora=='LIBRE'){
        $estiloHora='horaLibre';
        $contenido_hora='';
    }else if($contenido_hora=='GUARDIA'){
         $estiloHora='horaGuardia';
         if($ausencia!=NULL){
             $estiloHora = $estiloHora.' ausencia';
         }
    }
    else{
        $estiloHora='horaDocencia';
        
         if($ausencia!=NULL){
             $estiloHora = $estiloHora.' ausencia';
             $estiloOpciones = 'hayTareas';
         }
    }
    if($tarea==1){
        $iconotarea='../imagenes/icono_tarea2.png';
    }
    if($observaciones!=NULL){
        $iconoObservaciones="../imagenes/icono_observaciones.png";
    }
    
    
    $html='<div class="col-sm-2 celda '.$estiloHora.'" id="celda_'.$dia.'_'.$hora.'" data-fecha="'.$fecha.'"  
    data-dia="'.$dia.'" 
    data-hora="'.$hora.'" 
    data-idhora="'.$idhora.'">
                
                
                <div class="datos_hora">'.$contenido_hora.'-'.$grupo.'-'.$aula.'</div>
                
                
                <div class="opciones '.$estiloOpciones.'" id="opciones_'.$dia.'_'.$hora.'">
                  <div class="icono_tarea" id="icono_tarea_'.$dia.'_'.$hora.'">
                   <img id="imagen_tarea_'.$dia.'_'.$hora.'" src="'.$iconotarea.'" width="40px" height="40px"/>
                 </div>
                    
                 <div class="icono_obsevaciones" id="icono_observaciones_'.$dia.'_'.$hora.'" data-dia="'.$dia.'" data-hora="'.$hora.'" data-target="#v" data-toggle="modal">
                   <img id="imagen_observacion_'.$dia.'_'.$hora.'" src="'.$iconoObservaciones.'" width="40px" height="40px"/>
                 </div>
                
                </div>
                <div class="contenido_hora" id="hora_'.$dia.'_'.$hora.'" data-fecha="'.$fecha.'" data-dia="'.$dia.'" data-hora="'.$hora.'" data-idhora="'.$idhora.'">
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
  
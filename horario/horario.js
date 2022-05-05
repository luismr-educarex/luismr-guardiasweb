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

                console.info(id_imagen);
                
                //Se cambia el icono y la clase del div icono_tarea_x_y donde x es el dia e y la hora
                if ($(this).hasClass('sin_tarea')){   // si no hay tarea, es que se ha hecho clik para poner la tarea. 
                    console.info("Cambiamos el icono");
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
       

  $(".icono_observaciones").click(function(event) {

            console.info("Observaciones");

             var dia = $(this).data('dia');
             var hora = $(this).data('hora');
             
            id_celda="#celda_"+dia+"_"+hora;
            id_observacion="#observaciones_"+dia+"_"+hora;

            console.info(id_celda);
         
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
         console.info("Se ha puesto el icono de tarea");
}
    
function cambiarIconoNoHayTarea(icono,imagen){ 
          $(imagen).attr('src', '../imagenes/icono_sin_tarea.png');
          $(icono).addClass('sin_tarea');  
          console.info("Se ha puesto el icono de no hay tarea");
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
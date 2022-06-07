<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../comun/cabeceraTablon.php';
require_once 'AusenciasDAO.php';
require_once 'Guardias.php';
require_once '../comun/fechas.php';
require_once '../DTO/HorasSemanasDocente.php';
require_once '../docente/DocenteDao.php';

?>

<script>


function seleccionarDocenteGuardia(docente,ausencia){

    console.info(docente+"-"+ausencia);
    var url = "guardarDocenteGuardia.php";  

    var valores = {"ausencia":ausencia,
                              "docenteGuardia":docente
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
           
           
         if(docente!=0){   
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

      
}


    



</script>
<script type="text/javascript" src="../js/Datejs/build/date.js"></script>
<script>
  
$(document).ready(function() {	
    function comprobarHora() {


        //obtenemos los tres datos del tiempo que necesitamos:
        // dia en la semana 1=lunes,2=martes,...
        // fecha actual en formato d-m-a
        // semana en el año 
        const tiempoTranscurrido = Date.now();
        const hoy = new Date(tiempoTranscurrido);
        var diaEnSemana=hoy.getDay();
        

        var date = new Date();
        const formatDate = (date)=>{
        let formatted_date = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear()
        return formatted_date;
        }
        var fecha = formatDate(date)
        

         //Obtener el numero de la semana 
        currentdate = new Date();
        var oneJan = new Date(currentdate.getFullYear(),0,1);
        var numberOfDays = Math.floor((currentdate - oneJan) / (24 * 60 * 60 * 1000));
        var semana = Math.ceil(( currentdate.getDay() + 1 + numberOfDays) / 7);

       /*
        Date.prototype.getWeek = function() {
        var onejan = new Date(this.getFullYear(),0,1);
        var today = new Date(this.getFullYear(),this.getMonth(),this.getDate());
        var dayOfYear = ((today - onejan + 86400000)/86400000);
        return Math.ceil(dayOfYear/7)
        };
        var today = new Date();
        var semana = today.getWeek();
        console.log(semana);
        */

        console.info("./obtenerAusencias.php?semana="+semana+"&dia="+diaEnSemana+"&fecha="+fecha);
        $.ajax({
            type: "GET",
            //semana=21&dia=5&fecha=27-5-2022<<<<
            url: "./obtenerAusencias.php?semana="+semana+"&dia="+diaEnSemana+"&fecha="+fecha,
            success: function(data) {
                console.info("Datos recibidos correctamente");
                
                
                $("#contenidoAusencias").html(data);
                
            },
            error : function(xhr, status) {
            alert('Disculpe, existió un problema a la hora de mostrar las guardias');
            },

            // código a ejecutar sin importar si la petición falló o no
            complete : function(xhr, status) {
               
            }
        });
       
    }
   setInterval(comprobarHora, 1000);
   
});


</script>



<div id="contenidoAusencias"></div>

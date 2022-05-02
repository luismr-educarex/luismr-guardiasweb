
<?php

require_once '../comun/cabecera.php';
require_once '../ausencias/AusenciasDAO.php';



$diaActual=date("j");
if(isset($_GET["mes"])){
    $mes = $_GET["mes"]; 
    $mesActual=date("n");
     
    if($mes<$mesActual)
       $diaActual=32;
    else if($mes>$mesActual)
       $diaActual=0;

}else{
    $mes=date("n");   
}
if(isset($_GET["anio"])){
     $anio = $_GET["anio"]; 
}else{
    $anio=date("Y");
}

if($mes==0){
    if($anio>date("Y")){
        $anio=$anio-1;
    }
   $mes=12;
}

if($mes>12){
    if($anio==date("Y")){
        $anio=$anio+1;
    }
   $mes=$mes%12;
}

$fecha=$anio.'-'.$mes.'-01';

//Ejemplo: Queremos mostrar Agosto 2018
$ObjetoFecha = new DateTime($fecha);

$numeroDias = $ObjetoFecha->format('t');

$diaInicioSemana = $ObjetoFecha->format('w');
// Domingo es 0, así que en este caso lo convertimos a 7 si es domingo
$diaInicioSemana = $diaInicioSemana == 0 ? 7 : $diaInicioSemana;
   
//Sacamos la semana del día uno usando el objeto creado en el punto 1.
//Si es Enero directamente lo inicializamos a cero
$semanaPrimerDia = $ObjetoFecha->format('n') == 1 ? 0 : $ObjetoFecha->format('W');


//Movemos la fecha hacia delante el numero de días
//que tiene el mes menos uno.
$intervalo = $numeroDias -1;
$ObjetoFecha->modify("+" . $intervalo . " days");

//Y sacamos la semana en la que estamos
$semanaUltimoDia = $ObjetoFecha->format('W');
//sumamos 1 porque la primera semana tambien hay que contarla


$anio = $ObjetoFecha->format('Y');
//$diaActual = $ObjetoFecha->format('d');


if($semanaPrimerDia<$semanaUltimoDia)
$numeroSemanas   = $semanaUltimoDia-$semanaPrimerDia+1;
else
$numeroSemanas=6;
/*
echo "Dia actual:".$diaActual;
echo $fecha."<br>";
echo "Numero de dias:".$numeroDias."<br>";
echo "Empieza el día:".$diaInicioSemana."<br>";
echo "Semana primer día:".$semanaPrimerDia."<br>";
echo "Semana ultimo día:".$semanaUltimoDia."<br>";
echo "Numero de semanas:".$numeroSemanas."<br>";
*/
$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$feriados        = array( 
'1-1',  //  Año Nuevo (irrenunciable) 
'1-5',  //  Día Nacional del Trabajo (irrenunciable) 
'12-10',  //  Aniversario del Descubrimiento de América 
'1-11',  //  Día de Todos los Santos (feriado religioso) 
'6-12',  //  Aniversario Constitución
'8-12',  //  Inmaculada Concepción de la Virgen (feriado religioso) 
'25-12',  //  Natividad del Señor (feriado religioso) (irrenunciable) 
); 


$ausenciaDAO = new AusenciasDAO();

//$result = $ausenciaDAO ->obtener_ausencias_por_dia_en_mes($mes);

//array en el que viene semana-dia-cantidad de ausencias
$cantidad_ausencias = $ausenciaDAO -> obtener_ausencias_por_dia_en_mes($semanaPrimerDia,$semanaUltimoDia);


$diferenciaSemanas =$semanaUltimoDia-$semanaPrimerDia+1;

$listaGuardias = array();
$numSemana=1;
    if ($cantidad_ausencias->num_rows > 0) {
        while($guardia = $cantidad_ausencias->fetch_assoc()) {       
            $numSemana = $diferenciaSemanas - ($semanaUltimoDia-$guardia['semana']);
            $listaGuardias [$numSemana]= $guardia;  
           
            //$numSemana++; 
        } 
    } 


//echo (int) (new \DateTime())->format('W');
//echo (int) (new \DateTime('first day of this month'))->format('W') + 1;
//echo (int) (new \DateTime())->format('W') - (int) (new \DateTime('first day of this month'))->format('W') + 1;
?>

<table id="calendar">
    
    <div class="cabeceraCalendario">
       <div class="navegacionMeses">      <ul class="pager">
    <li><a href="calendario3.php?mes=<?php echo $mes-1?>&anio=<?php echo $anio ?>">Anterior</a></li>
          <span class="tituloMes"><?php echo $meses[$mes]." ".$anio?></span> 
  <li><a href="calendario3.php?mes=<?php echo $mes+1?>&anio=<?php echo $anio ?>">Siguiente</a></li>
</ul>
    </div>
    
    </div>
  <thead class="tituloDias">
    
    <th>Lunes</th><th>Martes</th><th>Miercoles</th>
    <th>Jueves</th><th>Viernes</th>
  </thead>
  <tbody>
  <?php
    $semana = $semanaPrimerDia;  
    $numSemanaEnMes=1;
    
    for($i=$semanaEnMes;$i<=$numeroSemanas;$i++){ //bucle de las semanas
        
        ?><tr><?php
            for($d=1;$d<=7;$d++){  //bucle de los dias en la semana
                if($i==1){ //si es la primera semana, hay que preguntar cuando empieza el mes. Ese dato está en $diaInicioSemana
                    if($d>=$diaInicioSemana){
                       
                        $dia=isset($dia) ? $dia+1:1;
                    }
                }elseif(isset($dia) && $dia <$numeroDias){ //si no es la primera semana se comprueba que estamos dentro del mnes
                  
                    $dia++;
                 
                    
                }
                else{
                 
                    unset($dia);
                }
                
                if(isset($dia)){
                 
                  
                      $fecha=$dia.'-'.$mes.'-'.$anio;
                    
                      $estiloDia="";
                      if($dia==$diaActual){
                          $estiloDia="actual";
                      }else if($dia>$diaActual){
                           $estiloDia="diafuturo";
                      }
                     
                      if($d<=5){ // IMPORTANTE -> No pintamos los sábados y domingos. 
                      echo '<td class="dianormal  '.$estiloDia.'" width="200" height="100" padding="0"><a href="../ausencias/listarAusenciasGuardias3.php?semana='.$semana.'&dia='.$d.'&fecha='.$fecha.'">';
                        ?>  
                        <table width="100%" height="100%">
                            <tbody>
                               <tr>
                                    <!--
Si queremos abrir el modal al hacer click sobre un día del calendario, en el td del día hay que añadir el siguiente código: data-toggle="modal" data-target="#myModal"

 -->
                                         
                                 <?php 
                 
                                          if(in_array($dia.'-'.$mes,$feriados)){  
                                            echo '<td class="elementoCelda festivo">';
                                            echo $dia;
                                            echo '</td>';
                                        }
                                        else{
                                            echo '<td class="elementoCelda">';
                                            
                                            echo $dia;
                                            if(isset($listaGuardias[$numSemanaEnMes]['dia'])){
                                              if($listaGuardias[$numSemanaEnMes]['dia']==$d){
                                                echo '<div class="contenedorNumAusencias"><span class="numeroGuardiasEnDia">';
                                                echo 'Ausencias:'.$listaGuardias[$numSemanaEnMes]['total_ausencias'];
                                                echo '</span></div>';
                                              }
                                             
                                            }
                                             
                                            echo '</td>';
                                          }
                                          
                                        ?>
                                </tr>
                                <tr>
                                    <td class="elementoCelda">
                                      <?php ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td class="elementoCelda">
                                      <?php ?>
                                   </td>
                                </tr>
                            
                            </tbody>
                        </table>
                      
                    <?php 
                    echo '</a></td>';

                  }
                }
                else{
                    ?><td class="dianomes">&nbsp;</td><?php
                }
                
            } // fin for que pinta los dias de la semana del 1-7
      ?></tr><?php
        $semana++;
        $numSemanaEnMes++;
    }    
?>

 

<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Configuración día</h4>
        </div>
        <div class="modal-body">
          <p>Marcar el día como...</p>
            
            <div class="checkbox">
  <label><input type="checkbox" value="">Festivo</label>
</div>
<div class="checkbox">
  <label><input type="checkbox" value="">Fin de evaluación</label>
</div>
<div class="checkbox disabled">
  <label><input type="checkbox" value="" >Fin de FCT</label>
</div><form>
            
                        <input type="hidden" name="dia" id="dia" value=""/>
            </form>

        </div>
          
        <div class="modal-footer">
            <button type="button" class="btn btn-info" data-dismiss="modal" onclick="enviar()">Grabar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>
    
    
 </tbody>
</table>

<script>
    
$(document).on("click", ".elementoCelda", function () {
var dia = $(this).data('dia');
var semana = $(this).data('semana');
console.info(dia+"-"+semana);
$(".modal-body #dia").val( dia );
});
function enviar(){
    
    alert("enviar configuración día");
    alert($(".modal-body #dia").val());
}

</script>
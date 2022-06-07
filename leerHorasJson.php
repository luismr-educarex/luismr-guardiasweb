<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Europe/Madrid");
$time = time();
echo "<center> Fecha Y Hora"."<br/>";
$dias = array("domingo","lunes","martes","miercoles","jueves","viernes","sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 $f = $dias[date('w')];
echo $f." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y')."<br>" ;
echo date('H:i',$time);
echo '<br>';
echo strtotime(date('H:i',$time));
echo '<br>';
echo strtotime("9:4",$time);


function obtenerHoraActualdeDocencia(){
    $time = time();

$horaActual =strtotime(date('H:i',$time));


$datos = file_get_contents("./configuracion/horas.json");
$horario = json_decode($datos,true);
$horas = $horario['horas'];

$horalectiva = 0;
$horaEncontrada=false;
echo "<br>horario<br>";
foreach($horas as $hora){
    $hora_inicio=$hora['inicio'];
    $hora_fin=$hora['fin'];
   
    echo "<br>";
    echo "hora inicio:".strtotime($hora_inicio,$time);
    echo "<br>";
    echo "hora fin:".strtotime($hora_fin,$time);
    if(($horaActual>=strtotime($hora_inicio,$time)) && ($horaActual<=strtotime($hora_fin,$time)) && !$horaEncontrada)
    {
        $horalectiva = $hora['hora'];
        $horaEncontrada=true;
        
    } 
}
echo "<br>";
echo $horaActual.'-----'.$horalectiva;

}

obtenerHoraActualdeDocencia();




?>
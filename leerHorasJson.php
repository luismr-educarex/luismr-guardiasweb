<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$time = time();
$horaActual = intval(date("H", $time));
$minutoActual = intval(date("i", $time));

$datos = file_get_contents("./configuracion/horas.json");
$horario = json_decode($datos,true);
$horas = $horario['horas'];

$horalectiva = 0;
$horaEncontrada=false;
foreach($horas as $hora){
    $hora_inicio=$hora['inicio'];
    $hora_fin=$hora['fin'];
    $hm_inicio= explode(':',$hora_inicio);
    $hm_fin= explode(':',$hora_fin);
    $h_inicio = intval($hm_inicio[0]);
    $m_inicio = intval($hm_inicio[1]);
    $h_fin = intval($hm_fin[0]);
    $m_fin = intval($hm_fin[1]);
    

    if(($horaActual>=$h_inicio) && ($horaActual<=$h_fin) && !$horaEncontrada)
    {

        $horalectiva = $hora['hora'];
        $horaEncontrada=true;
        
    }   
}

echo $horaActual.'---'.$minutoActual.'----'.$horalectiva;//rand(0,100);


?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php

if(isset($_GET["semana"])){
    $semana = $_GET["semana"];
    
    if($semana=='siguiente'){ 
        $vectorDias =  obtenerVectorDiasSemanaSiguiente();
        imprimirSemana($vectorDias);
    }
    if($semana=='anterior'){ 
          $vectorDias =  obtenerVectorDiasSemana();
          imprimirSemana($vectorDias);
    }
    
}else{
   $vectorDias =  obtenerVectorDiasSemana();
   imprimirSemana($vectorDias);
}  

function obtenerPrimerDiaSemana(){
    
    $year=date('y');
    $month=date('m');
    $day=date('d');
 
    # Obtenemos el día de la semana de la fecha dada. Con N, los lunes=1 y los domingos=7
    $diaSemana=date("N",mktime(0,0,0,$month,$day,$year));
    # A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
    $primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
    return $primerDia;
}
    
function obtenerUltimoDiaSemana(){
    # A la fecha recibida, le sumamos el dia de la semana menos 5 y obtendremos el viernes
    $ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(5-$diaSemana),$year));
    return $ultimoDia;
    
}
    
function obtenerVectorDiasSemana(){
    
    $vectorDias = array();
    $primerDia = obtenerPrimerDiaSemana();
    array_push ( $vectorDias , $primerDia );
    for($i=1;$i<5;$i++){
         array_push ( $vectorDias , date("d-m-Y",strtotime($primerDia."+ ".$i." days")) );
    }
    
    return $vectorDias;
   
}
    
function obtenerVectorDiasSemanaSiguiente(){
    $vectorDiasSemanaSiguiente=array();
    $primerDia = obtenerPrimerDiaSemana();
    $primerDia = date("d-m-Y",strtotime($primerDia."+ 7 days"));
    array_push ( $vectorDiasSemanaSiguiente , $primerDia );
    for($i=1;$i<5;$i++){
         array_push ( $vectorDiasSemanaSiguiente , date("d-m-Y",strtotime($primerDia."+ ".$i." days")) );
    }
                      
    return $vectorDiasSemanaSiguiente;
}
                      
function imprimirSemana($vectorSemana){
   
    print_r($vectorSemana);
    echo "LISTADO DE DIAS".'<br>';
    for($i=0;$i<5;$i++){
         echo "DIA ".($i+1).":".$vectorSemana[$i].'<br>';
    }
}
?>
    <a href="ejemploFechas.php?semana=anterior">Semana -</a>
    <a href="ejemploFechas.php?semana=siguiente">Semana +></a>

</body>
</html>
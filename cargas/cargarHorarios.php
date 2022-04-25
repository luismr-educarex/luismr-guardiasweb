<head>
  <title>GUARDIAS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
<style>
       .boton_panel{
           width: 170px;
           height: 200px;
           font-size: 30px;
           text-align: center;
           padding-top: 60px;
       }
       .panel{
           width: 900px;
           margin-left: 160px;
           margin-top: 160px;
       }
        table {
      display: block;
      overflow-x: auto;
    }

    .static {
      /* position: absolute;*/
      background-color: white;
      height: 100%;
      padding-right: 20px;
      width: 30px;
    }

    .first-col {
      padding-left:100.5px!important;
    }
    
   </style>    
    
</head>

<?php

require_once "horario.php";

if(isset($_POST["fichero"]))
      $fichero = $_POST["fichero"]; 


//$fichero = fopen("empresas.csv", "r") or die("No se puede abrir el fichero!");


$directorio = "subidas/";
$ruta_fichero = $directorio . basename($_FILES["fichero"]["name"]);


$mensajeFichero ='<div class="w3-container w3-blue"><p>FICHERO:'.$ruta_fichero.'</p></div>';


$uploadOk = 1;

$tipoFichero = strtolower(pathinfo($ruta_fichero,PATHINFO_EXTENSION));

// Comprobar que el fichero ya existe
if (file_exists($ruta_fichero)) {
    $mensaje ='<div class="w3-container w3-blue"><p>El fichero ya existe</p></div>';
    $mensajeFichero.=$mensaje;
    $uploadOk = 0;
}
// Comprobar el tamaño del fichero
if ($_FILES["fichero"]["size"] > 500000) {
    $mensaje ='<div class="w3-container w3-red"><p>El fichero es demasiado grande</p></div>';
    $mensajeFichero.=$mensaje;
    $uploadOk = 0;
}
// Comprobar que el fichero es tipo csv
if($tipoFichero != "csv") {
    $mensaje ='<div class="w3-container w3-red"><p>Sólo están permitidos los ficheros csv</p></div>';
    $mensajeFichero.=$mensaje;
    $uploadOk = 0;
}
// Se comprueba si se ha dado algún caso anterior de error.
if ($uploadOk == 0) {
     $mensaje ='<div class="w3-container w3-blue"><p>El fichero no ha sido subido</p></div>';
    $mensajeFichero.=$mensaje;
// Si todas las comprobaciones han ido bien, intenta subir el fichero.
} else {
    if (move_uploaded_file($_FILES["fichero"]["tmp_name"], $ruta_fichero)) {
         $mensaje ='<div class="w3-container w3-green"><p>El fichero '. basename( $_FILES["fichero"]["name"]). ' ha sido subido</p></div>';
        $mensajeFichero.=$mensaje;
    } else {
         $mensaje ='<div class="w3-container w3-red"><p>Error al subir el ficheros</p></div>';
          $mensajeFichero.=$mensaje;
    }
}

$fichero = fopen($ruta_fichero, "r") or die("No se puede abrir el fichero!");


 echo '<div class="container">
  <h2>Horarios que se van a cargar</h2>';

  echo $mensajeFichero;

  echo '<a href="cargarDatos.php?fichero='.$ruta_fichero.'" class="btn btn-info" role="button">Cargar horarios</a>
  <div class="text-right">
            <a href="cargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
  
  <div style="overflow:scroll;">
  <table class="table" style="font-size:90%;">

      <tr>
        <td class="static">DOCENTE</td>
        <td>L1</td>
        <td>L2</td>
        <td>L3</td>
        <td>L4</td>
        <td>L5</td>
        <td>L6</td>
        <td>L7</td>
        <td>M1</td>
        <td>M2</td>
        <td>M3</td>
        <td>M4</td>
        <td>M5</td>
        <td>M6</td>
        <td>M7</td>
        <td>X1</td>
        <td>X2</td>
        <td>X3</td>
        <td>X4</td>
        <td>X5</td>
        <td>X6</td>
        <td>X7</td>
        <td>J1</td>
        <td>J2</td>
        <td>J3</td>
        <td>J4</td>
        <td>J5</td>
        <td>J6</td>
        <td>J7</td>
        <td>V1</td>
        <td>V2</td>
        <td>V3</td>
        <td>V4</td>
        <td>V5</td>
        <td>V6</td>
        <td>V7</td>
      </tr>
    ';
 	
$horarios=array(); // array donde se almacenarán los horarios de todos los docentes haciendo uso de objetos horario.
//Abrimos nuestro archivo
//Lo recorremos
while (($fila = fgetcsv($fichero, ";")) == true) 
{
     
    //por cada fila del fichero csv, actualizamos la lista de horarios con el objeto
   // horario correspondiente.
    //$fila es un array con los datos de la fila de cada docente.
  $horario=cargarHorarioDocente($fila);   
  array_push($horarios,$horario); 

  $num = count($fila);

   echo '<tr>';
    
    //Recorremos las columnas de esa linea
    for($i=0;$i<=65;$i++){
        $cadena_datos=$fila[$i]; 
        $estilo = "";
        if($i==0){
            echo '<td class="textoTabla static">'.$cadena_datos.'-'.$horario->getTipo_horario().'</td>';
        }else{
        /*
        else if($i==1){
            //$estilo='first-col';
            echo '<td class="textoTabla first-col">'.$cadena.'</td>';
        }*/
          
          $datos =  explode("\n", $cadena_datos); //Obtenemos en un array los valores de las celdas del fichero csv.
           //print_r($valores);
           //echo '<br>';
           if(sizeof($datos)>0){
              echo ' <td class="textoTabla '.$estilo.'">'.$datos[0].'</td>'; 
               
              
           }
        }
               
        
    }
         
        echo '</tr>';
     
} // fin del bucle while que recorre las filas del fichero csv.

//Cerramos el archivo
fclose($fichero);

print_r ($horarios);

echo "</table></div>";

$tablahorarios = mostrarHorariosDocentes($horarios);
echo $tablahorarios;


/*
Esta función se encarga de crear y devolver un objeto horario el cual almacena
el docente, el tipo de horario y un array asociativo con el horario de los cinco 
días de la semana.
Recibe como parámetro el array que se ha obtendo de la fila del fichero csv correspondiente a un docente.
*/
function cargarHorarioDocente($datos_docente){
    
    $horario = new Horario();
    
    //toda fila del csv tiene 66 posiciones, donde la primera corresponde con el nombre
    //del docente.
    $horario->setDocente($datos_docente[0]);
    //obtenemos que tipo de horario tiene el docente:diurno,nocturno,partido o avanza.
    $tipo_horario = obtenerTipoHorario($datos_docente);
    $horario->setTipo_horario($tipo_horario);
    
     //Actualizamos el objeto horario con la lista de horas dependiendo del tipo 
            //de horario.
    if($tipo_horario=='diurno') { // para un horario diurno
        $horario->setHoras(obtenerHorasDiurno($datos_docente));
    }else if($tipo_horario=='nocturno'){ // para un horario nocturno
        $horario->setHoras(obtenerHorasNocturno($datos_docente));
    }else if($tipo_horario=='partido'){ // para un horario partido

    }else{ // para un horario avanza

    }

       
    return $horario;
}

function mostrarHorariosDocentes($horarios){
    
    $html=' <div class="container-fluid">
    <div class="row">
      <div class="col-sm bg-info">HORA</div>
       <div class="col-sm bg-info">LUNES</div>
        <div class="col-sm bg-info">MARTES</div>
         <div class="col-sm bg-info">MIERCOLES</div>
          <div class="col-sm bg-info">JUEVES</div>
           <div class="col-sm bg-info">VIERNES</div>
            
      
    </div>'; 
    
    $num_hora=1;
    foreach($horarios as $horario)
	{
	   $html =$html.'<div class="row">';
           $horas=$horario->getHoras();
           $lunes=$horas['LUNES'];
            $martes=$horas['MARTES'];
            $miercoles=$horas['MIERCOLES'];
            $jueves=$horas['JUEVES'];
            $viernes=$horas['VIERNES'];
           
           for($i=1;$i<7;$i++){
                $html =$html.'<div class="col-sm bg-info">'.$i.'</div>';
               $html =$html.'<div class="col-sm bg-info">'.$lunes[$i].'</div>';
               $html =$html.'<div class="col-sm bg-info">'.$martes[$i].'</div>';
               $html =$html.'<div class="col-sm bg-info">'.$miercoles[$i].'</div>';
               $html =$html.'<div class="col-sm bg-info">'.$jueves[$i].'</div>';
               $html =$html.'<div class="col-sm bg-info">'.$viernes[$i].'</div>';
           }
        
           //print_r($horas);
           /*foreach((array)$horas as $hora){
                $html =$html.'<div class="col-sm bg-info">'.$num_hora.'</div>';
                 foreach($hora as $clase){
                     $html =$html.'<div class="col-sm">'.$clase.'</div>';
                 }
           }*/
       $html =$html.'</div>';
       $num_hora++;
	}// fin foreach
    
  $html =$html.'</div>';
    
    return $html;
}

// Se tiene 4 posibles tipos de horarios
// horario diurno
// horario nocturno
// horario partido
// horario avanza
function obtenerTipoHorario($fila_horas){
    
    $tipo_horario='';
    $horario_diurno=0;
    $horario_nocturno=0;
    //si hay una clase entre la posicion 1 y 6 significa que da clase por la mañana
    for($i=1;$i<7;$i++){
        if($fila_horas[$i]!=''){
            $valores =  explode("\n", $fila_horas[$i]);
            if($valores[0]!='REUNIÓN')
                $horario_diurno=1;
        }
    }
     //si hay una clase entre la posicion 8 y 13 significa que da clase por la tarde
    for($i=8;$i<14;$i++){
        if($fila_horas[$i]!=''){
             $valores =  explode("\n", $fila_horas[$i]);
                if($valores[0]!='REUNIÓN')
                    $horario_nocturno=2;
        }
    }
    
    $suma=$horario_diurno+$horario_nocturno;
    switch($suma){
        case 0: $tipo_horario='avanza';break;
        case 1: $tipo_horario='diurno';break;
        case 2: $tipo_horario='nocturno';break;
        case 3: $tipo_horario='partido';break;
        
    }
    
    return $tipo_horario;
}

function obtenerHorasDiurno($lista_horas){
    
    //LUNES 1..7/ MARTES 14..20/ MIERCOLES 27..33/ JUEVES 40..46/ VIERNES 53..59
     $horas = array();
   
     $horas["LUNES"]=recuperaHoras($lista_horas,1,7);
     $horas["MARTES"]=recuperaHoras($lista_horas,14,20);
     $horas["MIERCOLES"]=recuperaHoras($lista_horas,27,33);
     $horas["JUEVES"]=recuperaHoras($lista_horas,40,46);
     $horas["VIERNES"]=recuperaHoras($lista_horas,53,59);
    
    return $horas;
    
}

function obtenerHorasNocturno($lista_horas){
    
    //LUNES 8..13/ MARTES 21..26/ MIERCOLES 32..39/ JUEVES 47..52/ VIERNES 60..65
     $horas = array();
   
     $horas["LUNES"]=recuperaHoras($lista_horas,8,13);
     $horas["MARTES"]=recuperaHoras($lista_horas,21,26);
     $horas["MIERCOLES"]=recuperaHoras($lista_horas,32,36);
     $horas["JUEVES"]=recuperaHoras($lista_horas,47,52);
     $horas["VIERNES"]=recuperaHoras($lista_horas,60,65);
    
     return $horas;
    
}


function recuperaHoras($lista,$inicio,$fin){
    
    $clases=array();
    $hora=1;
    for($i=$inicio;$i<=$fin;$i++){
        $clases[$hora]=$lista[$i];
        $hora++;
    }
    
    return $clases;
    
}

?>
<head>
  <title>GUARDIAS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    
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
require_once './docente/DocenteDAO.php';
require_once './docente/DocenteDTO.php';
require './bd/Datasource.php';
$config = parse_ini_file('./bd/configBD.ini');

$ds = new Datasource('localhost',$config['dbname'],$config['username'],$config['password']);
$docenteDAO = new DocenteDAO();
$docenteDTO = new DocenteDTO();  

$ruta_fichero="";
if(isset($_GET["fichero"]))
      $ruta_fichero = $_GET["fichero"]; 

$tipos_horarios= [
    "diurno" => 1,
    "nocturno" => 2,
    "avanza" => 3,
    "partido" => 4
];

$ficheroHorarios = fopen($ruta_fichero, "r") or die("No se puede abrir el fichero!");


 echo '<div class="container">
  <h2>Horarios que se van a cargar</h2>';

  echo '
  <div class="">
            <a href="limpiarTablas.php" class="btn btn-error" role="button">Limpiar Tablas</a>
  </div>
  <div class="text-right">
            <a href="formularioCargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
  
  <div style="overflow:scroll;">
  
  ';
 	
//Abrimos nuestro archivo
//Lo recorremos
while (($fila = fgetcsv($ficheroHorarios, ";")) == true) 
{
    
   //por cada fila del fichero csv, actualizamos la lista de horarios con el objeto
  // horario correspondiente.
  //$fila es un array con los datos de la fila de cada docente.
  $horario=cargarHorarioDocente($fila);   
  $docenteDTO->setNombre($fila[0]);
  $tipo_horario=$horario->getTipo_horario();
  $codigo_tipo_horario=$tipos_horarios[$tipo_horario];
  $docenteDTO->setTipoHorario($codigo_tipo_horario);  
  $id_docente = insentarDocente($docenteDTO);
  insertarHorario($id_docente,$horario);
    

}

//Cerramos el archivo
fclose($ficheroHorarios);


function insentarDocente($docente){
    
  require('./bd/conexion.php');
   
  $sql = 'INSERT INTO docente( nombre,tipo_horario) VALUES ("'.$docente->getNombre().'",'.$docente->getTipoHorario().')';

  mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  $id_docente_insertado = mysqli_insert_id($connection); 

  mysqli_close($connection);

  return $id_docente_insertado;
}

function insertarHorario($docente,$horario){
    
    $horas=$horario->getHoras();
    $lunes=$horas['LUNES'];
    $martes=$horas['MARTES'];
    $miercoles=$horas['MIERCOLES'];
    $jueves=$horas['JUEVES'];
    $viernes=$horas['VIERNES'];
    
    grabarhorarios($docente,$lunes,1);
    grabarhorarios($docente,$martes,2);
    grabarhorarios($docente,$miercoles,3);
    grabarhorarios($docente,$jueves,4);
    grabarhorarios($docente,$viernes,5);    
}

function grabarhorarios($docente,$horas_docente,$dia){
    
    require('./bd/conexion.php');
    $grupo;
    $aula;
    $guardia;
    $materia;
    //$num_horas = sizeof($horas_docente);
    for($hora=1;$hora<7;$hora++){
        $datosHora =  explode("\n", $horas_docente[$hora]);
        $num_elementos = sizeof($datosHora);
        if($num_elementos==3){ // si hay tres elementos -> hora normal de clase
            $materia=$datosHora[0];
            $grupo=$datosHora[1];
            $aula=$datosHora[2];
            $guardia=0;
        }else if($num_elementos==2){ //si hay dos elementos -> puede ser guardia o reunion
            $materia=$datosHora[0];
            $grupo=$datosHora[1];
            if($materia=='GUARDIA' && $materia=='Guardia'){
                 $guardia=1;
            }else{
                $guardia=0;
            }
            $aula='';     
        }else{//está vacio con lo cual es una hora LIBRE
            $materia='LIBRE';
            $grupo='';
            $aula='';
            $guardia=0;
        }
        $sql='INSERT INTO `horario`(`idProfesor`, `dia`, `hora`,materia ,`grupo`, `aula`, `guardia`) VALUES ('.$docente.','.$dia.','.$hora.',"'.$materia.'","'.$grupo.'","'.$aula.'",'.$guardia.')';
        
         mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
        
        
    }// fin bucle for
    
     mysqli_close($connection);
    
}

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
     $horas["MIERCOLES"]=recuperaHoras($lista_horas,34,39);
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
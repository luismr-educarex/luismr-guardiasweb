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
// Motrar todos los errores de PHP
error_reporting(E_ALL);

require_once '../comun/cabecera.php';       
require_once "../DTO/horario.php";
require_once '../docente/DocenteDao.php';
require_once '../docente/DocenteDTO.php';
require '../bd/Datasource.php';

$config = parse_ini_file('../bd/configBD.ini');

$ds = new Datasource('localhost',$config['dbname'],$config['username'],$config['password']);
$docenteDAO = new DocenteDAO();
$docenteDTO = new DocenteDTO();  

$fichero="";
if(isset($_GET["fichero"]))
      $fichero = $_GET["fichero"]; 

$config = parse_ini_file('../configuracion/config_importacion.ini'); 

$directorio = "../".$config['nombre_directorio'];     

$ruta_fichero = $directorio."/".$fichero;

$tipos_horarios= [
    "diurno" => 1,
    "nocturno" => 2,
    "avanza" => 3,
    "partido" => 4
];




 echo '<div class="container">
  <h2>Docentes y Horarios insertados</h2>';

  echo '
 
  <div class="text-right">
            <a href="formularioCargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
  
  <div style="overflow:scroll;">
  
  ';
 	
//Abrimos nuestro archivo
$ficheroHorarios = fopen($ruta_fichero, "r") or die("No se puede abrir el fichero!");

//Lo recorremos
while (($fila = fgetcsv($ficheroHorarios, ",")) == true) 
{
   
   //por cada fila del fichero csv, actualizamos la lista de horarios con el objeto horario correspondiente.
  //$fila es un array con los datos de la fila de cada docente.
  $horario=cargarHorarioDocente($fila); 
                 
  $docenteDTO->setNombre($fila[0].','.$fila[1]);
  $tipo_horario=$horario->getTipo_horario();
  $codigo_tipo_horario=$tipos_horarios[$tipo_horario];
  $docenteDTO->setTipoHorario($codigo_tipo_horario);   
  $id_docente = insentarDocente($docenteDTO);
  echo "DOCENTE INSERTADO:".$docenteDTO->getNombre().'<br>';
  insertarHorario($id_docente,$horario);
  echo "HORARIO INSERTADO".'<br>';
    

}

//Cerramos el archivo
fclose($ficheroHorarios);


function insentarDocente($docente){
    
  require('../bd/conexion.php');
   
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


/**
 * funcion que recibe el id del docente, el día y las horas de dicho día.
 * 
 */
function grabarhorarios($docente,$horas_docente,$dia){
    
    require('../bd/conexion.php');
    $grupo;
    $aula;
    $guardia;
    $materia;
    //$num_horas = sizeof($horas_docente);
    for($hora=1;$hora<=6;$hora++){
        //La información de cada elemento (celda en el csv) está separada por un \n
        //Utilizamos el caracter \n para obtener los elementos en un array
        $datosHora =  explode("\n", $horas_docente[$hora]);
        $num_elementos = sizeof($datosHora);
        //Dependiendo del número de elementos, tenemos un tipo de hora.
        // vacio = HORA LIBRE
        // 2 elementos =  HORA DE REUNIÓN O DE GUARDIA: GUARDIAS - Guardias / REUNIŃ - DPTO
        // 3 elementos = HORA DE CLASE CON UN GRUPO : MATERIA - GRUPO - AULA
        // 4 o más elememtos = HORA DE CLASE CON DOS O MÁS GRUPOS : MATERIA - GRUPO1 - AULA - GRUPO2 - GRUPO3...


        if($num_elementos>3){
            $materia=$datosHora[0];
            $grupo=$datosHora[1];
            $aula=$datosHora[2];
            $guardia=0;
            for($i=3;$i<$num_elementos;$i++){
                $grupo.=' - '.$datosHora[$i];
            }

        }
        else if($num_elementos==3){ // si hay tres elementos -> hora normal de clase
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
    $horario->setDocente($datos_docente[0].",".$datos_docente[1]);
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
    for($i=2;$i<8;$i++){
        if($fila_horas[$i]!=''){
            $valores =  explode("\n", $fila_horas[$i]);
            if($valores[0]!='REUNIÓN')
                $horario_diurno=1;
        }
    }
     //si hay una clase entre la posicion 8 y 13 significa que da clase por la tarde
    for($i=8;$i<15;$i++){
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
    
    $config = parse_ini_file('../configuracion/config_importacion.ini');
    
    $horas = array();
  
    //se le pasa la fila del csv y el inicio del día que queremos obtener
    $horas["LUNES"]=recuperaHoras($lista_horas,$config['inicio_lunes']);
    $horas["MARTES"]=recuperaHoras($lista_horas,$config['inicio_martes']);
    $horas["MIERCOLES"]=recuperaHoras($lista_horas,$config['inicio_miercoles']);
    $horas["JUEVES"]=recuperaHoras($lista_horas,$config['inicio_jueves']);
    $horas["VIERNES"]=recuperaHoras($lista_horas,$config['inicio_viernes']);

   
   return $horas;
    
}

function obtenerHorasNocturno($lista_horas){
    
    $horas = array();
    $config = parse_ini_file('../configuracion/config_importacion.ini');
  
   //se le pasa la fila del csv y el inicio del día que queremos obtener
   $horas["LUNES"]=recuperaHoras($lista_horas,$config['inicio_lunes_nocturno']);
    $horas["MARTES"]=recuperaHoras($lista_horas,$config['inicio_martes_nocturno']);
    $horas["MIERCOLES"]=recuperaHoras($lista_horas,$config['inicio_miercoles_nocturno']);
    $horas["JUEVES"]=recuperaHoras($lista_horas,$config['inicio_jueves_nocturno']);
    $horas["VIERNES"]=recuperaHoras($lista_horas,$config['inicio_viernes_nocturno']);

   
    return $horas;
    
}


function recuperaHoras($lista,$inicio){
    
    return array_slice($lista,$inicio,6);
    
}
?>
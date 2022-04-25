
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
require_once '../comun/cabecera.php';
require_once "../DTO/horario.php";

if(isset($_POST["fichero"]))
      $fichero = $_POST["fichero"]; 



$fichero = $_FILES["fichero"];

$mensajeFichero = validarFichero($fichero);



 echo '<div class="container">
  <h2>Horarios que se van a cargar</h2>';

  echo $mensajeFichero;

  echo '<a href="insertarDatos.php?fichero='.basename($fichero["name"]).'" class="btn btn-info" role="button">Grabar horarios</a>
  <div class="text-right">
            <a href="formularioCargarHorarios.php" class="btn btn-warning" role="button">Volver</a>
  </div>';
  
 	
$horarios = leerHorariosDelFichero($fichero);

$tablahorarios = mostrarHorariosDocentes($horarios);

echo $tablahorarios;

/**
 * Función que recibe el nombre del fichero y devuelve la ruta de dicho fichero
 * Para ello utiliza el fichero de configuración config_importacion.ini donde se recoge
 * el nombre de la carpeta donde almacenar el fichero.
 * 
 */
function obtenerRutaFichero($fichero){

    $nombreFichero = basename($fichero["name"]);
   

    // Carga la configuración 
    $config = parse_ini_file('../configuracion/config_importacion.ini');

    $directorio = "../".$config['nombre_directorio'];

    $ruta_fichero = $directorio .'/'. $nombreFichero;


    return $ruta_fichero;

}

/**
 * Funció que recibe el nombre del fichero recuperado del formulario y 
 * realiza una serie de validaciones:
 * - Comprueba que existe el fichero.
 * - Comprueba el tamaño del fichero.
 * - Valida la extensión csv del fichero.
 * 
 * Si está todo bien, sube el fichero a una carpeta dentro del servidor.
 */

function validarFichero($fichero){

    $ruta_fichero =  obtenerRutaFichero($fichero);

    $avisos ='<div class="w3-container w3-blue"><p>FICHERO:'.$ruta_fichero.'</p></div>';

    $uploadOk = 1;

    $tipoFichero = strtolower(pathinfo($ruta_fichero,PATHINFO_EXTENSION));

    // Comprobar que el fichero ya existe
    if (file_exists($ruta_fichero)) {
        $mensaje ='<div class="w3-container w3-blue"><p>El fichero ya existe</p></div>';
        $avisos.=$mensaje;
        $uploadOk = 0;
    }
    // Comprobar el tamaño del fichero
    if ($fichero["size"] > 500000) {
        $mensaje ='<div class="w3-container w3-red"><p>El fichero es demasiado grande</p></div>';
        $avisos.=$mensaje;
        $uploadOk = 0;
    }
    // Comprobar que el fichero es tipo csv
    if($tipoFichero != "csv") {
        $mensaje ='<div class="w3-container w3-red"><p>Sólo están permitidos los ficheros csv</p></div>';
        $avisos.=$mensaje;
        $uploadOk = 0;
    }
    // Se comprueba si se ha dado algún caso anterior de error.
    if ($uploadOk == 0) {
        $mensaje ='<div class="w3-container w3-blue"><p>El fichero no ha sido subido</p></div>';
        $avisos.=$mensaje;
    // Si todas las comprobaciones han ido bien, intenta subir el fichero.
    } else {
        if (move_uploaded_file($fichero["tmp_name"], $ruta_fichero)) {
            $mensaje ='<div class="w3-container w3-green"><p>El fichero '. basename( $fichero["name"]). ' ha sido subido</p></div>';
            $avisos.=$mensaje;
        } else {
            $mensaje ='<div class="w3-container w3-red"><p>Error al subir el ficheros</p></div>';
            $avisos.=$mensaje;
        }
    }

    return $avisos;

 }


/**
 * Función que recibe el fichero csv(ruta+nombre fichero) que leerá linea por línea
 * creando un array por cada fila. 
 * Devolverá un array de objetos de tipo Horario donde cada objeto representa
 * el horario de un docente.
 */
function leerHorariosDelFichero($fichero){

    $ruta_fichero =  obtenerRutaFichero($fichero);

    $horarios=array(); // array donde se almacenarán los horarios de todos los docentes haciendo uso de objetos horario.
    //Abrimos nuestro archivo
    $fichero = fopen($ruta_fichero, "r") or die("No se puede abrir el fichero!");
    //Lo recorremos
    while (($fila = fgetcsv($fichero, ",")) == true) 
    {   
        //por cada fila del fichero csv, obtenemos un objeto de tipo Horario
        $horario=cargarHorarioDocente($fila);   
        //Se debe sustituir por un INSERT en las tablas correspondientes.     
        //$id_docente = insentarDocente($fila[0]);
        //insertarHorario($id_docente,$horario);

        //Almacenamos el objeto Horario en el array de horarios
        array_push($horarios,$horario); 

    } // fin del bucle while 

    fclose($fichero);

    return $horarios;

}

/**
 * 
 */
function insentarDocente($cadena_docente){
    
  require('./bd/conexion.php');
   
  $sql = 'INSERT INTO docente( nombre) VALUES ("'.$cadena_docente.'")';

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  //recuperamos el id del elemento insertado
  $id = mysqli_insert_id($connection); 

  mysqli_close($connection);

  return $id;
}

/**
 * 
 */
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
 * 
 */

function grabarhorarios($docente,$horas_docente,$dia){
    
    $grupo="";
    $aula="";
    $guardia="";
    $materia="";
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
        $sql='INSERT INTO `horario`(`idProfesor`, `dia`, `hora`,materia ,`grupo`, `aula`, `guardia`) VALUES ('.$docente.','.$dia.','.$hora.','.$materia.','.$grupo.','.$aula.','.$guardia.')';
        
        echo $sql;  
    }// fin bucle for
    
}

/**
 * Esta función se encarga de crear y devolver un objeto horario el cual almacena
 * el docente, el tipo de horario y un array asociativo con el horario de los cinco 
 * días de la semana.
 * Recibe como parámetro el array que se ha obtendo de la fila del fichero csv correspondiente a un docente.
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


/**
 * Función que obtiene un array con objetos de tipo Horario que continene los
 * horarios de los docentes.
 * Muestra por pantalla el contenido del array en formato horario
 */

function mostrarHorariosDocentes($horarios){

    
    $html=' <div class="container-fluid">
    <div class="row">
      <div class="col-sm bg-info celdaTituloH">H</div>
       <div class="col-sm bg-info">LUNES</div>
        <div class="col-sm bg-info">MARTES</div>
         <div class="col-sm bg-info">MIERCOLES</div>
          <div class="col-sm bg-info">JUEVES</div>
           <div class="col-sm bg-info">VIERNES</div>
            
      
    </div>'; 
    
    foreach($horarios as $horario)
	{
        $html =$html.'<div class="row">';
        $html =$html.'<div class="col-sm-12 bg-warning">'.$horario->getDocente().'-'.$horario->getTipo_horario().'</div>';
        $html =$html.'</div>';

            
           $horas=$horario->getHoras(); // horas es un array bidemensional, cuya primera dimensión son los días de la semana
            $lunes=$horas['LUNES'];
            $martes=$horas['MARTES'];
            $miercoles=$horas['MIERCOLES'];
            $jueves=$horas['JUEVES'];
            $viernes=$horas['VIERNES'];
           
           for($i=0;$i<6;$i++){ //buvle para recorrer las 6 horas de cada día
               $html =$html.'<div class="row">';
               $html =$html.'<div class="col-sm-2 bg-info">'.($i+1).'</div>';
               $html =$html.'<div class="col-sm-2 ">'.$lunes[$i].'</div>';
               $html =$html.'<div class="col-sm-2 ">'.$martes[$i].'</div>';
               $html =$html.'<div class="col-sm-2 ">'.$miercoles[$i].'</div>';
               $html =$html.'<div class="col-sm-2 ">'.$jueves[$i].'</div>';
               $html =$html.'<div class="col-sm-2 ">'.$viernes[$i].'</div>';
               $html =$html.'</div>';
           }
       
	}// fin foreach
    
  $html =$html.'</div>';
    
    return $html;
}


/**
 * Función que recibe una fila de un horario y se obtiene el tipo el tipo de horario.
 * Dependiendo de las posiciones que hay rellenas en la fila se podrá saber de
 * qué tipo es el horario.
 * Se tiene 4 posibles tipos de horarios:
 * -horario diurno
 * -horario nocturno
 * -horario partido
 * -horario avanza 
*/
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

/**
 * Función que recibe un array con todas las horas semanales de un docente.
 * Devuelve un array asociativo de 5 posiciones, donde cada posición es otro 
 * array con las horas por día del docente.
 * Es para los horarios diurnos, con lo cual se utilizan los índices donde empiezan
 * cada día en la fila. La posición de inicio de cada día se obtiene del fichero
 * de configuración config_importacion.ini
 */
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

/**
 * Función que recibe un array con todas las horas semanales de un docente.
 * Devuelve un array asociativo de 5 posiciones, donde cada posición es otro 
 * array con las horas por día del docente.
 * Es para los horarios nocturnos, con lo cual se utilizan los índices donde empiezan
 * cada día en la fila. La posición de inicio de cada día se obtiene del fichero
 * de configuración config_importacion.ini
 */
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


/**
 * Función que recibe un array con todo el horario del docente y el inicio 
 * del día que se quiere obtener. 
 * Como son 6 horas cada día, devolverá un array con 6 posiciones correspondientes
 * a las 6 horas del día.
 */

function recuperaHoras($lista,$inicio){

    return array_slice($lista,$inicio,6);
}


?>
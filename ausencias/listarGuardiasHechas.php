
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../comun/cabecera.php';
require_once 'AusenciasDAO.php';
require_once 'GuardiaDao.php';

$ausenciaDAO = new AusenciasDAO();
$guardiaDAO = new GuardiaDao();

if(isset($_GET["semana"]))
      $semana = $_GET["semana"]; 
if(isset($_GET["fecha"]))
      $fecha = $_GET["fecha"]; 
if(isset($_GET["dia"]))
      $dia = $_GET["dia"]; 
if(isset($_GET["hora"]))
      $hora = $_GET["hora"]; 




$datosVolver='semana='.$semana.'&dia='.$dia.'&fecha='.$fecha; 
//PASO 1: obtener un array con los id de los profesores que están de guardia en un dia y hora determinados
$docentesEnGuardia = $guardiaDAO -> obtenerDocentesGuardia($dia,$hora);


$listaIdsDocentesGuardia = array();
$listaDocentesGuardia =  array();
if ($docentesEnGuardia->num_rows > 0) {
    while($docente = $docentesEnGuardia->fetch_assoc()) {       
        //array_push($listaIdsDocentesGuardia ,$docente["idProfesor"]); 
        //inicializamos el array que continene en cada elemento el id del docente asociado a la guardia que inicialmente esta vacia
        $listaDocentesGuardia[$docente["idProfesor"]] =  "-";
        $listaIdsDocentesGuardia[$docente["idProfesor"]]["nombre"]=$docente["nombre"];
    } 
}



//PASO 2: obtener el número de guardias hechas por cada docente en un dia y hora determinado NO FUNCIONA
$guardiasHechas = $ausenciaDAO ->obtener_guardias_hechas($dia,$hora);

foreach ($guardiasHechas as $guardiaHecha) {
   
        $listaIdsDocentesGuardia[$guardiaHecha["idDocente"]]["total"]=$guardiaHecha["guardiasHechas"];

       
    
}

//PASO 3: obtener guardias hechas por docente en un dia y hora.
$guardiasHechasPorSemana = $ausenciaDAO ->obtener_guardias_hechas_por_profesor($dia,$hora);



//obtener un array asociativo donde las claves son las fechas en las que se ha hecho guardia
$listaFechas = array();
$datosGuardia =  array();
foreach ($guardiasHechasPorSemana as $guardia) {
    if(!array_key_exists($guardia['fechaGuardia'],$datosGuardia)){

      
        //echo $guardia['fechaGuardia'].'-'.$guardia['idProfGuardia'].'-'.$guardia['nombre'].'-'.$guardia['grupo'].'-'.$guardia['aula'] ;
        //usamos el array de los id de los docentes vacio
       //var_dump($guardia);
       
       $datosGuardia[$guardia['fechaGuardia']]=array();
       $datosGuardia[$guardia['fechaGuardia']]=$listaDocentesGuardia;
      
        //$guardia['fechaGuardia']= $listaDocentesGuardia;
       // $fecha= $guardia['fechaGuardia'];
        //$listaFechas[$fecha];//=$listaDocentesGuardia;
        //array_push($listaFechas,$guardia['fechaGuardia']);
       
       
    }

    $datosGuardia[$guardia['fechaGuardia']][$guardia['idProfGuardia']]=$guardia['grupo'].'-'.$guardia['aula'];
}










$nombreDia="";
switch($dia){
    case 1: $nombreDia='LUNES';break;
    case 2: $nombreDia='MARTES';break;
    case 3: $nombreDia='MIERCOLES';break;
    case 4: $nombreDia='JUEVES';break;
    case 5: $nombreDia='VIERNES';break;
}

?>
<div class="w3-container">
<div class="w3-row" >
    <div class="w3-col m8 texto_titulo">  <h2>GUARDIA <?php echo $nombreDia ?>  - <?php echo $hora ?>ªHORA </h2> </div>
    
    <div class="w3-col m4  w3-center "><a class="btn btn-info btn-volver boton" href="listarAusenciasGuardias3.php?<?php echo $datosVolver ?>">Volver </a></div>

    </div> 
    
    </div> 

<?php


echo '<div class="w3-container contenedortabla">';
echo '<table class="w3-table">';

echo '<tr class="fila2">';
    echo '<td class="cabeceraTabla">';
    echo '<img class="icono_peque" src="../imagenes/icono_calendario.png">';
    echo '</td>';
foreach ($listaIdsDocentesGuardia as $docenteGuardia){
    echo '<td class="cabeceraTabla">';
         echo $docenteGuardia["nombre"];
         echo "<span class='total_guardias'>TOTAL:".$docenteGuardia["total"]."</span>";
    echo '</td>';
}
echo '</tr>';

foreach ($datosGuardia as $fecha => $guardias) {
    echo '<tr class="fila2">';
    echo '<td class="celdaGuardias">';
    
    //original date is in format YYYY-mm-dd
    $timestamp = strtotime($fecha); 
    $fechaFormateada = date("d-m-Y", $timestamp );
    echo $fechaFormateada;
    echo '</td>';
    foreach ($guardias as $guardia) {
        echo '<td class="celdaGuardias">';
       
            echo $guardia;
       
        
        echo '</td>';
    }
   
    echo '</tr>';
}

echo '</table>';
echo '</div>';

echo '</body>
</html>
';

?>
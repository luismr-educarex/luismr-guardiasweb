
<?php

require_once '../comun/cabecera.php';
require_once 'AusenciasDAO.php';
require_once 'GuardiaDAO.php';

$ausenciaDAO = new AusenciasDAO();
$guardiaDAO = new GuardiaDAO();

if(isset($_GET["semana"]))
      $semana = $_GET["semana"]; 
if(isset($_GET["fecha"]))
      $fecha = $_GET["fecha"]; 
if(isset($_GET["dia"]))
      $dia = $_GET["dia"]; 
if(isset($_GET["hora"]))
      $hora = $_GET["hora"]; 




$datosVolver='semana='.$semana.'&dia='.$dia.'&fecha='.$fecha; 
//obtener un array con los id de los profesores que están de guardia en un dia y hora determinados
$docentesEnGuardia = $guardiaDAO -> obtenerDocentesGuardia($dia,$hora);
$listaIdsDocentesGuardia = array();
if ($docentesEnGuardia->num_rows > 0) {
    while($docente = $docentesEnGuardia->fetch_assoc()) {       
        array_push($listaIdsDocentesGuardia ,$docente["idProfesor"]); 
    } 
}


$guardias = $ausenciaDAO ->obtener_guardias_hechas($dia,$hora);

$guardiasFechas = $ausenciaDAO ->obtener_guardias_hechas_por_profesor($dia,$hora);



//obtener un array asociativo donde las claves son las fechas en las que se ha hecho guardia
$listaFechas = array();
foreach ($guardiasFechas as $guardia) {
    if(!in_array($guardia['fechaGuardia'],$listaFechas)){
        array_push($listaFechas,$guardia['fechaGuardia']);
       
    }
}

$listaFechasGuardias = array();
foreach ($guardiasFechas as $guardia) {
    $fecha = $guardia['fechaGuardia'];
    $profesorGuardia = $guardia['idProfGuardia'];
   
    $listaFechasGuardias[$fecha][$profesorGuardia] = $guardia['grupo'].'-'.$guardia['aula'];
    
}


// se recorre el array anterior pero comprobrando si están todos los docentes.
//si no está un docente es que no ha hecho guardia ese día, entonces añadimos un símbolo -
foreach ($guardiasFechas as $guardia) {
    $fecha = $guardia['fechaGuardia'];
    
    foreach ($listaIdsDocentesGuardia as $docente) {
        if(!isset($listaFechasGuardias[$fecha][$docente])){
      
         $listaFechasGuardias[$fecha][$docente] = "-";

    }
}
}








$nombreDia="";
switch($dia){
    case 1: $nombreDia='LUNES';break;
    case 2: $nombreDia='MARTES';break;
    case 3: $nombreDia='MIERCOLES';break;
    case 4: $nombreDia='JUEVES';break;
    case 5: $nombreDia='VIERNES';break;
}


// se pasa los resultados obtenidos de la consulta de la base de datos a un array asociativo.
$listaGuardias = array();
if ($guardias->num_rows > 0) {
    while($guardia = $guardias->fetch_assoc()) {       
        $listaGuardias []= $guardia;   
    } 
}

function mostrarCabeceraTablaGuardias($lista){

    $html='
    <table class="w3-table w3-bordered w3-striped w3-border">
<thead>
<tr class="filaGuardias">
  <th>Día</th>';

  foreach($lista as $guardia){
    $html.= '<th>'.$guardia["nombreDocente"].' <span class="w3-badge w3-large w3-padding w3-green">'.$guardia["guardiasHechas"].'</span></th>';
  }


  $html.='</tr></thead>'; 
    
   return $html;
}


?>

<div class="w3-row" >
    <div class="w3-col m8 w3-center">  <h2>GUARDIA <?php echo $nombreDia ?>  - <?php echo $hora ?>ªHORA </h2> </div>
    
    <div class="w3-col m4  w3-center "><a class="btn btn-info btn-volver boton" href="listarAusenciasGuardias3.php?<?php echo $datosVolver ?>">Volver </a></div>

    </div>    
</div>

<?php

$tabla =  mostrarCabeceraTablaGuardias($listaGuardias);

echo $tabla;


foreach ($listaFechas as $fecha) {
    echo '<tr class="filaGuardias">';
    echo "<td>";
    echo $fecha;
    echo "</td>";
    foreach ($listaIdsDocentesGuardia as $docente) {
        echo "<td>";
        echo '<span class=" w3-large w3-padding w3-green">'.$listaFechasGuardias[$fecha][$docente].'</span>';
        echo "</td>";
    
   }
   echo '</tr>';
   
}
echo '</table>';


echo '</body>
</html>
';

?>
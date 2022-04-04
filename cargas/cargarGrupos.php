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
    
   </style>    
    
</head>

<?php
session_start();
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
         $mensaje ='<div class="w3-container w3-green"><p>El fichero '. basename( $_FILES["fichero"]["name"]). ' ha sido subido.';
        $mensajeFichero.=$mensaje;
    } else {
         $mensaje ='<div class="w3-container w3-red"><p>Error al subir el fichero.</p></div>';
          $mensajeFichero.=$mensaje;
    }
}

$fichero = fopen($ruta_fichero, "r") or die("No se puede abrir el fichero!");


 echo '<div class="container">
  <h2>Grupos que se van a cargar</h2>';

  echo $mensajeFichero;

  echo '<a href="cargarGruposDesdeFichero.php?fichero='.$ruta_fichero.'" class="btn btn-info" role="button">Cargar grupos</a>
  <div class="text-right">
            <a href="cargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
 
  <table class="table" style="font-size:90%;">
    <thead>
      <tr>
        <th>NOBRE CORTO</th>
        <th>NOMBRE LARGO</th>
        <th>CURSO</th>
        <th>DNI</th>
      </tr>
    </thead><tbody>';
 	


$fila = 0;
 $nombresMateria=array();
while(!feof($fichero))
{   
   
    $linea=fgets($fichero);
    if($fila!=0){
       
        $campos = explode(";", $linea);
       
        if(sizeof($campos)==9){ //Esto se hace para comprobar si hay 4 elementos en el array campos

        echo '<tr data-tipo="'.$campos[1].'">
          <td class="textoTabla">'.utf8_encode($campos[0]).'</td>
         <td class="textoTabla">'.utf8_encode($campos[1]).'</td>
         <td class="textoTabla">'.$campos[8].'</td>
        </tr>';
              
        }    
        array_push($nombresMateria,utf8_encode($campos[1]));
       
        /* 
        0 NOMBRE CORTO	
        1 NOMBRE LARGO
        9 CURSO
        */
       
    }
    $fila++;
} // fin while
 echo "</tbody></table>";

$_SESSION["listaMaterias"] = $nombresMateria;
fclose($fichero);


?>
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
      position: absolute;
      background-color: white;
      height: 100%;
    }

    .first-col {
      padding-left:100.5px!important;
    }
    .guardia{
        
         background-color: darkseagreen;
    }
    
   </style>    
    
</head>

<?php
session_start();

$listaMaterias = $_SESSION["listaMaterias"];


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

  echo '<a href="cargarHorariosDesdeFichero.php?fichero='.$ruta_fichero.'" class="btn btn-info" role="button">Cargar horarios</a>
  <div class="text-right">
            <a href="cargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
  
  <div style="overflow:scroll;">
  <table class="table" style="font-size:90%;">
    <thead>
      <tr>
        <th class="static">DOCENTE</th>
        <th class="first-col">L1</th>
        <th>L2</th>
        <th>L3</th>
        <th>L4</th>
        <th>L5</th>
        <th>L6</th>
        <th>L7</th>
        <th>M1</th>
        <th>M2</th>
        <th>M3</th>
        <th>M4</th>
        <th>M5</th>
        <th>M6</th>
        <th>M7</th>
        <th>X1</th>
        <th>X2</th>
        <th>X3</th>
        <th>X4</th>
        <th>X5</th>
        <th>X6</th>
        <th>X7</th>
      </tr>
    </thead><tbody>';
 	
$linea = 0;
//Abrimos nuestro archivo


//Lo recorremos
while (($datos = fgetcsv($fichero, ";")) == true) 
{
  $num = count($datos);
  $linea++;
  //Recorremos las columnas de esa linea
    echo '<tr>';
    
    for($i=0;$i<=24;$i++){
        $cadena=$datos[$i];
        
        $estilo="";
        if($i==0){
           $estilo="static";
        }else if($i==1){
             $estilo="first-col";        
        }
        
        $lon = strlen ($cadena);
        $pos1 = stripos($cadena, '(');
        $pos2 = stripos($cadena, ')');
        if($pos1){ //si hay un aula entre ()
            
            $recorrido=($pos2)-$pos1;
            $rest = substr($cadena, $pos1+1, $recorrido-1);
            echo '<td class="textoTabla '.$estilo.'">'.$rest.'</td>';
        }else{  
            
            if(identificarGuardias($cadena)){
                 echo '<td class="textoTabla guardia '.$estilo.'">'.$cadena.'</td>';
            }else{
                  echo '<td class="textoTabla '.$estilo.'">'.$cadena.'</td>';
            }
        }
        
        
    }
    
    echo '</tr>';
    
        
     
}
//Cerramos el archivo
fclose($fichero);

/*
$fila = 0;
while(!feof($fichero))
{   
   
    $linea=fgets($fichero);
    echo $linea.'<br>';
    if($fila>=0){
        $campos = explode(";", $linea);
        echo sizeof($campos).'<br>';
        if(sizeof($campos)==66){ //Esto se hace para comprobar si hay 65 elementos en el array campos
        
        echo '<tr data-tipo="'.$campos[1].'">
          <td class="textoTabla">'.$campos[0].'</td>
         <td class="textoTabla">'.$campos[1].'</td>
         <td class="textoTabla">'.$campos[2].'</td>
         <td class="textoTabla">'.$campos[3].'</td>
         <td class="textoTabla">'.$campos[4].'</td>
         <td class="textoTabla">'.$campos[5].'</td>
         <td class="textoTabla">'.$campos[6].'</td>
         <td class="textoTabla">'.$campos[7].'</td>
        </tr>';
              
        }else{
            echo 'Fila sin campos suficientes'.'<br>';
            
        }
        
    }else{
        echo 'Estamos en las dos primeras filas';
    }
    $fila++;
}
 echo "</tbody></table>";

fclose($fichero);
*/
echo "</tbody></table></div>";

function identificarGuardias($cadena){
    
    if (strpos($cadena, 'GUARDIA') !== false){
        if( strrpos($cadena,"Biblio")==false){
                    return true;
            
        }else{
             return false;
        }

    }else{
        return false;
    }
}

?>
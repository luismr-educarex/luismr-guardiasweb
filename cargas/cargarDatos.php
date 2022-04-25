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


require_once './docente/DocenteDAO.php';
require_once './docente/Docente.php';
require './bd/Datasource.php';
$config = parse_ini_file('./bd/configBD.ini');

$ds = new Datasource('localhost',$config['dbname'],$config['username'],$config['password']);
$docenteDAO = new DocenteDAO();
$docente = new Docente();  

$ruta_fichero="";
if(isset($_GET["fichero"]))
      $ruta_fichero = $_GET["fichero"]; 
echo $ruta_fichero;

$fichero = fopen($ruta_fichero, "r") or die("No se puede abrir el fichero!");


 echo '<div class="container">
  <h2>Horarios que se van a cargar</h2>';

  echo '
  <div class="text-right">
            <a href="formularioCargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
  
  <div style="overflow:scroll;">
  
  ';
 	
//Abrimos nuestro archivo
//Lo recorremos
while (($datos = fgetcsv($fichero, ";")) == true) 
{
  $num = count($datos);

  $lunes=array_unique(array_slice($datos,1,6));
  echo 'hueco:'.array_search('',$lunes).'<br>';
  $contadores=array_count_values($lunes);
  $horas_lunes =  count($lunes);
    
  echo "HORAS LUNES:".$horas_lunes.'<br>';
    
    
  $tipo="";
   
    if($datos[1]!=''){
        echo "HORARIO DE MAÑANA";
        $tipo='m';
    }else{
        echo "HORARIO DE TARDE";
        $tipo='t';
    }
    
    
    //Recorremos las columnas de esa linea
    for($i=0;$i<=65;$i++){
        $cadena=$datos[$i];
        
        
        $estilo = "";
        if($i==0){
            echo $cadena;
            //insertarmos profesor
            echo "_____________________ PROFESOR _______________________<br>";
            echo 'INSERT INTO `docente`(`nombre`) VALUES ('.$cadena.')'.'<br>';
            
       
            $docente->setNombre($cadena); 
            $docente->setCodigo($cadena); 
            $docente->setDepartamento(''); 
            $docente->setRol(''); 
        
            
            $id_docente= $docenteDAO->add($docente);
            
            echo 'Insertado el docente con id:'.$id_docente.'<br>';
            
            
        }else{
       
          
          $valores =  explode("\n", $cadena); //Obtenemos en un array los valores de las celdas del fichero csv.
           //print_r($valores);
          
           if(sizeof($valores)>0){
               //insertarmos los valores
               $guardia=0;
               $grupo=$valores[0];
                
               if($grupo=='GUARDIA'){
                   $guardia=identificarGuardias($valores[1]);
                   $aula='';
               }else if($grupo=='REUNIÓN')
               {
                   $aula='';
               }else if($grupo!=''){
                   $aula=$valores[2];
               }else{
                   $aula='';
               }
               
               $dia=obtenerDia($i);
               $hora=obtenerHora($i);
               echo 'INSERT INTO `horario`(`idProfesor`, `dia`, `hora`, `grupo`, `aula`, `guardia`) VALUES ([value-1],'.$dia.','.$hora.','.$grupo.','.$aula.','.$guardia.')'.'<br>';
              
           }else{
               //insertamos hueco LIBRE
               echo 'INSERT INTO `horario`(`idProfesor`, `dia`, `hora`, `grupo`, `aula`, `guardia`) VALUES ([value-1],'.$dia.','.$hora.',LIBRE,,)'.'<br>';
           }
        }
               
        
    }
         
      
     
}
//Cerramos el archivo
fclose($fichero);


echo "</div>";

function obtenerTipoHorario($lista_horas){
    
   
}

function obtenerDia($num_Dia){
    
    if($num_Dia>=1 && $num_Dia<=13){
        $dia=1;
    }
    if($num_Dia>=14 && $num_Dia<=26){
         $dia=2;
    }
    if($num_Dia>=27 && $num_Dia<=39){
         $dia=3;
    }
    if($num_Dia>=40 && $num_Dia<=52){
         $dia=4;
    }
    if($num_Dia>=53 && $num_Dia<=65){
         $dia=5;
    }
    
    return $dia;
   
}

function obtenerHora($num_Dia){
    
    $primera = array(1,8,14,21,27,34,40,47,53,60);
    $segunda = array(2,9,15,22,28,35,41,48,54,61);
    $tercera = array(3,10,16,23,29,36,42,49,55,62);
    $cuarta = array(4,11,17,24,30,37,43,50,56,63);
    $quinta = array(5,12,18,25,31,38,44,51,57,64);
    $sexta = array(6,13,19,26,32,39,45,52,58,65);
    
    if (in_array($num_Dia, $primera)){
        $hora=1;
    }
    else if (in_array($num_Dia, $segunda)){
       $hora=2;
    }
    else if (in_array($num_Dia, $tercera)){
        $hora=3;
    }
    else if (in_array($num_Dia, $cuarta)){
        $hora=4;
    }
    else if (in_array($num_Dia, $quinta)){
       $hora=5;
    }
     else if (in_array($num_Dia, $sexta)){
        $hora=6;
    }else{
         $hora=7;
     }
    
    return $hora;
}

function identificarGuardias($cadena){
    
    $tipo=0;
        if( strrpos($cadena,"Guardia")==false){
                   $tipo=1;
            
        }

    return $tipo;
}
?>
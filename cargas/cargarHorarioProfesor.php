

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
    
    

<?php


    
require_once './horario/HorarioDAO.php';

    
require_once './horario/Horario.php';

require './bd/Datasource.php';
$config = parse_ini_file('./bd/configBD.ini');

$ds = new Datasource('localhost',$config['dbname'],$config['username'],$config['password']);    

    
$horarioDAO = new HorarioDAO();
$horario = new Horario();
    
    
$horario->setIdProfesor(2);
    
$listaHorario = $horarioDAO->searchMatchingByProfesor($ds,$horario);
 
$dias = [
    'lunes',
    'martes',
    'miercoles',
    'jueves',
    'viernes'
];    

$horas = [
    'lunes' => array(),
    'martes' => array(),
    'miercoles' => array(),
    'jueves' => array(),
    'viernes' => array()
];    

    foreach( $listaHorario as $hora ) {
           
          switch($hora->getDia()){
                    
                     case 1:
                           echo $hora->getDia();
                           array_push($horas['lunes'],$hora); 
                           echo sizeof($horas['lunes']);
                      break;  

                   case 2:
                           array_push($horas['martes'],$hora); 
                      break;
                     case 3:
                        array_push($horas['miercoles'],$hora); 
                      break;  

                   case 4:
                         array_push($horas['jueves'],$hora); 
                      break;
    
                   case 5:
                         array_push($horas['viernes'],$hora); 
                      break;
                      default:
                    
            }
         }

   
    foreach ($horas['lunes'] as $row) {
          echo $row->getGrupo();
       
    }
    foreach ($horas['martes'] as $row) {
           echo $row->getGrupo();
    }

?>
    
    
<body>

<div class="container">
  <h2>HORARIO</h2>
  <p></p>                                                                                      
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>HORA/DIA</th>
        <th>LUNES</th>
        <th>MARTES</th>
        <th>MIERCOLES</th>
        <th>JUEVES</th>
        <th>VIERNES</th>
      </tr>
    </thead>
    <tbody>
      
        <?php
          $dia=0;
        
        for ($hora = 1; $hora <=6; $hora++) {
      
             echo  '<tr>';
                 echo '<td>'.$hora.'</td>';
                 echo  '<td>'.$horas['lunes'][$dia]->getGrupo().'</td>';
                 echo  '<td>'.$horas['martes'][$dia]->getGrupo().'</td>';
                 echo  '<td>'.$horas['miercoles'][$dia]->getGrupo().'</td>';
                 echo  '<td>'.$horas['jueves'][$dia]->getGrupo().'</td>';
                 echo  '<td>'.$horas['viernes'][$dia]->getGrupo().'</td>'; 
             echo '</tr>';
            
            $dia++;
            
            } 
           
        
        ?>
        
     
    </tbody>
  </table>
  </div>
</div>

</body>
</html>
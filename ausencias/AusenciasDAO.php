<?php

class AusenciasDAO {


public function crear_ausencia($guardia) {

  require('../bd/conexion.php');
     
  $profesor = $guardia->getIdProfesor();
  $semana = $guardia->getSemana();
  $dia = $guardia->getDia();
  $hora = $guardia->getHora();
  $idhora = $guardia->getIdHora();
  $fecha = $guardia->getFechaGuardia();  
    
  $fecha = strtotime($fecha);    
  $fechaMysql = date('Y-m-d',$fecha); 
  
  $sql  = "INSERT INTO `guardia`
  ( `horario`,`idProfesor`, `semana`, `dia`, `hora`,fechaGuardia) 
  VALUES (".$idhora.",".$profesor.",".$semana.",".$dia.",".$hora.",'".$fechaMysql."')";

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

  //recuperamos el id del elemento insertado
  $id = mysqli_insert_id($connection); 
    
  mysqli_close($connection);

  return $sql;

}
    
    
public function borrar_ausencia($guardia) {

  require('../bd/conexion.php');
     
  $profesor = $guardia->getIdProfesor();
  $semana = $guardia->getSemana();
  $dia = $guardia->getDia();
  $hora = $guardia->getHora();
  
  $sql  = "DELETE FROM `guardia`
  WHERE idProfesor=".$profesor." AND semana=".$semana." AND dia=".$dia."  AND hora=".$hora;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

  //recuperamos el id del elemento insertado
  $id = mysqli_insert_id($connection); 
    
  mysqli_close($connection);

  return $sql;

}
    
public function guardar_tarea($guardia) {

  require('../bd/conexion.php');
     
  $profesor = $guardia->getIdProfesor();
  $semana = $guardia->getSemana();
  $dia = $guardia->getDia();
  $hora = $guardia->getHora();
  
  $sql  = "UPDATE `guardia` SET tarea=1
   WHERE idProfesor=".$profesor." AND semana=".$semana." AND dia=".$dia."  AND hora=".$hora;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

  mysqli_close($connection);
    
  return $sql;

}
    
public function borrar_tarea($guardia) {

  require('../bd/conexion.php');
     
  $profesor = $guardia->getIdProfesor();
  $semana = $guardia->getSemana();
  $dia = $guardia->getDia();
  $hora = $guardia->getHora();
  
  $sql  = "UPDATE `guardia` SET tarea=0
   WHERE idProfesor=".$profesor." AND semana=".$semana." AND dia=".$dia."  AND hora=".$hora;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

    
  return $result;
}
    
public function guardar_observacion($guardia) {

  require('../bd/conexion.php');
     
  $profesor = $guardia->getIdProfesor();
  $semana = $guardia->getSemana();
  $dia = $guardia->getDia();
  $hora = $guardia->getHora();
  $observaciones = $guardia->getObservaciones();
  
  $sql  = "UPDATE `guardia` SET observaciones='".$observaciones."'  
   WHERE idProfesor=".$profesor." AND semana=".$semana." AND dia=".$dia."  AND hora=".$hora;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

  mysqli_close($connection);
    
  return $sql;

}
    
public function borrar_observacion($guardia) {

  require('../bd/conexion.php');
     
  $profesor = $guardia->getIdProfesor();
  $semana = $guardia->getSemana();
  $dia = $guardia->getDia();
  $hora = $guardia->getHora();
  
  $sql  = "UPDATE `guardia` SET tarea=0
   WHERE idProfesor=".$profesor." AND semana=".$semana." AND dia=".$dia."  AND hora=".$hora;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

    
  return $result;
}
    
   
    
public function obtener_ausencias($semana,$dia) {

  require('../bd/conexion.php');
     
  $sql  = "SELECT g.id,horario idhora,g.dia, g.hora,h.grupo,h.aula,g.idProfesor,tarea,observaciones,nombre,idProfGuardia FROM guardia g INNER JOIN docente d ON g.idProfesor=d.id INNER JOIN horario h ON g.horario=h.id WHERE semana=".$semana." AND g.dia=".$dia;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

  return $result;
}


public function obtener_ausencia_docente($idDocente){

  require('../bd/conexion.php');
     
  //$sql  = "SELECT * FROM guardia g LEFT JOIN docente d ON g.idProfesor=d.id WHERE idProfesor=".$idDocente." ORDER BY semana DESC,dia DESC,hora DESC";
  $sql = "SELECT g.idProfesor,g.fechaGuardia,g.hora,h.grupo,h.aula,dg.nombre docenteGuardia  FROM guardia g 
  LEFT JOIN docente d ON g.idProfesor=d.id 
  JOIN horario h ON h.id=horario
  LEFT JOIN docente dg ON g.idProfGuardia=dg.id
  WHERE g.idProfesor=".$idDocente." ORDER BY g.semana DESC,g.dia DESC,g.hora ASC;";

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

  return $result;
}


public function guardar_docenteGuardia($guardia) {

  require('../bd/conexion.php');
     
  $ausencia = $guardia->getId();
  $docenteGuardia = $guardia->getIdProfGuardia();

  $sql  = "UPDATE `guardia` SET idProfGuardia='".$docenteGuardia."'  
   WHERE id=".$ausencia;

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

  mysqli_close($connection);
    
  return $sql;

}
    
public function obtener_ausencias_por_dia_en_mes($semanainicio,$semanafin){
    
  require('../bd/conexion.php');
     
  $sql  = "SELECT semana,dia,COUNT(*) total_ausencias FROM guardia WHERE semana>=".$semanainicio." AND semana<=".$semanafin." GROUP BY dia,semana  ORDER BY semana
  ";

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

  return $result;
    
   
}


public function obtener_guardias_hechas($dia,$hora){

  require('../bd/conexion.php');
     
  $sql  = "SELECT d.id idDocente,d.nombre nombreDocente,COUNT(g.idProfGuardia) guardiasHechas FROM `horario` h 
  JOIN docente d ON h.idProfesor=d.id 
  LEFT JOIN guardia g ON g.idProfGuardia = d.id
  WHERE h.dia=".$dia." AND h.hora=".$hora." AND materia LIKE 'GUARDIA' AND h.grupo LIKE 'Guardias'
  GROUP BY(d.id)";

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

  return $result;
}

public function obtener_guardias_hechas_por_profesor($dia,$hora){

  require('../bd/conexion.php');
     
  
 $sql= 'SELECT fechaGuardia,g.idProfGuardia,d.nombre, h.grupo, h.aula 
 FROM guardia g 
 INNER JOIN docente d ON g.idProfGuardia=d.id
 INNER JOIN horario h ON g.horario=h.id
 WHERE g.dia='.$dia.' AND g.hora='.$hora.' ORDER BY fechaGuardia DESC,g.idProfGuardia ASC;';

  $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
    
  mysqli_close($connection);

  return $result;
}





    
}
    
?>
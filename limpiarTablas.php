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
<div class="container">
TABLAS LIMPIADAS
<a href="formularioCargarDatos.php" class="btn btn-warning" role="button">Volver</a>
</div>
<?php


 require('./bd/conexion.php');

         $sql_VACIAR_TABLA_GUARDIA='DELETE FROM `guardia`';
         $sql_VACIAR_TABLA_HORARIO='DELETE FROM `horario`';
         $sql_VACIAR_TABLA_DOCENTE='DELETE FROM `docente`';
         $sql_RESETEAR_AUTOINCREMENT_DOCENTE='ALTER TABLE docente AUTO_INCREMENT = 1';
        
         mysqli_query($connection,$sql_VACIAR_TABLA_GUARDIA) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_VACIAR_TABLA_GUARDIA);
         mysqli_query($connection,$sql_VACIAR_TABLA_HORARIO) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_VACIAR_TABLA_HORARIO);
         mysqli_query($connection,$sql_VACIAR_TABLA_DOCENTE) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_VACIAR_TABLA_DOCENTE);
        mysqli_query($connection,$sql_RESETEAR_AUTOINCREMENT_DOCENTE) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql_RESETEAR_AUTOINCREMENT_DOCENTE);
        
   
          mysqli_close($connection);
    


?>
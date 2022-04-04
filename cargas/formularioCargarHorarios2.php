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

    
    
    

?>
<body>
    
<div class="container">
    <h2>Carga de horarios</h2>


<form class="form-horizontal" action="cargarAulas.php" method="post" enctype="multipart/form-data">
    
     <div class="form-group"> 
  
      
   
  </div>
  
  <div class="form-group"> 
      
    <div class="file-field">
        <div class="float-left">
            
            <input type="file" name="fichero" size="200">
        
           
        </div>
    </div>  
   
      <button type="submit" class="btn btn-info">Leer fichero</button>
    </div>

</form>
<div class="text-right">
            <a href="cargarDatos.php" class="btn btn-warning" role="button">Volver</a>
  </div>
</div>
</body>

<?php
// Carga la configuración 
$config = parse_ini_file('configBD.ini');

// Conexión con los datos del 'config.ini' 
$connection = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);

// Check connection
if ($connection===false) {
    echo 'ERROR: '.mysqli_connect_error();
} 

?>
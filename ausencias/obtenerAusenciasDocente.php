<?php

require_once 'AusenciasDAO.php';
require_once '../comun/cabecera.php';

if(isset($_GET["docente"])){
    $docente = $_GET["docente"]; 
    $nombre = $_GET["nombre"]; 

    $ausenciaDAO = new AusenciasDAO();
    
    $listaAusencias = $ausenciaDAO ->obtener_ausencia_docente($docente);


    
    mostrarListadoAusenciasDocente($nombre,$listaAusencias);
    
   
}


function mostrarListadoAusenciasDocente($nombre,$ausencias){


    $html='
    <div class="w3-container contenedortabla">

    <div class="row">
    <div class="col-sm-6 docente">
    <div class="textoTabla">Docente'.$nombre.' Número total de ausencias:'.$ausencias->num_rows.'</div>
    </div>
    </div>
   
    <table class="w3-table">
        <tr>
            <th class="cabeceraTabla">FECHA</th>
            <th class="cabeceraTabla">HORA</th>
            <th class="cabeceraTabla">GRUPO</th>
            <th class="cabeceraTabla">AULA</th>

        </tr>';

    if ($ausencias->num_rows > 0) {
        // output data of each row
        while($ausencia = $ausencias->fetch_assoc()) {
           
            $fechaMysql = strtotime($ausencia["fechaGuardia"]);    
            $fecha = date('d-m-Y',$fechaMysql); 

            $html .= '<tr>
                <td class="textoDatosDocente">'. $fecha.'</td>
                <td class="textoDatosDocente">'. $ausencia["hora"].'</td>
                <td class="textoDatosDocente">'. $ausencia["grupo"].'</td>
                <td class="textoDatosDocente">'. $ausencia["aula"].'</td>
            </tr>';
           
         
        }
        } else {
            $html .= "ESTE DOCENTE NO TIENE AUSENCIAS.";
        }

         
   $html =$html.'   </table></div> ';
   
   echo $html;
}



?>
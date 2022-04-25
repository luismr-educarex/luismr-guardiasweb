<?php

class Ausencia {
    
    private $id;
    private $dia;
    private $hora;
    private $tarea;
    private $observaciones;
    
    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setDia($dia) { $this->dia = $dia; }
    function getDia() { return $this->dia; }
    function setHora($hora) { $this->hora = $hora; }
    function getHora() { return $this->hora; }
    function setTarea($tarea) { $this->tarea = $tarea; }
    function getTarea() { return $this->tarea; }
    function setObservaciones($observaciones) { $this->observaciones = $observaciones; }
    function getObservaciones() { return $this->observaciones; }
    


    
    
}

?>
<?php

class Horario {
    public $docente;
    public $tipo_horario;
    public $horas;
    
    //Métodos
    public function getDocente(){
        return $this->docente;
    }
     
    public function setDocente($docente){
        $this->docente = $docente;
    }
    
    public function getTipo_horario(){
        return $this->tipo_horario;
    }
     
    public function setTipo_horario($tipo_horario){
        $this->tipo_horario = $tipo_horario;
    }
    
     public function getHoras(){
        return $this->horas;
    }
     
    public function setHoras($horas){
        $this->horas = $horas;
    }

}

?>
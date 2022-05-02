<?php

 /**
  * Docente Value Object.
  */

class DocenteDTO {

    var $id;
    var $nombre;
    var $codigo;
    var $departamento;
    var $rol;
    var $tipo_horario;

    function __construct() {

    }

    function getId() {
          return $this->id;
    }
    function setId($idIn) {
          $this->id = $idIn;
    }

    function getNombre() {
          return $this->nombre;
    }
    function setNombre($nombreIn) {
          $this->nombre = $nombreIn;
    }

    function getCodigo() {
          return $this->codigo;
    }
    function setCodigo($codigoIn) {
          $this->codigo = $codigoIn;
    }

    function getDepartamento() {
          return $this->departamento;
    }
    function setDepartamento($departamentoIn) {
          $this->departamento = $departamentoIn;
    }

    function getRol() {
          return $this->rol;
    }
    function setRol($rolIn) {
          $this->rol = $rolIn;
    }

    function getTipoHorario() {
          return $this->tipo_horario;
    }
    function setTipoHorario($tipoHorario) {
          $this->tipo_horario = $tipoHorario;
    }


    function setAll($idIn,
          $nombreIn,
          $codigoIn,
          $departamentoIn,
          $rolIn,
          $tipo_horario) {
          $this->id = $idIn;
          $this->nombre = $nombreIn;
          $this->codigo = $codigoIn;
          $this->departamento = $departamentoIn;
          $this->rol = $rolIn;
          $this->tipo_horario = $tipo_horario;
    }


    function hasEqualMapping($valueObject) {

          if ($valueObject->getId() != $this->id) {
                    return(false);
          }
          if ($valueObject->getNombre() != $this->nombre) {
                    return(false);
          }
          if ($valueObject->getCodigo() != $this->codigo) {
                    return(false);
          }
          if ($valueObject->getDepartamento() != $this->departamento) {
                    return(false);
          }
          if ($valueObject->getRol() != $this->rol) {
                    return(false);
          }
          if ($valueObject->getTipoHorario() != $this->tipo_horario) {
                    return(false);
          }

          return true;
    }


    function toString() {
      
        $out = $out."\nclass Docente, mapping to table Docente\n";
        $out = $out."Persistent attributes: \n"; 
        $out = $out."id = ".$this->id."\n"; 
        $out = $out."nombre = ".$this->nombre."\n"; 
        $out = $out."codigo = ".$this->codigo."\n"; 
        $out = $out."departamento = ".$this->departamento."\n"; 
        $out = $out."rol = ".$this->rol."\n"; 
        $out = $out."tipo horario = ".$this->tipo_horario."\n"; 
        return $out;
    }

    function clone() {
        $cloned = new DocenteDTO();

        $cloned->setId($this->id); 
        $cloned->setNombre($this->nombre); 
        $cloned->setCodigo($this->codigo); 
        $cloned->setDepartamento($this->departamento); 
        $cloned->setRol($this->rol); 
        $cloned->setTipoHorario($this->tipo_horario); 

        return $cloned;
    }

    /** 
     * getDaogenVersion will return information about
     * generator which created these sources.
     */
    

}

?>

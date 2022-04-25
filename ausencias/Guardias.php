<?php


 /**
  * Guardia Value Object.
  * This class is value object representing database table guardia
  * This class is intented to be used together with associated Dao object.
  */

class Guardia {

    /** 
     * Persistent Instance variables. This data is directly 
     * mapped to the columns of database table.
     */
    var $id;
    var $idProfesor;
    var $semana;
    var $dia;
    var $hora;
    var $grupo;
    var $aula;
    var $idProfGuardia;
    var $tarea;
    var $observaciones;
    var $cancelada;
    var $creadoPor;
    var $fechaCreacion;
    var $fechaGuardia;
    var $idHora;
    var $nombreDocente;



    /** 
     * Constructors. DaoGen generates two constructors by default.
     * The first one takes no arguments and provides the most simple
     * way to create object instance. The another one takes one
     * argument, which is the primary key of the corresponding table.
     */

    function Guardia () {

    }

    /* function Guardia ($idIn) {

          $this->id = $idIn;

    } */


    /** 
     * Get- and Set-methods for persistent variables. The default
     * behaviour does not make any checks against malformed data,
     * so these might require some manual additions.
     */

    function getId() {
          return $this->id;
    }
    function setId($idIn) {
          $this->id = $idIn;
    }

    function getIdProfesor() {
          return $this->idProfesor;
    }
    function setIdProfesor($idProfesorIn) {
          $this->idProfesor = $idProfesorIn;
    }

    function getSemana() {
          return $this->semana;
    }
    function setSemana($semanaIn) {
          $this->semana = $semanaIn;
    }

    function getDia() {
          return $this->dia;
    }
    function setDia($diaIn) {
          $this->dia = $diaIn;
    }

    function getHora() {
          return $this->hora;
    }
    function setHora($horaIn) {
          $this->hora = $horaIn;
    }

    function getGrupo() {
          return $this->grupo;
    }
    function setGrupo($grupoIn) {
          $this->grupo = $grupoIn;
    }

    function getAula() {
          return $this->aula;
    }
    function setAula($aulaIn) {
          $this->aula = $aulaIn;
    }

    function getIdProfGuardia() {
          return $this->idProfGuardia;
    }
    function setIdProfGuardia($idProfGuardiaIn) {
          $this->idProfGuardia = $idProfGuardiaIn;
    }

    function getTarea() {
          return $this->tarea;
    }
    function setTarea($tareaIn) {
          $this->tarea = $tareaIn;
    }

    function getObservaciones() {
          return $this->observaciones;
    }
    function setObservaciones($observacionesIn) {
          $this->observaciones = $observacionesIn;
    }

    function getCancelada() {
          return $this->cancelada;
    }
    function setCancelada($canceladaIn) {
          $this->cancelada = $canceladaIn;
    }

    function getCreadoPor() {
          return $this->creadoPor;
    }
    function setCreadoPor($creadoPorIn) {
          $this->creadoPor = $creadoPorIn;
    }

    function getFechaCreacion() {
          return $this->fechaCreacion;
    }
    function setFechaCreacion($fechaCreacionIn) {
          $this->fechaCreacion = $fechaCreacionIn;
    }

    function getFechaGuardia() {
          return $this->fechaGuardia;
    }
    function setFechaGuardia($fechaGuardiaIn) {
          $this->fechaGuardia = $fechaGuardiaIn;
    }
    function getIdHora() {
          return $this->idHora;
    }
    function setIdHora($idHora) {
          $this->idHora = $idHora;
    }
     function getNombreDocente() {
          return $this->nombreDocente;
    }
    function setNombreDocente($nombre) {
          $this->nombreDocente = $nombre;
    }



    /** 
     * setAll allows to set all persistent variables in one method call.
     * This is useful, when all data is available and it is needed to 
     * set the initial state of this object. Note that this method will
     * directly modify instance variales, without going trough the 
     * individual set-methods.
     */

    function setAll($idIn,
          $idProfesorIn,
          $semanaIn,
          $diaIn,
          $horaIn,
          $grupoIn,
          $aulaIn,
          $idProfGuardiaIn,
          $tareaIn,
          $observacionesIn,
          $canceladaIn,
          $creadoPorIn,
          $fechaCreacionIn,
          $fechaGuardiaIn,
          $idHora,
          $nombreDocente) {
          $this->id = $idIn;
          $this->idProfesor = $idProfesorIn;
          $this->semana = $semanaIn;
          $this->dia = $diaIn;
          $this->hora = $horaIn;
          $this->grupo = $grupoIn;
          $this->aula = $aulaIn;
          $this->idProfGuardia = $idProfGuardiaIn;
          $this->tarea = $tareaIn;
          $this->observaciones = $observacionesIn;
          $this->cancelada = $canceladaIn;
          $this->creadoPor = $creadoPorIn;
          $this->fechaCreacion = $fechaCreacionIn;
          $this->fechaGuardia = $fechaGuardiaIn;
          $this->idHora = $idHora;
          $this->nombreDocente = $nombreDocente;
    }


    /** 
     * hasEqualMapping-method will compare two Guardia instances
     * and return true if they contain same values in all persistent instance 
     * variables. If hasEqualMapping returns true, it does not mean the objects
     * are the same instance. However it does mean that in that moment, they 
     * are mapped to the same row in database.
     */
    function hasEqualMapping($valueObject) {

          if ($valueObject->getId() != $this->id) {
                    return(false);
          }
          if ($valueObject->getIdProfesor() != $this->idProfesor) {
                    return(false);
          }
          if ($valueObject->getSemana() != $this->semana) {
                    return(false);
          }
          if ($valueObject->getDia() != $this->dia) {
                    return(false);
          }
          if ($valueObject->getHora() != $this->hora) {
                    return(false);
          }
          if ($valueObject->getGrupo() != $this->grupo) {
                    return(false);
          }
          if ($valueObject->getAula() != $this->aula) {
                    return(false);
          }
          if ($valueObject->getIdProfGuardia() != $this->idProfGuardia) {
                    return(false);
          }
          if ($valueObject->getTarea() != $this->tarea) {
                    return(false);
          }
          if ($valueObject->getObservaciones() != $this->observaciones) {
                    return(false);
          }
          if ($valueObject->getCancelada() != $this->cancelada) {
                    return(false);
          }
          if ($valueObject->getCreadoPor() != $this->creadoPor) {
                    return(false);
          }
          if ($valueObject->getFechaCreacion() != $this->fechaCreacion) {
                    return(false);
          }
          if ($valueObject->getFechaGuardia() != $this->fechaGuardia) {
                    return(false);
          }

          return true;
    }



    /**
     * toString will return String object representing the state of this 
     * valueObject. This is useful during application development, and 
     * possibly when application is writing object states in textlog.
     */
    function toString() {
        $out = $this->getDaogenVersion();
        $out = $out."\nclass Guardia, mapping to table guardia\n";
        $out = $out."Persistent attributes: \n"; 
        $out = $out."id = ".$this->id."\n"; 
        $out = $out."idProfesor = ".$this->idProfesor."\n"; 
        $out = $out."semana = ".$this->semana."\n"; 
        $out = $out."dia = ".$this->dia."\n"; 
        $out = $out."hora = ".$this->hora."\n"; 
        $out = $out."grupo = ".$this->grupo."\n"; 
        $out = $out."aula = ".$this->aula."\n"; 
        $out = $out."idProfGuardia = ".$this->idProfGuardia."\n"; 
        $out = $out."tarea = ".$this->tarea."\n"; 
        $out = $out."observaciones = ".$this->observaciones."\n"; 
        $out = $out."cancelada = ".$this->cancelada."\n"; 
        $out = $out."creadoPor = ".$this->creadoPor."\n"; 
        $out = $out."fechaCreacion = ".$this->fechaCreacion."\n"; 
        $out = $out."fechaGuardia = ".$this->fechaGuardia."\n"; 
        return $out;
    }


    /**
     * Clone will return identical deep copy of this valueObject.
     * Note, that this method is different than the clone() which
     * is defined in java.lang.Object. Here, the retuned cloned object
     * will also have all its attributes cloned.
     */
    function clone() {
        $cloned = new Guardia();

        $cloned->setId($this->id); 
        $cloned->setIdProfesor($this->idProfesor); 
        $cloned->setSemana($this->semana); 
        $cloned->setDia($this->dia); 
        $cloned->setHora($this->hora); 
        $cloned->setGrupo($this->grupo); 
        $cloned->setAula($this->aula); 
        $cloned->setIdProfGuardia($this->idProfGuardia); 
        $cloned->setTarea($this->tarea); 
        $cloned->setObservaciones($this->observaciones); 
        $cloned->setCancelada($this->cancelada); 
        $cloned->setCreadoPor($this->creadoPor); 
        $cloned->setFechaCreacion($this->fechaCreacion); 
        $cloned->setFechaGuardia($this->fechaGuardia); 

        return $cloned;
    }



    /** 
     * getDaogenVersion will return information about
     * generator which created these sources.
     */
    function getDaogenVersion() {
        return "DaoGen version 2.4.1";
    }

}

?>
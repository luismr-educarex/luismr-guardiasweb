<?php


 /**
  * HorasSemanasDocente Value Object.
  * This class is value object representing database table horasSemanasDocente
  * This class is intented to be used together with associated Dao object.
  */


class HorasSemanasDocente {

    /** 
     * Persistent Instance variables. This data is directly 
     * mapped to the columns of database table.
     */
    var $id;
    var $dia;
    var $hora;
    var $contenido;
    var $grupo;
    var $aula;
    var $semana;
    var $ausencia;
    var $tarea;
    var $observaciones;



    /** 
     * Constructors. DaoGen generates two constructors by default.
     * The first one takes no arguments and provides the most simple
     * way to create object instance. The another one takes one
     * argument, which is the primary key of the corresponding table.
     */

    function __construct()
    {
          
   

    }

    /* function HorasSemanasDocente ($idIn) {

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

    function getContenido() {
          return $this->contenido;
    }
    function setContenido($contenidoIn) {
          $this->contenido = $contenidoIn;
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

    function getSemana() {
          return $this->semana;
    }
    function setSemana($semanaIn) {
          $this->semana = $semanaIn;
    }

    function getAusencia() {
          return $this->ausencia;
    }
    function setAusencia($ausenciaIn) {
          $this->ausencia = $ausenciaIn;
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



    /** 
     * setAll allows to set all persistent variables in one method call.
     * This is useful, when all data is available and it is needed to 
     * set the initial state of this object. Note that this method will
     * directly modify instance variales, without going trough the 
     * individual set-methods.
     */

    function setAll($idIn,
          $diaIn,
          $horaIn,
          $contenidoIn,
          $grupoIn,
          $aulaIn,
          $semanaIn,
          $ausenciaIn,
          $tareaIn,
          $observacionesIn) {
          $this->id = $idIn;
          $this->dia = $diaIn;
          $this->hora = $horaIn;
          $this->contenido = $contenidoIn;
          $this->grupo = $grupoIn;
          $this->aula = $aulaIn;
          $this->semana = $semanaIn;
          $this->ausencia = $ausenciaIn;
          $this->tarea = $tareaIn;
          $this->observaciones = $observacionesIn;
    }


    /** 
     * hasEqualMapping-method will compare two HorasSemanasDocente instances
     * and return true if they contain same values in all persistent instance 
     * variables. If hasEqualMapping returns true, it does not mean the objects
     * are the same instance. However it does mean that in that moment, they 
     * are mapped to the same row in database.
     */
    function hasEqualMapping($valueObject) {

          if ($valueObject->getId() != $this->id) {
                    return(false);
          }
          if ($valueObject->getDia() != $this->dia) {
                    return(false);
          }
          if ($valueObject->getHora() != $this->hora) {
                    return(false);
          }
          if ($valueObject->getContenido() != $this->contenido) {
                    return(false);
          }
          if ($valueObject->getGrupo() != $this->grupo) {
                    return(false);
          }
          if ($valueObject->getAula() != $this->aula) {
                    return(false);
          }
          if ($valueObject->getSemana() != $this->semana) {
                    return(false);
          }
          if ($valueObject->getAusencia() != $this->ausencia) {
                    return(false);
          }
          if ($valueObject->getTarea() != $this->tarea) {
                    return(false);
          }
          if ($valueObject->getObservaciones() != $this->observaciones) {
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
        $out = $out."\nclass HorasSemanasDocente, mapping to table horasSemanasDocente\n";
        $out = $out."Persistent attributes: \n"; 
        $out = $out."id = ".$this->id."\n"; 
        $out = $out."dia = ".$this->dia."\n"; 
        $out = $out."hora = ".$this->hora."\n"; 
        $out = $out."contenido = ".$this->contenido."\n"; 
        $out = $out."grupo = ".$this->grupo."\n"; 
        $out = $out."aula = ".$this->aula."\n"; 
        $out = $out."semana = ".$this->semana."\n"; 
        $out = $out."ausencia = ".$this->ausencia."\n"; 
        $out = $out."tarea = ".$this->tarea."\n"; 
        $out = $out."observaciones = ".$this->observaciones."\n"; 
        return $out;
    }


    /**
     * Clone will return identical deep copy of this valueObject.
     * Note, that this method is different than the clone() which
     * is defined in java.lang.Object. Here, the retuned cloned object
     * will also have all its attributes cloned.
     */
    function clone() {
        $cloned = new HorasSemanasDocente();

        $cloned->setId($this->id); 
        $cloned->setDia($this->dia); 
        $cloned->setHora($this->hora); 
        $cloned->setContenido($this->contenido); 
        $cloned->setGrupo($this->grupo); 
        $cloned->setAula($this->aula); 
        $cloned->setSemana($this->semana); 
        $cloned->setAusencia($this->ausencia); 
        $cloned->setTarea($this->tarea); 
        $cloned->setObservaciones($this->observaciones); 

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
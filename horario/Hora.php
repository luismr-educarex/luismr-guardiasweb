<?php
class Hora {

private $id;
private $materia;
private $aula;
private $grupo;
private $dia;
private $hora;


function __construct ($id,$materia,$aula,$grupo,$dia,$hora) {

    $this->id = $id;
    $this->materia = $materia;
    $this->aula = $aula;
    $this->grupo = $grupo;
    $this->dia = $dia;
    $this->hora = $hora;
  
    
}


/**
 * Get the value of id
 */ 
public function getId()
{
return $this->id;
}

/**
 * Set the value of id
 *
 * @return  self
 */ 
public function setId($id)
{
$this->id = $id;

return $this;
}

/**
 * Get the value of materia
 */ 
public function getMateria()
{
return $this->materia;
}

/**
 * Set the value of materia
 *
 * @return  self
 */ 
public function setMateria($materia)
{
$this->materia = $materia;

return $this;
}

/**
 * Get the value of aula
 */ 
public function getAula()
{
return $this->aula;
}

/**
 * Set the value of aula
 *
 * @return  self
 */ 
public function setAula($aula)
{
$this->aula = $aula;

return $this;
}

/**
 * Get the value of grupo
 */ 
public function getGrupo()
{
return $this->grupo;
}

/**
 * Set the value of grupo
 *
 * @return  self
 */ 
public function setGrupo($grupo)
{
$this->grupo = $grupo;

return $this;
}

/**
 * Get the value of dia
 */ 
public function getDia()
{
return $this->dia;
}

/**
 * Set the value of dia
 *
 * @return  self
 */ 
public function setDia($dia)
{
$this->dia = $dia;

return $this;
}

/**
 * Get the value of hora
 */ 
public function getHora()
{
return $this->hora;
}

/**
 * Set the value of hora
 *
 * @return  self
 */ 
public function setHora($hora)
{
$this->hora = $hora;

return $this;
}
}

?>
CREATE TABLE `docente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `codigoProfesor` varchar(50) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `tipo_horario` int(11) NOT NULL,
  `rol` int(11) NOT NULL DEFAULT 1,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `guardia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `horario` int(11) NOT NULL,
  `idProfesor` int(11) NOT NULL,
  `semana` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `hora` int(11) NOT NULL,
  `grupo` varchar(50) DEFAULT NULL,
  `aula` varchar(50) DEFAULT NULL,
  `idProfGuardia` int(11) DEFAULT NULL,
  `tarea` tinyint(1) NOT NULL DEFAULT 0,
  `observaciones` varchar(255) DEFAULT NULL,
  `cancelada` tinyint(1) NOT NULL DEFAULT 0,
  `creadoPor` varchar(50) DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaGuardia` date NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `horario` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProfesor` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `hora` int(11) NOT NULL,
  `materia` varchar(50) NOT NULL,
  `grupo` varchar(50) DEFAULT NULL,
  `aula` varchar(50) DEFAULT NULL,
  `guardia` tinyint(1) DEFAULT 0,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
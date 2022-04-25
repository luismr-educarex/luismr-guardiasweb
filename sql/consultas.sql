SELECT d.nombre FROM `horario` h 
JOIN docente d ON h.idProfesor=d.id 
LEFT JOIN guardia g ON g.idProfGuardia=d.id
WHERE h.dia=5 AND h.hora=4 AND materia LIKE 'GUARDIA' AND h.grupo LIKE 'Guardia'
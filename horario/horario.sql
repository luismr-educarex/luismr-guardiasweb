# SQL command to create the table: 
# Remember to correct VARCHAR column lengths to proper values 
# and add additional indexes for your own extensions.

# If you had prepaired CREATE TABLE SQL-statement before, 
# make sure that this automatically generated code is 
# compatible with your own code. If SQL code is incompatible,
# it is not possible to use these generated sources successfully.
# (Changing VARCHAR column lenghts will not break code.)

CREATE TABLE Horario (
      idProfesor bigint NOT NULL,
      dia bigint NOT NULL,
      hora bigint NOT NULL,
      grupo varchar(255),
      aula varchar(255),
      guardia bigint,
PRIMARY KEY(idProfesor, dia, hora),
INDEX Horario_idProfesor_INDEX (idProfesor),
INDEX Horario_dia_INDEX (dia),
INDEX Horario_hora_INDEX (hora));
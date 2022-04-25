# SQL command to create the table: 
# Remember to correct VARCHAR column lengths to proper values 
# and add additional indexes for your own extensions.

# If you had prepaired CREATE TABLE SQL-statement before, 
# make sure that this automatically generated code is 
# compatible with your own code. If SQL code is incompatible,
# it is not possible to use these generated sources successfully.
# (Changing VARCHAR column lenghts will not break code.)

CREATE TABLE Docente (
      id bigint NOT NULL,
      nombre varchar(255),
      codigo varchar(255),
      departamento varchar(255),
      rol bigint,
      turno bigint,
PRIMARY KEY(id),
INDEX Docente_id_INDEX (id));

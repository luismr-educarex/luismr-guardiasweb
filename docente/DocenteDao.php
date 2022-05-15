<?php



class DocenteDao {


    /**
     * createValueObject-method. This method is used when the Dao class needs
     * to create new value object instance. The reason why this method exists
     * is that sometimes the programmer may want to extend also the valueObject
     * and then this method can be overrided to return extended valueObject.
     * NOTE: If you extend the valueObject class, make sure to override the
     * clone() method in it!
     */
    function createValueObject() {
         // return new Docente();
    }


    /**
     * getObject-method. This will create and load valueObject contents from database 
     * using given Primary-Key as identifier. This method is just a convenience method 
     * for the real load-method which accepts the valueObject as a parameter. Returned
     * valueObject will be created using the createValueObject() method.
     */
    function getObject(&$conn, $id) {

          $valueObject = $this->createValueObject();
          //$valueObject->setId($id);
          $this->load($conn, $valueObject);
          return $valueObject;
    }


    /**
     * load-method. This will load valueObject contents from database using
     * Primary-Key as identifier. Upper layer should use this so that valueObject
     * instance is created and only primary-key should be specified. Then call
     * this method to complete other persistent information. This method will
     * overwrite all other fields except primary-key and possible runtime variables.
     * If load can not find matching row, NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be loaded.
     *                     Primary-key field must be set for this to work properly.
     */
    function load(&$conn, &$valueObject) {

          if (!$valueObject->getId()) {
               //print "Can not select without Primary-Key!";
               return false;
          }

          $sql = "SELECT * FROM Docente WHERE (id = ".$valueObject->getId().") "; 

          if ($this->singleQuery($conn, $sql, $valueObject))
               return true;
          else
               return false;
    }


    /**
     * LoadAll-method. This will read all contents from database table and
     * build an Vector containing valueObjects. Please note, that this method
     * will consume huge amounts of resources if table has lot's of rows. 
     * This should only be used when target tables have only small amounts
     * of data.
     *
     * @param conn         This method requires working database connection.
     */
    function loadAll(&$conn) {


          $sql = "SELECT * FROM Docente ORDER BY id ASC ";

          $searchResults = $this->listQuery($conn, $sql);

          return $searchResults;
    }

    
    
    public function cargar_docentes() {

     require('../bd/conexion.php');

      $sql = "SELECT * FROM docente";

      $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

      mysqli_close($connection);

      return $result;

    }
    
    
public function cargar_docentes_guardias($dia,$hora) {

     require('../bd/conexion.php');

      $sql = "SELECT * 
      FROM horario h INNER JOIN docente d ON h.idProfesor=d.id
       WHERE dia=".$dia." AND hora=".$hora." AND materia='GUARDIA' AND grupo='Guardias' AND tipo_horario=1";

      $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

      mysqli_close($connection);

      return $result;

    }

    public function cargar_docente_por_id(String $id) {

      require('../bd/conexion.php');
 
       $sql = "SELECT * FROM docente WHERE id=".$id;
 
       $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);

         if ($result->num_rows > 0) {
            
            $docente = $result->fetch_assoc();
             
          }
 
       mysqli_close($connection);
 
       return $docente;
 
     }

     function guardar($valueObject) {

      require('../bd/conexion.php');

      $sql = "UPDATE docente SET nombre = '".$valueObject->getNombre()."' ";

      //$sql = $sql."codigoProfesor = '".$valueObject->getCodigo()."', ";
     
      //$sql = $sql."departamento = '".$valueObject->getDepartamento()."', ";
      
      //$sql = $sql."rol = ".$valueObject->getRol()." ";
      
      $sql = $sql." WHERE id = ".$valueObject->getId()." ";
      
      $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql." en DocenteDao");
      //$result = $this->databaseUpdate($connection, $sql);


      if ($result != 1) {
           //print "PrimaryKey Error when updating DB!";

           return false;
      }

      return true;
}
     
   


    /**
     * create-method. This will create new row in database according to supplied
     * valueObject contents. Make sure that values for all NOT NULL columns are
     * correctly specified. Also, if this table does not use automatic surrogate-keys
     * the primary-key must be specified. After INSERT command this method will 
     * read the generated primary-key back to valueObject if automatic surrogate-keys
     * were used. 
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be created.
     *                     If automatic surrogate-keys are not used the Primary-key 
     *                     field must be set for this to work properly.
     */
    function create(&$conn, &$valueObject) {

          $sql = "INSERT INTO Docente ( id, nombre, codigo, ";
          $sql = $sql."departamento, rol, turno) VALUES (".$valueObject->getId().", ";
          $sql = $sql."'".$valueObject->getNombre()."', ";
          $sql = $sql."'".$valueObject->getCodigo()."', ";
          $sql = $sql."'".$valueObject->getDepartamento()."', ";
          $sql = $sql."".$valueObject->getRol().", ";
          $sql = $sql."".$valueObject->getTurno().") ";
          $result = $this->databaseUpdate($conn, $sql);
        

          return true;
    }
    
    
    function add(&$valueObject){
        
        require('./bd/conexion.php');
        
        $sql = "INSERT INTO `docente`(`nombre`, `codigoProfesor`, `departamento`, `rol`) VALUES ('".$valueObject->getNombre()."','".$valueObject->getCodigo()."','".$valueObject->getDepartamento()."','".$valueObject->getRol()."')";

        $result = mysqli_query($connection,$sql) or die ("MENSAJE:No se ha ejecutado la senctencia sql:".$sql);
        $last_id=mysqli_insert_id($connection);
        

        mysqli_close($connection);
        
        return $last_id;
    }


    /**
     * save-method. This method will save the current state of valueObject to database.
     * Save can not be used to create new instances in database, so upper layer must
     * make sure that the primary-key is correctly specified. Primary-key will indicate
     * which instance is going to be updated in database. If save can not find matching 
     * row, NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be saved.
     *                     Primary-key field must be set for this to work properly.
     */
    function save(&$conn, &$valueObject) {

          $sql = "UPDATE Docente SET nombre = '".$valueObject->getNombre()."', ";
          $sql = $sql."codigo = '".$valueObject->getCodigo()."', ";
          $sql = $sql."departamento = '".$valueObject->getDepartamento()."', ";
          $sql = $sql."rol = ".$valueObject->getRol().", ";
          $sql = $sql."turno = ".$valueObject->getTurno()."";
          $sql = $sql." WHERE (id = ".$valueObject->getId().") ";
          $result = $this->databaseUpdate($conn, $sql);

          if ($result != 1) {
               //print "PrimaryKey Error when updating DB!";
               return false;
          }

          return true;
    }


    /**
     * delete-method. This method will remove the information from database as identified by
     * by primary-key in supplied valueObject. Once valueObject has been deleted it can not 
     * be restored by calling save. Restoring can only be done using create method but if 
     * database is using automatic surrogate-keys, the resulting object will have different 
     * primary-key than what it was in the deleted object. If delete can not find matching row,
     * NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance to be deleted.
     *                     Primary-key field must be set for this to work properly.
     */
    function delete(&$conn, &$valueObject) {


          if (!$valueObject->getId()) {
               //print "Can not delete without Primary-Key!";
               return false;
          }

          $sql = "DELETE FROM Docente WHERE (id = ".$valueObject->getId().") ";
          $result = $this->databaseUpdate($conn, $sql);

          if ($result != 1) {
               //print "PrimaryKey Error when updating DB!";
               return false;
          }
          return true;
    }


    /**
     * deleteAll-method. This method will remove all information from the table that matches
     * this Dao and ValueObject couple. This should be the most efficient way to clear table.
     * Once deleteAll has been called, no valueObject that has been created before can be 
     * restored by calling save. Restoring can only be done using create method but if database 
     * is using automatic surrogate-keys, the resulting object will have different primary-key 
     * than what it was in the deleted object. (Note, the implementation of this method should
     * be different with different DB backends.)
     *
     * @param conn         This method requires working database connection.
     */
    function deleteAll(&$conn) {

          $sql = "DELETE FROM Docente";
          $result = $this->databaseUpdate($conn, $sql);

          return true;
    }


    /**
     * coutAll-method. This method will return the number of all rows from table that matches
     * this Dao. The implementation will simply execute "select count(primarykey) from table".
     * If table is empty, the return value is 0. This method should be used before calling
     * loadAll, to make sure table has not too many rows.
     *
     * @param conn         This method requires working database connection.
     */
    function countAll(&$conn) {

          $sql = "SELECT count(*) FROM Docente";
          $allRows = 0;

          $result = $conn->execute($sql);

          if ($row = $conn->nextRow($result))
                $allRows = $row[0];

          return $allRows;
    }


    /** 
     * searchMatching-Method. This method provides searching capability to 
     * get matching valueObjects from database. It works by searching all 
     * objects that match permanent instance variables of given object.
     * Upper layer should use this by setting some parameters in valueObject
     * and then  call searchMatching. The result will be 0-N objects in vector, 
     * all matching those criteria you specified. Those instance-variables that
     * have NULL values are excluded in search-criteria.
     *
     * @param conn         This method requires working database connection.
     * @param valueObject  This parameter contains the class instance where search will be based.
     *                     Primary-key field should not be set.
     */
    function searchMatching(&$conn, &$valueObject) {

          $first = true;
          $sql = "SELECT * FROM Docente WHERE 1=1 ";

          if ($valueObject->getId() != 0) {
              if ($first) { $first = false; }
              $sql = $sql."AND id = ".$valueObject->getId()." ";
          }

          if ($valueObject->getNombre() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND nombre LIKE '".$valueObject->getNombre()."%' ";
          }

          if ($valueObject->getCodigo() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND codigo LIKE '".$valueObject->getCodigo()."%' ";
          }

          if ($valueObject->getDepartamento() != "") {
              if ($first) { $first = false; }
              $sql = $sql."AND departamento LIKE '".$valueObject->getDepartamento()."%' ";
          }

          if ($valueObject->getRol() != 0) {
              if ($first) { $first = false; }
              $sql = $sql."AND rol = ".$valueObject->getRol()." ";
          }

          if ($valueObject->getTurno() != 0) {
              if ($first) { $first = false; }
              $sql = $sql."AND turno = ".$valueObject->getTurno()." ";
          }


          $sql = $sql."ORDER BY id ASC ";

          // Prevent accidential full table results.
          // Use loadAll if all rows must be returned.
          if ($first)
               return array();

          $searchResults = $this->listQuery($conn, $sql);

          return $searchResults;
    }



    /**
     * databaseUpdate-method. This method is a helper method for internal use. It will execute
     * all database handling that will change the information in tables. SELECT queries will
     * not be executed here however. The return value indicates how many rows were affected.
     * This method will also make sure that if cache is used, it will reset when data changes.
     *
     * @param conn         This method requires working database connection.
     * @param stmt         This parameter contains the SQL statement to be excuted.
     */
    function databaseUpdate($conn, $sql) {

          $result = mysqli_query($conn,$sql);

          return $result;
    }



    /**
     * databaseQuery-method. This method is a helper method for internal use. It will execute
     * all database queries that will return only one row. The resultset will be converted
     * to valueObject. If no rows were found, NotFoundException will be thrown.
     *
     * @param conn         This method requires working database connection.
     * @param stmt         This parameter contains the SQL statement to be excuted.
     * @param valueObject  Class-instance where resulting data will be stored.
     */
    function singleQuery(&$conn, &$sql, &$valueObject) {

          $result = $conn->execute($sql);

          if ($row = $conn->nextRow($result)) {

                   $valueObject->setId($row[0]); 
                   $valueObject->setNombre($row[1]); 
                   $valueObject->setCodigo($row[2]); 
                   $valueObject->setDepartamento($row[3]); 
                   $valueObject->setRol($row[4]); 
                   $valueObject->setTurno($row[5]); 
          } else {
               //print " Object Not Found!";
               return false;
          }
          return true;
    }


    /**
     * databaseQuery-method. This method is a helper method for internal use. It will execute
     * all database queries that will return multiple rows. The resultset will be converted
     * to the List of valueObjects. If no rows were found, an empty List will be returned.
     *
     * @param conn         This method requires working database connection.
     * @param stmt         This parameter contains the SQL statement to be excuted.
     */
    function listQuery(&$conn, &$sql) {

          $searchResults = array();
          $result = $conn->execute($sql);

          /*
          while ($row = $conn->nextRow($result)) {
               $temp = $this->createValueObject();

               $temp->setId($row[0]); 
               $temp->setNombre($row[1]); 
               $temp->setCodigo($row[2]); 
               $temp->setDepartamento($row[3]); 
               $temp->setRol($row[4]); 
               $temp->setTurno($row[5]); 
               array_push($searchResults, $temp);
          }

          */
          return $searchResults;
    }
}

?>

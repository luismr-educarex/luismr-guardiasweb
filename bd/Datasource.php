<?php






class Datasource {

        var $dbLink;

       /**
        * Constructor. Call this once when initializing system core.
        * Then save the instance of this class in $connection variable 
        * and pass it as an argument when using services from core.
        */
       function Datasource($dbHost, $dbName, $dbuser, $dbpasswd) {

                 $config = parse_ini_file('configBD.ini');
                // Change this line to reflect whatever Database system you are using:
                //$this->dbLink = mysql_connect ($dbHost, $dbuser, $dbpasswd);
                //$this->dbLink  = new mysqli($dbHost, $dbName, $dbuser, $dbpasswd);
                  
               $host= $config['host'];
               $usermane= $config['username'];
               $pass =  $config['password'];
               $dbname = $config['dbname'];
           
                $this->dbLink  = new mysqli($host,$usermane,$pass,$dbname);
                //$this->dbLink  = new mysqli('localhost','root','','cuadernoaula');

                // Change this line to reflect whatever Database system you are using:
                mysqli_select_db ( $this->dbLink,'guardias');
	}


        /**
         * Function to execute SQL-commands. Use this thin wrapper to avoid 
         * MySQL dependency in application code.
         */
        function execute($sql) {

                // Change this line to reflect whatever Database system you are using:
                //$result = mysql_query($sql, $this->dbLink);
                $result = $this->dbLink->query($sql);
                //$this->checkErrors($sql);

                return $result;
        }


        /**
         * Function to "blindly" execute SQL-commands. This will not put up 
         * any notifications if SQL fails, so make sure this is not used for 
         * normal operations.
         */
        function executeBlind($sql) {

                // Change this line to reflect whatever Database system you are using:
                $result = mysql_query($sql, $this->dbLink);

                return $result;
        }


        /**
         * Function to iterate trough the resultset. Use this thin wrapper to 
         * avoid MySQL dependency in application code.
         */
        function nextRow ($result) {

                // Change this line to reflect whatever Database system you are using:
                $row = mysqli_fetch_array($result);

                return $row;
        }


        /**
         * Check if sql-queries triggered errors. This will be called after an 
         * execute-command. Function requires attempted SQL string as parameter 
         * since it can be logged to application spesific log if errors occurred.
         * This whole method depends heavily from selected Database-system. Make
         * sure you change this method when using some other than MySQL database.
         */
        function checkErrors($sql) {

                //global $systemLog;

                // Only thing that we need todo is define some variables
                // And ask from RDBMS, if there was some sort of errors.
                $err=mysqli_error();
                $errno=mysqli_errno();

                if($errno) {
                        // SQL Error occurred. This is FATAL error. Error message and 
                        // SQL command will be logged and aplication will teminate immediately.
                        $message = "The following SQL command ".$sql." caused Database error: ".$err.".";

                        //$message = addslashes("SQL-command: ".$sql." error-message: ".$message);
                        //$systemLog->writeSystemSqlError ("SQL Error occurred", $errno, $message);

                        print "Unrecowerable error has occurred. All data will be logged.";
                        print "Please contact System Administrator for help! \n";
                        print "<!-- ".$message." -->\n";
                        exit;

                } else {
                        // Since there was no error, we can safely return to main program.
                        return;
                }
        }
}

?>

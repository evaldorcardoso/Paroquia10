<?php
	    //Define connection as a static variable, to avoid connecting more than once
	    static $connection;
	    // Try and connect to the database, if a connection has not been established yet
		if(!isset($connection)) 
	    {
            // Load configuration as an array. Use the actual location of your configuration file
            $connection = new mysqli('localhost', 'u195070854_db', 'unidosporcristo', 'u195070854_paroq');
	        //$connection = mysqli_connect('localhost','u831949765_paroq','unidosporcristo','u831949765_paroq');
	        mysqli_query($connection,"SET time_zone = 'America/Sao_Paulo';");
        }
        if ($connection->connect_error) {
            die('Connect Error (' . $connection->connect_errno . ') '
                    . $connection->connect_error);
        }
           
        echo 'Success... ' . $mysqli->host_info . "\n";

?>
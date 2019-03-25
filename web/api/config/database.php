<?php

    include ("config.php");

    class Database{
        
        private $connection;

        function getConnection(){

            $this->connection = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

            if($this->connection->connect_error){
                exit('Error connecting to database');
                echo "ERROR CONNECTING TO DATABASE";
            }

            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->connection->set_charset('utf8mb4');

            return $this->connection;

        }
    }

?>
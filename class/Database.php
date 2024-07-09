<?php

class Database  {

    private  $conn;

    function Database() {
      $this-> conn =  $this -> dbConnect(); 
    }
  
    function dbConnect()    {
      
        $serverName = ".\sql2022";
        $connectionOptions = array("Database"=>"Kojaa",
            "Uid"=>"kojaa", "PWD"=>"kojaa");
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if($conn == false)
        {
            echo "Could not connect.\n";  
            die(sqlsrv_errors());
        }
        else
        {
           echo 'connection ok\n';
        }

        return $conn;
    }

    function GetTable($sqlQuery)  {
        
        return sqlsrv_query($this->conn, $sqlQuery);
    }

    function ExecuteQuery($sqlQuery) {
        return sqlsrv_query($this-> conn , $sqlQuery);
    }

}

?>
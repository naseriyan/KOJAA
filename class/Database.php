<?php

class Database  {

    private  $conn;

    function Database() {
      $this->conn =  $this->dbConnect(); 
    }
  
    private function dbConnect()    {
      
        $serverName = ".\sql2022";
        $connectionOptions = array("Database"=>"Kojaa",
            "Uid"=>"kojaa", "PWD"=>"kojaa"
            ,"CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if($conn == false)
        {
            echo "Could not connect.\n";  
            die(sqlsrv_errors());
        }
        else
        {
            return $conn;
        }
    }

    function GetTable($sqlQuery,$params)  {
        if($this->conn==null)
            $this->conn=$this->dbConnect();
        return sqlsrv_query($this->conn, $sqlQuery,$params,array( "Scrollable" => 'static' ));
    }

    function ExecuteQuery($sqlQuery,$params) {
        if($this->conn==null)
            $this->conn=$this->dbConnect();
        sqlsrv_query($this->conn, "SET NAMES 'utf8'");

        return sqlsrv_query($this->conn , $sqlQuery,$params);
    }

}

?>
<?php

define("GETINFO",getenv("CLEARDB_DATABASE_URL"));
class Database
{
  
    private $host =  "localhost";
    private $db_name = "OfficeHours";
    private $username = "root";
    private $password = "";
    private $conn;
    
    // $cleardb_server = $cleardb_url["host"];
    // $cleardb_username = $cleardb_url["user"];
    // $cleardb_password = $cleardb_url["pass"];
    // $cleardb_db = substr($cleardb_url["path"],1);
    // $active_group = 'default';
    // $query_builder = TRUE;
    

    public function connect()
    {
        // $cleardb_url =  parse_url(GETINFO);
        // $this->host =  $cleardb_url["host"];
        // $this->db_name = substr($cleardb_url["path"],1);
        // $this->username = $cleardb_url["user"];
        // $this->password = $cleardb_url["pass"];
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
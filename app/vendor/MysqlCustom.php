<?php
class MysqlCustom
{
    public $queryLog = true;
    public $conn, $db, $logs;
    private $database = "";
    
    public function __construct($config)
    {
        $this->db = $config['database'];
        $this->conn = mysqli_connect($config['server'], $config['username'], $config['password'], $config['database'], $config['port']);
        
        $this->database = $config['database'];
        
        if ($this->conn === false)
        {
            throw new Exception(mysqli_connect_error());
        }
    }
    
    public function close()
    {
        mysqli_close($this->conn);
    }
    
    public function getDBName()
    {
        return $this->database;
    }
    
    public function query($q)
    {
        $this->logs[] = $q;
        
        return mysqli_query($this->conn, $q);
    }
    
    public function select($q)
    {
        $result = $this->query($q);
        
        $records = array();
        
        while($row = mysqli_fetch_assoc($result))
        {
            $records[] = $row;
        }
        
        return $records;
    }
    
    public function transactionBegin()
    {
        $this->query("SET AUTOCOMMIT=0");
        $this->query("START TRANSACTION");
    }
    
    public function transactionCommit()
    {
        $this->query("COMMIT");
    }
    
    public function transactionRollback()
    {
        $this->query("ROLLBACK");
    }
}

<?php

abstract class MysqlMain {
    private string $sql;
    private string $instruction;
    private array $fields;
    private string $table;
    private string $where;
    private $searchedText;
    private $sqlHeader;

    public function __construct($instruction, array $fields, $table, $where, $searchedText=null, $sqlHeader=null) {
        $this->instruction = $instruction;
        $this->fields = $fields;
        $this->table = $table;
        $this->where = $where;
        $this->searchedText = $searchedText;
        $this->sqlHeader = $sqlHeader;
    }
 
    public function setSql($sql)
    {
        $this->sql = $sql;
    }
 
    public function getSql()
    {
        return $this->sql;
    }
    
    public function getInst()
    {
        return $this->instruction;
    }

    public function getFields()
    {
        return $this->fields;
    }
    
    public function getTable()
    {
        return $this->table;
    }

    public function getWhere()
    {
        return $this->where;
    }
    
    public function getSText()
    {
        return $this->searchedText;
    }
    
    public function setSText($searchedText)
    {
        $this->searchedText = $searchedText;
    }
    
    public function getHeader()
    {
        return $this->sqlHeader;
    }

    public abstract function sqlString();

    /*public function sqlString() {
        $sql = $this->instruction . implode(", ", $this->fields) . ' FROM ' . $this->table . ' WHERE ' . $this->where;
        if(isset($this->searchedText)) {$sql .= ' LIKE "%'.$this->searchedText.'%"';}
        return $sql;
    }*/

    public function getData($rows = false){
        $data = [];
        require('./config/dbconfig.php');
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $result = mysqli_query($conn, $this->sql);
        if($result && $rows === true){
            if (mysqli_num_rows($result) > 0) {
                while(($row = mysqli_fetch_assoc($result)) !== Null) {
                    $data[] = $row;
                }
            }
            
        }
        mysqli_close($conn);
        return $data;
    }
}
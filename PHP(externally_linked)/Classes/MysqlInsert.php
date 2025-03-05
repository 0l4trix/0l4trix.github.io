<?php
require_once('./Classes/MysqlMain.php');

class MysqlInsert extends MysqlMain {
    private array $newData;
    private array $needTick;

    public function __construct(array $fields, $table, array $newData, $needTick) {
        parent::__construct('INSERT INTO ', $fields, $table, '', 'null', 'null');
        $this->newData = $newData;
        $this->needTick = $needTick;
    }
    /*INSERT INTO employees (name, position)
VALUES ('John Doe', 'Software Engineer');*/

    public function sqlString() {
        if(count($this->needTick) === count($this->newData)) {
        $sql = $this->getInst() . $this->getTable() . ' (' . implode(", ", $this->getFields()) . ') VALUES (';
        //. implode(", ", $this->newData) . ')';
        for ($i=0; $i < count($this->newData); $i++) { 
            if ($this->needTick[$i] === 'true') {
                $sql .= "'".$this->newData[$i]."' , ";
            } else {
                $sql .= $this->newData[$i]." , ";
            }
        }
        $sql = substr($sql, 0, (strlen($sql)-2));
        $sql .= ')';
        //$sql = $this->getInst() . implode(", ", $this->getFields()) . ' FROM ' . $this->getTable() . ' WHERE ' . $this->getWhere();
        $this->setSql($sql);
        return $sql;
    } else {
        print 'Function not available due to wrong data';
    }
    }
}
require('./config/dbconfig.php');

function getNewData() {
    
}
/*
$newData = ['1', 'Doe', 'store', 'desc', 'rand'];

$test = new MysqlInsert($needColumn, $tableName, $newData);
var_dump($test->sqlString());*/
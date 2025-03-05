<?php
require_once('./Classes/MysqlMain.php');

class MysqlUpdate extends MysqlMain {
    private array $needTick;

    public function __construct(array $fields, $table, $where, $sqlHeader, $needTick) {
        parent::__construct('UPDATE ', $fields, $table, $where, 'null', $sqlHeader);
        $this->needTick = $needTick;
    }
    
    //class mod
        //UPDATE $table SET name = 'rand', cim = 'otherRand' WHERE id

    public function sqlString() {
        $header = $this->getHeader();
        if(count($this->needTick) === count($header)) {
        $fields = $this->getFields();
        /*var_dump($header);
        print'<br>';
        var_dump($fields);*/
        /*var_dump(count($this->getHeader()));
        print '<br>';*/
        $sql = $this->getInst() . $this->getTable() . ' SET ';
        for ($i=0; $i < count($header); $i++) { 
            if ($header[$i] === 'id' || $header[$i] === 'Id' || $header[$i] === 'ID') {
                 null;
            } else if ($this->needTick[$i] === 'true') {
                $sql .= $header[$i]." = '".$fields[$i]."', ";
            } else {
                $sql .= $header[$i].' = '.$fields[$i].', ';
            }
        }
         $sql = substr($sql, 0, (strlen($sql)-2));
        $sql .= ' WHERE ' . $this->getWhere();
        $this->setSql($sql);
        return $sql;
    } else {
        print 'Function not available due to wrong data';
    }
    }
}
/*require('./config/dbconfig.php');

$testClass = new MysqlUpdate([1, 2, 3, 4, 5], 'searchmyhome', 'id = 1', $needColumn);
$testClass->sqlString();
var_dump($testClass->sqlString());*/
<?php
require_once('./Classes/MysqlMain.php');

class MysqlSelect extends MysqlMain {

    public function __construct(array $fields, $table, $where) {
        parent::__construct('SELECT ', $fields, $table, $where, 'null', 'null');
    }

    public function sqlString() {
        $sql = $this->getInst() . implode(", ", $this->getFields()) . ' FROM ' . $this->getTable() . ' WHERE ' . $this->getWhere();
        //print 'SQL: '.$sql.'<br>';
        $this->setSql($sql);
        return $sql;
    }
}
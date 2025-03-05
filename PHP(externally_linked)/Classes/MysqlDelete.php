<?php
require_once('./Classes/MysqlMain.php');

class MysqlDelete extends MysqlMain {

    public function __construct(array $fields, $table, $where) {
        parent::__construct('DELETE ', $fields, $table, $where, 'null', 'null');
    }

    public function sqlString() {
        $sql = $this->getInst() . implode(", ", $this->getFields()) . ' FROM ' . $this->getTable() . ' WHERE ' . $this->getWhere();
        $this->setSql($sql);
        return $sql;
    }
}
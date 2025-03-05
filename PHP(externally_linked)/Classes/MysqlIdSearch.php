<?php
require_once('./Classes/MysqlMain.php');

class MysqlIdSearch extends MysqlMain {

    public function __construct($table) {
        parent::__construct('SELECT ', [], $table, '', 'null', 'null');
    }

    public function sqlString() {
        $sql = ''.$this->getInst().' 
    COALESCE(
        ('.$this->getInst().' MIN(t1.id) FROM (
            '.$this->getInst().' @rownum := @rownum + 1 AS id
            FROM ('.$this->getInst().' @rownum := 0) r, '.$this->getTable().'
        ) AS t1
        LEFT JOIN '.$this->getTable().' t2 ON t1.id = t2.id
        WHERE t2.id IS NULL),
        ('.$this->getInst().' MAX(id) + 1 FROM '.$this->getTable().')
        , 1) AS missing_id';
        $this->setSql($sql);
        return $sql;
    }

    /*public function sqlString() {
        $sql = 'SELECT 
        CASE 
            WHEN MIN(t1.id) +1 IS NULL THEN (SELECT MAX(id) +1 FROM '.$this->getTable().')
            ELSE MIN(t1.id)  
        END AS missing_id
        FROM (
            SELECT @rownum := @rownum + 1 AS id
            FROM (SELECT @rownum := 0) r, '.$this->getTable().'
        ) AS t1
        LEFT JOIN '.$this->getTable().' t2 ON t1.id = t2.id
        WHERE t2.id IS NULL';
        $this->setSql($sql);
        return $sql;
    }*/
}
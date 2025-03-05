<?php
require_once('./Classes/MysqlMain.php');

class MysqlJoin extends MysqlMain {
    private string $tableJoined;
    private array $tableJoinedColumns;
    public int $joinedColumn = 2;

    public function __construct(array $fields, $table, $where, $tableJoined, $tableJoinedColumns) {
        parent::__construct('SELECT ', $fields, $table, $where, 'null', 'null');
        $this->tableJoined = $tableJoined;
        $this->tableJoinedColumns = $tableJoinedColumns;
    }
    //$sql = 'SELECT * FROM searchmyhome JOIN locationmyhome ON searchmyhome.Location = locationmyhome.id WHERE searchmyhome.id = 1';

    public function sqlString() {
        $fields = array_values($this->getFields());
        $sql = $this->getInst();// . implode(", ", $this->getFields())
        for ($i=0; $i < count($fields); $i++) {
            if($i === $this->joinedColumn) {
                $sql .= $this->tableJoined.'.'.$fields[$i].' , ';
            } else {
                $sql .= $this->getTable().'.'.$fields[$i].' , ';
            }
        }
        $sql = substr($sql, 0, (strlen($sql)-2))
        . ' FROM ' . $this->getTable()
        . ' JOIN ' . $this->tableJoined . ' ON ' . $this->getTable().'.'.$fields[2].' = '.$this->tableJoined.'.'.$this->tableJoinedColumns[0]
        . ' WHERE ' . $this->getTable().'.'.$this->getWhere();
        //print 'SQL: '.$sql.'<br>';
        $this->setSql($sql);
        return $sql;
    }
}
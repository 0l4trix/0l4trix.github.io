<?php
require_once('./Classes/MysqlMain.php');

class MysqlLike extends MysqlMain {

    private string $tableJoined;
    private array $tableJoinedColumns;
    public int $joinedColumn = 2;
    public int $searchedJoinedColumn = 1;

    public function __construct(array $fields, $table, $where, $searchedText, $tableJoined, $tableJoinedColumns) {
        parent::__construct('SELECT ', $fields, $table, $where, $searchedText, 'null');
        $this->tableJoined = $tableJoined;
        $this->tableJoinedColumns = $tableJoinedColumns;
    }

    public function sqlString($searchInJoined = false) {
        $fields = array_values($this->getFields());
        $sql = $this->getInst();
        for ($i=0; $i < count($fields); $i++) {
            if($i === $this->joinedColumn) {
                $sql .= $this->tableJoined.'.'.$this->tableJoinedColumns[$this->searchedJoinedColumn].' , ';
            } else {
                $sql .= $this->getTable().'.'.$fields[$i].' , ';
            }
        }
        $sql = substr($sql, 0, (strlen($sql)-2))
        . ' FROM ' . $this->getTable()
        . ' JOIN ' . $this->tableJoined . ' ON ' . $this->getTable().'.'.$fields[$this->joinedColumn].' = '.$this->tableJoined.'.'.$this->tableJoinedColumns[0];
        if($searchInJoined === true) {
            $sql .= ' WHERE ' . $this->tableJoined;
        } else {
            $sql .=  ' WHERE ' . $this->getTable();
        }
        $sql .= '.'.$this->getWhere() . " LIKE '%" . $this->getSText() . "%'";
        $this->setSql($sql);
        return $sql;
    }
}
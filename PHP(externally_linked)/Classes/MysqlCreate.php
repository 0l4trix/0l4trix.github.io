<?php
require_once('./Classes/MysqlMain.php');

class MysqlCreate extends MysqlMain {

    public string $int = 'INT(11)';
    public array $isInt = [0];
    public string $varchar = 'VARCHAR(250)';
    public array $notNull = [1, 2, 4];
    public int $unsigned = 2;
    public int $primaryKey = 0;
    public int $joinedColumn = 2;
    public function __construct(array $fields, $table) {
        parent::__construct('CREATE TABLE ', $fields, $table, '', 'null', 'null');
    }

    public function sqlString(string $tableJoined = null, array $tableJoinedOn = null) {
        $fields = $this->getFields();
        $sql = $this->getInst() . $this->getTable() . ' (';
        for ($i=0; $i < count($fields); $i++) {
            in_array($i, $this->isInt) ? $sql .= $fields[$i] . ' ' . $this->int . ', ':
            $sql .= $fields[$i] . ' ' . $this->varchar . ', ';
            if ($tableJoined !== null && $tableJoinedOn !== null && $i === $this->unsigned) {
                $sql = substr($sql, 0, (strlen($sql)-2));
                $sql .= ' UNSIGNED, ';
            } else if (in_array($i, $this->notNull)) {
                $sql = substr($sql, 0, (strlen($sql)-2));
                $sql .= ' NOT NULL, ';
            }
            if ($i === $this->primaryKey) {
                $sql = substr($sql, 0, (strlen($sql)-2));
                $sql .= ' UNSIGNED AUTO_INCREMENT PRIMARY KEY, ';
            }
        }
        if($tableJoined !== null && $tableJoinedOn !== null) {
            $sql .= ' FOREIGN KEY (' . $fields[$this->joinedColumn] . ') REFERENCES ' . $tableJoined . '(' . $tableJoinedOn[$this->primaryKey] . ')';
        } else {
        $sql = substr($sql, 0, (strlen($sql)-2));
        }
        $sql .= ')';
        $this->setSql($sql);
        return $sql;
    }

}
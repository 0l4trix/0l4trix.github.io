<?php
require_once('./Classes/MysqlSelect.php');

class MysqlPassword extends MysqlSelect {

    public function __construct($fields, $table, $where) {
        parent::__construct($fields, $table, $where);
    }

    public function checkPassword(array $userData, string $password) {
        if(count($userData) === 1){
            return password_verify($password, $userData[0]["Pass"]) ? $userData[0]['Username'] : false;
        }
        return false;
    }

    public function sqlString() {
        return parent::sqlString();
    }

}
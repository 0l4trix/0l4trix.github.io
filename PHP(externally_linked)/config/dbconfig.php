<?php
/*$servername     = "localhost";
$username       = "prooktat_0l4trix";
$password       = "OGNHFSNARNTSS";
$dbname         = "prooktat_0l4trix";*/

$servername     = "localhost";
$username       = "root";
$password       = "";
$dbname         = "test";


$tableLogin = 'loginfo';
$tableMain = 'smh';
$tableSub = 'locmh';

$loginInfo = ['Id', 'Username', 'Email', 'Pass'];
//needs to be same amount as $loginInfo, tells if sql needs '' around text
$needTickLogin = ['false', 'true', 'true', 'true'];

//id IS A MUST!
$needColumn = ['Id','Name', 'Location', 'Description', 'Amount'];
$needTick = ['false', 'true', 'false', 'true', 'true'];

$tableJoinedColumns = ['Id', 'Location', 'Room'];
$needTickJoined = ['false', 'true', 'true'];
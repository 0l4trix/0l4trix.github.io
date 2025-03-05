<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

        <?php
        require('./config/dbconfig.php');

    require('./Classes/MysqlSelect.php');
    require('./Classes/Form.php');
    require('./Classes/MysqlDelete.php');
    require('./Classes/MysqlInsert.php');
    require('./Classes/MysqlIdSearch.php');
    require('./Classes/MysqlCreate.php');
    $table1 = 'searchmyhome';
    $getId = new MysqlIdSearch($table1);
    $getId->sqlString();
    $newId = implode($getId->getData(true)[0]);
    var_dump($newId);
    /*$username = 'testCreate';
    $tableName = $username.'_'.$tableMain;
    $tableJoined = $username.'_'.$tableSub;


    $newSubDb = new MysqlCreate($tableJoinedColumns, $username.'_'.$tableJoined);
    //var_dump($newSubDb->sqlString());
    $newSubDb->sqlString();
    $newSubDb->getData();
    print'<br>';

    $newMainDb = new MysqlCreate($needColumn, $username.'_'.$tableName);
    $newMainDb->isInt = [0, 2];
    //var_dump($newMainDb->sqlString($username.'_'.$tableJoined, $tableJoinedColumns));
    $newMainDb->sqlString($username.'_'.$tableJoined, $tableJoinedColumns);
    $newMainDb->getData();*/


        ?>



    <?php
    /*SELECT location AS $header[] FROM table1;
    SELECT * FROM film WHERE title LIKE '%searchedText%';
    */

//$sql = 'SELECT '.$tablename1.'.'.$needInfo1[0].', '. $tablename2.'.'.$needInfo2[1].' FROM '. $tablename1.' JOIN '.$tablename2.' ON '.$tablename1.'.'.$needInfo1[1] .' = '. $tablename2.'.'.$needInfo2[0];
    //$sql = 'SELECT searchmyhome.name, locationmyhome.location FROM searchmyhome JOIN locationmyhome ON searchmyhome.Location = locationmyhome.id';
    //$sql = 'SELECT * FROM searchmyhome JOIN locationmyhome ON searchmyhome.Location = locationmyhome.id WHERE searchmyhome.id = 1';
?>
    
</body>
</html>
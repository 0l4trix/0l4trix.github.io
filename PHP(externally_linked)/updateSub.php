<?php
session_start();
ob_start();
if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SearchMyHome | Update</title>
    <link rel="stylesheet" href="./css/form.css">
</head>
<body>
        <header>
            <a id="home" href="index.php">SearchMyHome</a>
            <a id="logout" href="logout.php">Logout</a>
        </header>

    <div class="main">
        <div class="upper">
    <?php
        require('./config/dbconfig.php');

        require('./Classes/MysqlSelect.php');
        require('./Classes/Table.php');
        require('./Classes/MysqlUpdate.php');
        require('./Classes/Form.php');
        if(isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } else {
            $username = $_COOKIE['username'];
        }
        $updateID = $_SESSION['updateID'];
        $tableName = $username.'_'.$tableMain;
        $tableJoined = $username.'_'.$tableSub;

        $dataSelect = new MysqlSelect(['*'], $tableJoined, 'id = '.$updateID);
        $dataSelect->sqlString();
        $datas = ($dataSelect->getData(true));
        
        $form = new Form($datas, $tableJoinedColumns);
        print($form->generateForm());

        $newDatas = [];

        $submit = $form->getSubmit();
        $cancel = $form->getCancel();

        if(isset($_POST[$submit])) {
        foreach ($datas as $key => $data) {
            $i = 0;
            foreach ($data as $keys => $value) {
            $i++;
            array_push($newDatas, filter_var($_POST[$tableJoinedColumns[$i-1]], FILTER_SANITIZE_SPECIAL_CHARS));
            }
        }
        $update = new MysqlUpdate($newDatas, $tableJoined, 'id = '.$updateID, $tableJoinedColumns, $needTickJoined);
        $update->sqlString();
        $update->getData();
        header('Location: locationTable.php');
        exit;
        }

        if(isset($_POST[$cancel])) {
            header('Location: locationTable.php');
            exit;
        }
    ?></div>
    </div>
    
</body>
</html>
<?php
}else{
    header('Location: login.php');
}
?>
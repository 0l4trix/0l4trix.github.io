<?php
session_start();
ob_start();
if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
    if(isset($_SESSION['warning'])) {
        $warning = $_SESSION['warning'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SearchMyHome | Add new row</title>
        <link rel="stylesheet" href="./css/form.css">
    </head>
    <body>
        <header>
            <a id="home" href="index.php">SearchMyHome</a>
            <a id="logout" href="logout.php">Logout</a>
        </header>
        
        <div class="main">

        <?php
        if(isset($warning)) {
            print'<p id="warning">'.$warning.'</p>';
        }
        require('./config/dbconfig.php');

    require('./Classes/MysqlSelect.php');
    require('./Classes/Form.php');
    require('./Classes/MysqlDelete.php');
    require('./Classes/MysqlInsert.php');
    require('./Classes/MysqlIdSearch.php');
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = $_COOKIE['username'];
    }
    $tableName = $username.'_'.$tableMain;
    $tableJoined = $username.'_'.$tableSub;

    $idSearch = new MysqlIdSearch($tableJoined);
    $idSearch->sqlString();
    $idArray = $idSearch->getData(true);
    $nextId = implode($idArray[0]);

    $datas = [['Id' => $nextId, 1 => '', 2 => '']];
        
    $form = new Form($datas, $tableJoinedColumns);
    print($form->generateForm());

    $newDatas = [];

    $submit = $form->getSubmit();
    $cancel = $form->getCancel();

    if(isset($_POST[$submit])) {
    foreach ($datas as $key => $data) {
        $i = 0;
        foreach ($data as $key => $value) {
        $i++;
            if(isset($_POST[$tableJoinedColumns[$i-1]]) && $_POST[$tableJoinedColumns[$i-1]] !== "") {
            array_push($newDatas, filter_var($_POST[$tableJoinedColumns[$i-1]], FILTER_SANITIZE_SPECIAL_CHARS));
            } else {
                $_SESSION['warning'] = 'Please in fill ALL the rows!';
                header('Location: addRowSub.php');
                exit;
            }
        }
    }
    $insert = new MysqlInsert($tableJoinedColumns, $tableJoined, $newDatas, $needTickJoined);
    $insert->sqlString();
    $insert->getData();
    if(isset($_SESSION['warning'])) {
        unset($_SESSION['warning']);
    }
    header('Location: locationTable.php');
    exit;
    }

    if(isset($_POST[$cancel])) {
        if(isset($_SESSION['warning'])) {
            unset($_SESSION['warning']);
        }
        header('Location: locationTable.php');
        exit;
    }
        ?></div>
    </body>
</html>
<?php
}else{
    header('Location: login.php');
}
?>

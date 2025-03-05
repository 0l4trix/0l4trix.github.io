<?php
session_start();
ob_start();
if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SearchMyHome | Storage locations</title>
        <link rel="stylesheet" href="./css/main.css">
    </head>
    <body>
        <header>
            <a id="home" href="index.php">SearchMyHome</a>
            <a id="logout" href="logout.php">Logout</a>
        </header>
        
        <div class="main">

        <?php
        require('./config/dbconfig.php');

    require('./Classes/MysqlSelect.php');
    require('./Classes/Table.php');
    require('./Classes/MysqlDelete.php');
    require('./Classes/MysqlJoin.php');
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = $_COOKIE['username'];
    }
    $tableName = $username.'_'.$tableMain;
    $tableJoined = $username.'_'.$tableSub;
    $footer = '<a href="addRowSub.php">Add new row</a>';

    $dataSelect = new MysqlSelect(['*'], $tableJoined, 'id != false');
    $dataSelect->sqlString();
    $datas = ($dataSelect->getData(true));

    $table = new Table($datas);
    $table->getHeader();
    $siteContent = print($table->generateTable($footer));
        
        if(isset($_POST['delete'])){
            $deleteID = (int)$_POST['delete'];

            session_start();
            $_SESSION['deleteID'] = $deleteID;
            header('Location: confirmDeleteSub.php');
            exit;
        }

        if(isset($_POST['update'])) {
            $updateID = (int)$_POST['update'];

            session_start();
            $_SESSION['updateID'] = $updateID;
            header('Location: updateSub.php');
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
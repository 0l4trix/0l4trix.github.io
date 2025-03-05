<?php
session_start();
ob_start();
if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SearchMyHome | All Items</title>
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

    $footer = '<a href="addRowMain.php">Add new row</a>';

    $dataSelect = new MysqlJoin($needColumn, $tableName, 'id != false', $tableJoined, $tableJoinedColumns);
    $dataSelect->sqlString();
    $datas = ($dataSelect->getData(true));

    $table = new Table($datas);
    $table->getHeader();
    $checkLoc = new MysqlSelect(['*'], $tableJoined, 'id != false LIMIT 1');
    $checkLoc->sqlString();
    if (count($checkLoc->getData(true)) === 0) {
        $siteContent = print($table->generateTable());
    } else {
        $siteContent = print($table->generateTable($footer));
    }

        
        if(isset($_POST['delete'])){
            $deleteID = (int)$_POST['delete'];
            $_SESSION['deleteID'] = $deleteID;
            $_SESSION['redirect'] = 'mainTable.php';
            header('Location: confirmDeleteMain.php');
            exit;
        }

        if(isset($_POST['update'])) {
            $updateID = (int)$_POST['update'];
            $_SESSION['updateID'] = $updateID;
            $_SESSION['redirect'] = 'mainTable.php';
            header('Location: updateMain.php');
            exit;
        }
        ?>
        </div>
    </body>
</html>
<?php
}else{
    header('Location: login.php');
}
?>

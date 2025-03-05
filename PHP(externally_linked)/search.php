<?php
session_start();
ob_start();
if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SearchMyHome | Search</title>
        <link rel="stylesheet" href="./css/main.css">
    </head>
    <body>
        <header>
            <a id="home" href="index.php">SearchMyHome</a>
            <a id="logout" href="logout.php">Logout</a>
        </header>

        <div class="main">
            <form action="" method="POST">
                <div class="row">
                <input type="text" name="searchArea" placeholder="Type here">
                </div>
                <div class="row">
                <label for="searchColumn">Search in?</label>
                <select name="searchColumn" id="searchColumn">
                    <option value="name">Name</option>
                    <option value="location">Location (Storage unit)</option>
                    <option value="room">Location (Room)</option>
                    <option value="desc">Description</option>
                </select>
                </div>
                <button type="submit" id="search">Search</button>
            </form>
        <?php
        require('./config/dbconfig.php');

    require('./Classes/MysqlLike.php');
    require('./Classes/MysqlJoin.php');
    require('./Classes/Table.php');
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = $_COOKIE['username'];
    }
    $tableName = $username.'_'.$tableMain;
    $tableJoined = $username.'_'.$tableSub;

    if (isset($_POST['searchArea'])) {
        $searchedInfo = filter_var($_POST['searchArea'], FILTER_SANITIZE_SPECIAL_CHARS);
        
        $selectedOption = $_POST['searchColumn'];
        switch ($selectedOption) {
            case 'name':
                $selectedOption = 1;
                break;
            case 'location':
                $selectedOption = 2;
                break;
            case 'desc':
                $selectedOption = 3;
                break;
            case 'room':
                $selectedOption = 4;
                break;
        }
        if($selectedOption === 4) {
            $search = new MysqlLike($needColumn, $tableName, $tableJoinedColumns[$selectedOption -2], $searchedInfo, $tableJoined, $tableJoinedColumns);
            $search->searchedJoinedColumn = 2;
            $search->sqlString(true);
        } else if($selectedOption === 2) {
            $search = new MysqlLike($needColumn, $tableName, $needColumn[$selectedOption], $searchedInfo, $tableJoined, $tableJoinedColumns);
            $search->sqlString(true);
        } else {
            $search = new MysqlLike($needColumn, $tableName, $needColumn[$selectedOption], $searchedInfo, $tableJoined, $tableJoinedColumns);
            $search->sqlString();
        }
        $datas = $search->getData(true);
        $table = new Table($datas);
        $table->getHeader();
        $siteContent = print($table->generateTable());
    }
    if(isset($_POST['delete'])){
        $deleteID = (int)$_POST['delete'];
        $_SESSION['deleteID'] = $deleteID;
        $_SESSION['redirect'] = 'search.php';
        header('Location: confirmDeleteMain.php');
        exit;
    }

    if(isset($_POST['update'])) {
        $updateID = (int)$_POST['update'];
        $_SESSION['updateID'] = $updateID;
        $_SESSION['redirect'] = 'search.php';
        header('Location: updateMain.php');
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

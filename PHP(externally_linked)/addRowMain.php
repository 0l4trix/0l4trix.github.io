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
            <div class="upper">

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

    $idSearch = new MysqlIdSearch($tableName);
    $idSearch->sqlString();
    $idArray = $idSearch->getData(true);
    $nextId = implode($idArray[0]);

    $rowSelect = new MysqlSelect($tableJoinedColumns, $tableJoined, 'Id != false');
    $rowSelect->sqlString();
    $row = $rowSelect->getData(true);

    $id = array();
    $location = array();

    foreach ($row as $item) {
        $id[] = $item['Id'];
        $location[] = $item['Location'];
    }

    $datas = [['Id' => $nextId, 1 => '', 2 => '', 3 => '', 4 => '']];
        
    $form = new Form($datas, $needColumn);
    $form->setSName($id);
    $form->setSValue($location);
    print($form->generateForm(true));

    $newDatas = [];

    $submit = $form->getSubmit();
    $cancel = $form->getCancel();

    if(isset($_POST[$submit])) {
        $selectedOption = $_POST['searchColumn'];
        for ($i=0; $i < count($id); $i++) { 
            switch ($selectedOption) {
                case ''.$id[$i].'':
                    $selectedOption = $id[$i];
                    break;
            }
        }
    foreach ($datas as $key => $data) {
        $i = 0;
        foreach ($data as $keys => $value) {
        $i++;
        if ($i === 3) {
            array_push($newDatas, $selectedOption);
        } else if
            (isset($_POST[$needColumn[$i-1]]) && $_POST[$needColumn[$i-1]] !== "") {
                array_push($newDatas, filter_var($_POST[$needColumn[$i-1]], FILTER_SANITIZE_SPECIAL_CHARS));
            } else {
            $_SESSION['warning'] = 'Please in fill ALL the rows!';
            header('Location: addRowMain.php');
            exit;
            }
        }
    }
    $insert = new MysqlInsert($needColumn, $tableName, $newDatas, $needTick);
    $insert->sqlString();
    $insert->getData();
    if(isset($_SESSION['warning'])) {
        unset($_SESSION['warning']);
    }
    header('Location: mainTable.php');
    exit;
    }

    if(isset($_POST[$cancel])) {
        if(isset($_SESSION['warning'])) {
            unset($_SESSION['warning']);
        }
        header('Location: mainTable.php');
        exit;
    }
        ?>
        </div>
        </div>
    </body>
</html>
<?php
}else{
    header('Location: login.php');
}
?>

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

        $dataSelect = new MysqlSelect(['*'], $tableName, 'id = '.$updateID);
        $dataSelect->sqlString();
        $datas = ($dataSelect->getData(true));

        $rowSelect = new MysqlSelect($tableJoinedColumns, $tableJoined, 'Id != false');
        $rowSelect->sqlString();
        $row = $rowSelect->getData(true);
    
        $id = array();
        $location = array();
    
    foreach ($row as $item) {
        $id[] = $item['Id'];
        $location[] = $item['Location'];
    }
        
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
            } else {
                array_push($newDatas, filter_var($_POST[$needColumn[$i-1]], FILTER_SANITIZE_SPECIAL_CHARS));
            }
            }
        }
        $update = new MysqlUpdate($newDatas, $tableName, 'id = '.$updateID, $needColumn, $needTick);
        $update->sqlString();
        $update->getData();
        header('Location: '.$_SESSION['redirect'].'');
        exit;
        }

        if(isset($_POST[$cancel])) {
            header('Location: '.$_SESSION['redirect'].'');
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
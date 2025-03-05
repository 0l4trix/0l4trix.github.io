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
    <title>SearchMyHome | Confirm</title>
    <link rel="stylesheet" href="./css/form.css">
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
        require('./Classes/Form.php');
        require('./Classes/MysqlDelete.php');
        if(isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } else {
            $username = $_COOKIE['username'];
        }
        $tableName = $username.'_'.$tableMain;
        $tableJoined = $username.'_'.$tableSub;
        $deleteID = $_SESSION['deleteID'];

        $deleteData = new MysqlSelect(['*'], $tableJoined, 'id = '.$deleteID);
        $deleteData->sqlString();
        $datas = $deleteData->getData(true);

        $form = new Form($datas, $tableJoinedColumns);
        $form->confirmDelete = 'Delete this line?<br><br>ALL STORED ITEMS WILL BE DELETED AS WELL!';
        print($form->generateConfirmForm());

        $submit = $form->getSubmit();
        $cancel = $form->getCancel();

        if(isset($_POST[$submit])) {
            $delete = new MysqlDelete([''], $tableJoined, 'id = '.$deleteID);
            $delete->sqlString();
            $deleteJoin = new MysqlDelete([''], $tableName, $needColumn[2].' = '.$deleteID);
            $deleteJoin->sqlString();
            $deleteJoin->getData();
            $delete->getData();
            header('Location: locationTable.php');
            exit;
        }

        if(isset($_POST[$cancel])) {
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
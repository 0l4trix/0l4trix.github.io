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

        $deleteData = new MysqlSelect(['*'], $tableName, 'id = '.$deleteID);
        $deleteData->sqlString();
        $datas = $deleteData->getData(true);

        $form = new Form($datas, $needColumn);
        print($form->generateConfirmForm());

        $submit = $form->getSubmit();
        $cancel = $form->getCancel();

        if(isset($_POST[$submit])) {
            $delete = new MysqlDelete([''], $tableName, 'id = '.$deleteID);
            $delete->sqlString();
            $delete->getData();
            header('Location: '.$_SESSION['redirect'].'');
            exit;
        }

        if(isset($_POST[$cancel])) {
            header('Location: '.$_SESSION['redirect'].'');
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
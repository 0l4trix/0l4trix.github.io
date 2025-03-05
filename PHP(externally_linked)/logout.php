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
    <title>SearchMyHome | Logout</title>
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

        $form = new Form([], []);
        $form->confirmDelete = "Do you want to logout?";
        $form->save = 'Yes';
        $form->back = "No";
        print($form->generateConfirmForm(false));

        $submit = $form->getSubmit();
        $cancel = $form->getCancel();

        if(isset($_POST[$submit])) {
            if(isset($_POST['username'])) {
                unset($_SESSION['username']);
            } else {
                unset($_COOKIE['username']);
                setcookie('username', '', -1, '/'); 
            }
            header('Location: login.php');
            exit;
        }

        if(isset($_POST[$cancel])) {
            header('Location: index.php');
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
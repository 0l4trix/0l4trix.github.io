<?php
session_start();
        if(!isset($_SESSION['passText'])) {
        $_SESSION['passText'] = 'Password';
        }
        if(!isset($_SESSION['emailText'])) {
        $_SESSION['emailText'] = 'Email';
        }
        if(!isset($_SESSION['usernameText'])) {
        $_SESSION['usernameText'] = 'Username';
        }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>SearchMyHome | Sign up</title>
        <link rel="stylesheet" href="./css/login.css">
    </head>
    <body>
    <?php
    $passText = $_SESSION['passText'];
    $emailText = $_SESSION['emailText'];
    $usernameText = $_SESSION['usernameText'];
    ?>
	<div class="main">  	
		<input type="checkbox" id="chk" aria-hidden="true">

			<div class="upper">
				<form method="POST">
					<label for="chk" aria-hidden="true">Sign up</label>
					<input type="text" name="username" placeholder="<?php echo $usernameText ?>" required="">
					<input type="email" name="email" placeholder="<?php echo $emailText ?>" required="">
					<input type="password" name="password" placeholder="<?php echo $passText ?>"required="">
					<input type="password" name="passwordRepeat" placeholder="Password" required="">
					<button>Sign up</button>
				</form>
			</div>

			<div class="lower">
            <label id="a" for="chk" aria-hidden="true"><a href="login.php">Login</a></label>
			</div>
	</div>
        
        <?php
        require('./Classes/MysqlPassword.php');
        require('./Classes/MysqlIdSearch.php');
        require('./Classes/MysqlInsert.php');
        require('./Classes/MysqlCreate.php');
        require('./config/dbconfig.php');

        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordRepeat'])) {

            if($_POST['username'] === (filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS))) {
                $username = $_POST['username'];
                $userCheck = new MysqlSelect(['*'], $tableLogin, "username = '".$username."'");
                $userCheck->sqlString();
                if(count($userCheck->getData(true)) !== 0) {
                    unset($_SESSION['passText'], $_SESSION['emailText']);
                    $_SESSION['usernameText'] = 'Username is already taken';
                    header('Location: signup.php');
                    exit;
                }
            } else {
                unset($_SESSION['passText'], $_SESSION['emailText']);
                $_SESSION['usernameText'] = "Username can't contain special characters";
                header('Location: signup.php');
                exit;
            }

            if($_POST['email'] === (filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS))) {
                $email = $_POST['email'];
                $emailCheck = new MysqlSelect(['*'], $tableLogin, "email = '".$email."'");
                $emailCheck->sqlString();
                if(count($emailCheck->getData(true)) !== 0) {
                    unset($_SESSION['passText'], $_SESSION['usernameText']);
                    $_SESSION['emailText'] = 'Email is already taken';
                    header('Location: signup.php');
                    exit;
                }
            } else {
                unset($_SESSION['passText'], $_SESSION['usernameText']);
                $_SESSION['emailText'] = "Email can't contain special characters";
                header('Location: signup.php');
                exit;
            }

            if($_POST['password'] !== $_POST['passwordRepeat']) {
                unset($_SESSION['usernameText'], $_SESSION['emailText']);
                $_SESSION['passText'] = "The two passwords don't match";
                header('Location: signup.php');
                exit;
            } else if($_POST['password'] !== (filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS))) {
                unset($_SESSION['usernameText'], $_SESSION['emailText']);
                $_SESSION['passText'] = "Password can't contain special characters";
                header('Location: signup.php');
                exit;
            } else {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $getId = new MysqlIdSearch($tableLogin);
                $getId->sqlString();
                $newId = implode($getId->getData(true)[0]);

                $newData = [$newId, $username, $email, $password];
                $newUser = new MysqlInsert($loginInfo, $tableLogin, $newData, $needTickLogin);
                $newUser->sqlString();
                $newUser->getData();

                $newSubDb = new MysqlCreate($tableJoinedColumns, $username.'_'.$tableSub);
                $newSubDb->sqlString();
                $newSubDb->getData();
            
                $newMainDb = new MysqlCreate($needColumn, $username.'_'.$tableMain);
                $newMainDb->isInt = [0, 2];
                $newMainDb->sqlString($username.'_'.$tableSub, $tableJoinedColumns);
                $newMainDb->getData();

                header('Location: login.php');
                exit;
            }
        }
        ?>
    </body>
</html>
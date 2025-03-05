<?php
session_start();
if(!isset($_SESSION['text'])) {
$_SESSION['text'] = 'Email';
}
$text = $_SESSION['text'];
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>SearchMyHome | Login</title>
        <link rel="stylesheet" href="./css/login.css">
    </head>
    <body>
	<div class="main">  	
		<input type="checkbox" id="chk" aria-hidden="true">

			<div class="upper">
				<form method="POST">
					<label for="chk" aria-hidden="true">Login</label>
					<input type="email" name="email" placeholder="<?php echo $text ?>" required="">
					<input type="password" name="password" placeholder="Password" required="">
                    <div class="remember"><label for="remember-me" id="remember">Remember me</label>
                    <input id="remember-me" name="remember-me" type="checkbox"></div>
					<button>Login</button>
				</form>
			</div>

			<div class="lower">
                <label id="a" for="chk" aria-hidden="true"><a href="signup.php">Sign up</a></label>
            </div>
    </div>
        
        <?php
        require('./Classes/MysqlPassword.php');
        require('./config/dbconfig.php');

        if(isset($_POST['email']) && isset($_POST['password'])){
            if($_POST['email'] === filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS)) {
                $email = $_POST['email'];
            } else {
                $email = '';
            }
            $password = $_POST['password'];

            $mysqlpass = new MysqlPassword($loginInfo, $tableLogin, "email = '$email'");
            $mysqlpass->sqlString();
            $userData = $mysqlpass->getData(true);
            $checkPass = $mysqlpass->checkPassword($userData, $password);
            $username = $checkPass;

            if($username){
                $_SESSION['username'] = $username;
                if(isset($_POST['remember-me'])) {
                    $cookie_lifetime = time() + (30 * 24 * 60 * 60);
                    setcookie('username', $username, $cookie_lifetime, "/");
                }
                unset($_SESSION['text']);
                header('Location: index.php');
                exit;
            }else{
                $_SESSION['text'] = 'Incorrect email or password';
                header('Location: login.php');
                exit;
            }
        }
        ?>

    </body>
</html>
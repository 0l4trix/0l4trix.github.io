<?php
session_start();
ob_start();
if(isset($_SESSION['username']) || isset($_COOKIE['username'])){
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        $username = $_COOKIE['username'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SearchMyHome</title>
    <link rel="stylesheet" href="./css/form.css">
</head>
<body>
        <header>
            <a id="home" href="index.php"><?php print'Welcome '.$username.'!' ?></a>
            <a id="logout" href="logout.php">Logout</a>
        </header>
        
    <div class="main">
        <div class="left">
                <a href="locationTable.php"><button>Storages<pre>(START HERE)</pre></button></a>
        </div>
        <div class="mid">
                <a href="search.php"><button>Search</button></a>
        </div>
        <div class="right">
                <a href="mainTable.php"><button>Stored items</button></a>
        </div>
    </div>
</body>
</html>
<?php
}else{
    header('Location: login.php');
}
?>
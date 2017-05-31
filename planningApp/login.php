<?php
/**
 * Created by PhpStorm.
 * User: geldh
 * Date: 31/05/2017
 * Time: 9:18
 */

session_start();
$_SESSION['username'] = '';

$userinfo = array(
    'admin'=>'Student1',
);

if(isset($_GET['logout'])) {
    $_SESSION['username'] = '';
    header('Location:  ' . $_SERVER['PHP_SELF']);
}

if(isset($_POST['username'])) {
    if($userinfo[$_POST['username']] == $_POST['password']) {
        $_SESSION['username'] = $_POST['username'];
    }else {
        ?>
        <p>Foute login gegevens.</p>
        <?php
    }
}



?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php if($_SESSION['username']): ?>
  <?php  header("Location: Main.php" ); ?>
<?php endif; ?>
<div class="container" style="max-width:500px;">
    <br>
    <br>
    <h1 style="font-family:'Arial'" style="text-align: center;">Login</h1>
    <h3 style="font-family:'Arial'" style="text-align: center;">Creatie events en shifts</h3>
<br>
    <br>
    <form name="login" action="" method="post">
    <div class="form-group">
        <label>Username: </label>
        <input class="form-control" type="text" name="username" value=""/>
    </div>
    <div class="form-group">
        <label>Password: </label>
        <input class="form-control" type="password" name="password" value="" />
    </div>
    <input type="submit" class="btn btn-default" name="submit" value="Submit" />
</form>



    <br>
    <br>
    <!-- Footer -->
    <hr>
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Groep C - Cloud en Planning</p>
            </div>
        </div>
    </footer>

</div>
</body>
</html>
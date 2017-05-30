<!DOCTYPE html>
<html lang="nl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Shift</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <![endif]-->

    <!--bron code in body: http://bootsnipp.com/snippets/featured/list-grid-view -->

</head>

<body>

<?php
$conn = mysqli_connect("localhost","root", "lalolu4", "owncloud");
$sql = "SELECT u.uid, u.displayname FROM oc_group_user gu JOIN oc_users u ON u.uid = gu.uid and gu.gid = 'collaborator'";
$result = $conn->query($sql);

?>

<br>
<br>
<div class="container">
    <h1>Shift aanmaken</h1>
    <form method="post" name="formShift" action="shiftVerwerking.php">
        <?php  if ($result->num_rows > 0) {
            ?>
            <div class="form-group">
                <label>Kies medewerker</label>
                <select name="collaborator" class="form-control">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["uid"] . '">' . $row["displayname"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <?php
        }else{
           echo" <label>! Geen medewerkers beschikbaar, er kaan geen shift worden aangemaakt</label>";
        }
        ?>

        <div class="form-group">
            <label>Dag</label>
            <input type="date" name="dag" class="form-control" id="start"">
        </div>
        <div class="form-group">
            <label>Start</label>
            <input type="time" name="start" class="form-control" id="start"">
        </div>
        <div class="form-group">
            <label>Einde</label>
            <input type="time" name="einde" class="form-control" id="einde"">
        </div>
        <div class="form-group">
            <label>Locatie</label>
            <input type="text" name="locatie" class="form-control" id="locatie">
        </div>
        <button type="submit" value="Send" class="btn btn-success">Submit</button>
        <a href="Main.php" class="btn btn-default">Terug</a>
    </form>
    <hr>
    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy;</p>
            </div>
        </div>
    </footer>
</div>
<!-- /.container -->
<!-- jQuery -->
<script src="bootstrap/js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
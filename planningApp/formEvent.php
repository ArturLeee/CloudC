<!DOCTYPE html>
<html lang="nl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Event</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <![endif]-->

    <!--bron code in body: http://bootsnipp.com/snippets/featured/list-grid-view -->

</head>

<body>
<br>
<br>

<?php
$conn = mysqli_connect("localhost","root", "lalolu4", "owncloud");
$sql = "SELECT u.uid, u.displayname FROM oc_group_user gu JOIN oc_users u ON u.uid = gu.uid and gu.gid = 'gastspreker'";
$result = $conn->query($sql);


/*$stmt = $conn->prepare("SELECT uid FROM oc_group_user WHERE gid = gastspreker");
$stmt->execute();
$stmt->bind_result($uid);
$result = array();
while($row=$stmt->fetch()){
    array_push($result,$uid);
}

*/


?>
<div class="container" style="max-width:750px;">
    <h1>Event aanmaken</h1>
    <form method="post" action="eventVerwerking.php">
        <div class="form-group">
            <label for="naam">Naam</label>
            <input type="text" name="naam" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Kies gastspreker</label>
            <select title="gastspreker" name="gastspreker" class="form-control">
                <option value="geen" >Geen gastspreker event</option>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<option value="'.$row["uid"].'">'.$row["displayname"].'</option>';
                }
            }
            /*
            foreach($result as $value):
                echo '<option value="'.$value.'">'.$value.'</option>'; //close your tags!!
            endforeach;*/
            ?>
            </select>
        </div>
        <div class="form-group">
            <label>Beschrijving</label>
            <textarea name="beschrijving" title="bericht" class="form-control" id="description"  rows="2" style="resize:vertical; max-height:400px;" required></textarea>
        </div>
        <div class="form-group">
            <label>Dag</label>
            <input type="date" name="dag" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Start</label>
            <input type="time" name="start" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Einde</label>
            <input type="time" name="einde" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Locatie</label>
            <input type="text" name="locatie" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
        <a href="Main.php" class="btn btn-default">Terug</a>
    </form>

<?php
$conn->close();
?>

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
<!-- /.container -->

<!-- jQuery -->
<script src="bootstrap/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>


</body>

</html>
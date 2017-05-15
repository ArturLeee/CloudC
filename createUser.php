<?php
$ownAdminname = "admin";
$ownAdminpassword = "Student1";
$userName = "bbb";
$RRpassword = "123456";
$url = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users';
    echo "Created URL is " . $url . "<br/>";
 
    $ownCloudPOSTArray = array('userid' => $userName, 'password' => $RRpassword);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $ownCloudPOSTArray);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    echo "Response from curl :" . $response;
    echo "<br/>Created a new user in owncloud<br/>";
?>
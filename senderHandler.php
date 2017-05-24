<?php
echo "Begun processing credentials , first it will be stored in local variables" . "<br/>";

// Loading into local variables
/*$userName = $_POST['username'];
$RRpassword = $_POST['password'];*/


$userName = "Jef"; //NAAM KLANT
$RRpassword = "1234"; // pasword klant

echo "Hello " . $userName . "<br/>";
echo "Your password is " . $RRpassword . "<br/>";

 // Login Credentials as Admin
 $ownAdminname = 'admin';
 $ownAdminpassword = 'lalolu4';


// Add data, to owncloud post array and then Send the http request for creating a new user
$url = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users';
echo "Created URL is " . $url . "<br/>"; 

$ownCloudPOSTArray = array('userid' => $userName, 'password' => $RRpassword );

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $ownCloudPOSTArray);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "Response from curl :" . $response;
echo "<br/>Created a new user in owncloud<br/>";

// Add to a group called 'Users'
$groupUrl = $url . '/' . $userName . '/' . 'groups';
echo "Created groups URL is " . $groupUrl . "<br/>";

$ownCloudPOSTArrayGroup = array('groupid' => 'Users');

$ch = curl_init($groupUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $ownCloudPOSTArrayGroup);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "Response from curl :" . $response;
echo "<br/>Added the new user to default group in owncloud";

?>
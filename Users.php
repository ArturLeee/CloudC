<?php

class Users
{
    public static function createUsers($id, $username, $email, $group)
    {
        $ownAdminname = "admin";
        $ownAdminpassword = "Student1";
        $passwordUser = "Student1";
        $url = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users';
        echo "Created URL is " . $url . "<br/>";

        //create user
        $ownCloudPOSTArray = array('userid' => $id, 'password' => $passwordUser);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ownCloudPOSTArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        echo "Response from curl :" . $response;
        echo "<br/>Created a new user in owncloud<br/>";

//add name to id

        $conn = mysqli_connect("localhost", "root", "lalolu4", "owncloud");
        $sql = "UPDATE oc_users SET displayname = '$username' WHERE uid='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "username updated successfully";
        } else {
            echo "Error updating username: " . $conn->error;
        }

        // add mail
        $mailkeyword = "email";
        $settings = "settings";
        $sql = "INSERT INTO oc_preferences (userid, appid, configkey, configvalue) VALUES ('$id', '$settings', '$mailkeyword', '$email')";

        if ($conn->query($sql) === TRUE) {
            echo "mail updated successfully";
        } else {
            echo "Error updating mail: " . $conn->error;
        }

        //add group to user
        //POST http://admin:secret@example.com/ocs/v1.php/cloud/users/Frank/groups -d groupid="newgroup"
        $urlGroup = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users/' . $id . '/groups';
        $groupArray = array('groupid' => $group);
        $ch = curl_init($urlGroup);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $groupArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        echo "Response group from curl :" . $response;
        echo "<br/>Added group to the new user in owncloud<br/>";

        /*
                public static function changeGroup($login, $password, $username, $group)
                {
                    $ownAdminname = $login;
                    $ownAdminpassword = $password;
                    $url = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users';
                    echo "Created URL is " . $url . "<br/>";

                    //add group to user
                    //POST http://admin:secret@example.com/ocs/v1.php/cloud/users/Frank/groups -d groupid="newgroup"
                    $urlGroup = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users/' . $username . '/groups';
                    $groupArray = array('groupid' => $group);
                    $ch = curl_init($urlGroup);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $groupArray);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    echo "Response group from curl :" . $response;
                    echo "<br/>Added group to the new user in owncloud<br/>";
                }

            }
            */
    }
}
?>
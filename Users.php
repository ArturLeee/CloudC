<?php

class Users
{
    public static function createUsers($login, $password, $id, $username, $email, $group)
    {
        $ownAdminname = $login;
        $ownAdminpassword = $password;
        $passwordUser = "Student1";
        $url = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users';
        echo "Created URL is " . $url . "<br/>";

        //create user
        $ownCloudPOSTArray = array('userid' => $username, 'password' => $passwordUser);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ownCloudPOSTArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        echo "Response from curl :" . $response;
        echo "<br/>Created a new user in owncloud<br/>";

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

        /*    //add mail to user
            //PUT http://admin:secret@example.com/ocs/v1.php/cloud/users/Frank -d key="email" -d value="franksnewemail@example.org"
            $urlMail = 'http://' . $ownAdminname . ':' . $ownAdminpassword . '@10.3.51.24/owncloud/ocs/v1.php/cloud/users/'.$username;
            $mailArray = array('key' => "email",'value' => $email);
            $ch = curl_init($urlMail);
            curl_setopt($ch, CURLOPT_PUT, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $mailArray);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            echo "Response mail from curl :" . $response;
            echo "<br/>Added mail to the new user in owncloud<br/>";*/
    }
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
?>
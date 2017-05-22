<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

include_once 'Users.php';
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('PlanningQueue', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";


$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
    var_dump($msg->body);
    /*
  $json = array(
      'Type' => 'Request',
      'Method' => 'PUT',
      'Sender' => 'cloud',
      'Receiver' => 'cloud',
      'ObjectType' => 'user'
      'Credentials' => array (
      'login' => 'admin',
      'password' => 'lalolu4'
      ),
      'Body' => array (
          'id' => '6d9fa04f-2148-c1c4-fb78-590f3af9e935',
          'username' => 'Jan',
          'fullname' => 'Jantje',
          'password' => '123',
          'email' => 'gast@gmail.test',
          'group' => 'gastspreker',
          'groupadmin' => 'no',
          'quota' => '5',
  );*/


    $json = json_decode($msg->body, true);

    $credentials = $json['Credentials'];
    $login = $credentials['login'];
    $password = $credentials['password'];
    $method = $json['Method'];
    $sender = $json['Sender'];
    $receiver = $json['Receiver'];
    $objectType = $json['ObjectType'];
    $body = $json['Body'];
    echo $objectType;



        foreach ($body as $name => $value) {
            switch ($name) {
                case 'UUID':
                    $id = $value;
                case 'fnaam':
                    $username = $value;
                case 'email':
                    $email = $value;
            }

            if (!isset($id)) {
                echo "geen id";
            }
            if (!isset($username)) {
                $username = null;
            }
            if (!isset($email)) {
                $email = null;
            }

            switch ($method) {
                case 'PUT':
                    switch ($objectType) {
                        case 'SPK':
                            $group = "gastspreker";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        case 'COL':
                            $group = "collaborator";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        case 'user':
                            $group = "gastspreker";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        default:
                            echo "deze user maken we niet aan";
                            break;
                    }
                    break;
                case 'GET':
                    switch ($objectType) {
                        case 'user':
                            //getUser($login, $password, $id, $username, $fullname, $email, $password2, $group, $groupadmin, $quota);
                            break;
                        case 'gastspreker':
                            break;
                        case 'admin':
                            break;
                        default:
                            echo "deze user maken we niet aan";
                            break;
                    }
                    break;
                case 'POST':
                    switch ($objectType) {
                        case 'SPK':
                            $group = "gastspreker";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        case 'COL':
                            $group = "collaborator";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        case 'user':
                            $group = "gastspreker";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        default:
                            echo "deze user maken we niet aan";
                            break;
                    }
                    break;
                default:
                    break;
            }

        }

};


$channel->basic_consume('PlanningQueue', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
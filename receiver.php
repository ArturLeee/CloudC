<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('cloud', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
   echo var_dump($msg);
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
    $json = $msg;

    $json = json_decode($input->body,true);
    
    $credentials = $json['Credentials'];
    $login = $credentials['login'];
    $password = $credentials['password'];
    
    $body = $json['Body'];
    foreach($body as $name => $value) {
        switch($name){
            case 'id':
                    $id = $value;
                case 'username':
                    $username = $value;
                case 'fullname':
                    $fullname = $value;
                case 'password':
                    $password2 = $value;
                case 'email':
                    $email = $value;
                case 'group':
                    $group = $value;
                case 'groupadmin':
                    $groupadmin = $value;
                case 'quota':
                    $quota = $value;
        }
    }
    
        if (!isset($id)) {
            //ERROR ANSWER
        }
        if (!isset($username)) {
            $username = null;
        }
        if (!isset($fullname)) {
            $fullname = null;
        }
        if (!isset($email)) {
            $email = null;
        }
        if (!isset($password2)) {
            $password2 = null;
        }
        if (!isset($group)) {
            $group = null;
        }
        if (!isset($groupadmin)) {
            $groupadmin = null;
        }
        if (!isset($quota)) {
            $quota = null;
        }
    
    switch ($method) {
        case 'PUT':
            switch ($ObjectType) {
                case 'user':
                      echo " [x] Received ";
                      header("location:createUser.php");
                    break;
                case 'gastspreker':
                    break;
                case 'admin':
                    break;
                default:
                    break;
            }
            break;
        case 'GET':
            switch ($ObjectType) {
                case 'user':
                    getUser($login, $password, $id, $username, $fullname, $email, $password2, $group, $groupadmin, $quota);
                    break;
                case 'gastspreker':
                    break;
                case 'admin':
                    break;
                default:
                    break;
            }
            break;
        case 'POST':
            switch ($ObjectType) {
                case 'user':
                    addUser($login, $password, $id, $username, $fullname, $email, $password2, $group, $groupadmin, $quota);
                    break;
                case 'gastspreker':
                    break;
                case 'admin':
                    break;
                default:
                    break;
            }
            break;     
        default:
            break;
    }
  
};
$channel->basic_consume('hello', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
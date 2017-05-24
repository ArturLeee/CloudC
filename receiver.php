<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

include_once 'Users.php';
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
    var_dump($msg->body);

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
                case 'uuid':
                    $id = $value;
                case 'name':
                    $username = $value;
                case 'email':
                    $email = $value;
            }
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
                        case 'SPO':
                            $group = "sponsor";
                            Users::createUsers($login, $password, $id, $username, $email, $group);
                            echo " [x] Received ";
                            break;
                        default:
                            break;
                    }
                    break;
                case 'GET':
                    switch ($objectType) {
                        case 'user':
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
                    switch ($objectType) {
                        case 'SPK':
                            break;
                        case 'COL':

                            break;
                        case 'user':

                            break;
                        default:
                    }
                    break;
                default:
                    break;
            }
};


$channel->basic_consume('PlanningQueue', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
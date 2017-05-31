<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

include_once 'Users.php';
include_once 'SSC.php';
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
    var_dump($msg->body);

    $json = json_decode($msg->body, true);

   //$credentials = $json['Credentials'];
   // $login = $credentials['login'];
   // $password = $credentials['password'];
    $Type = $json['Type'];
    if($Type != "HBT") {
        $method = $json['Method'];
        $objectType = $json['ObjectType'];
        // $sender = $json['Sender'];
        // $receiver = $json['Receiver'];
        $body = $json['Body'];
        echo $objectType;

        foreach ($body as $name => $value) {
            switch ($name) {
                case 'uuid':
                    $id = $value;
                    break;
                case 'name':
                    $username = $value;
                    break;
                case 'surname':
                    $surname = $value;
                    break;
                case 'email':
                    $email = $value;
                    break;
                case 'company_name':
                    $company = $value;
                    break;
            }
        }

        if (!isset($id)) {
            echo "geen id";
        }
        if (!isset($username)) {
            $username = null;
        }
        if (!isset($surname)) {
            $surname = null;
        }
        if (!isset($email)) {
            $email = null;
        }
        if (!isset($company)) {
            $company = null;
        }

        $username = $username . " " . $surname;
        switch ($method) {
            case 'POST':
                switch ($objectType) {
                    case 'SPK':
                        $group = "gastspreker";
                        Users::createUsers($id, $username, $email, $group);
                        echo " [x] Received ";
                        break;
                    case 'COL':
                        $group = "collaborator";
                        Users::createUsers($id, $username, $email, $group);
                        echo " [x] Received ";
                        break;
                    case 'SPO':
                        $group = "sponsor";
                        Users::createUsers($id, $company, $email, $group);
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
            case 'PUT':
                switch ($objectType) {
                    case 'SPK':
                        $group = "gastspreker";
                        Users::UpdateUsers($id, $username, $email, $group);
                        echo " [x] Received ";
                        break;
                    case 'COL':
                        $group = "collaborator";
                        Users::UpdateUsers($id, $username, $email, $group);
                        echo " [x] Received ";
                        break;
                    case 'SPO':
                        $group = "sponsor";
                        Users::UpdateUsers($id, $company, $email, $group);
                        echo " [x] Received ";
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }

    }else{
        $body = $json['Body'];

        foreach ($body as $name => $value) {
            switch ($name) {
                case 'uuid':
                    $id = $value;
                    break;
                case 'version':
                    $version = $value;
                    break;
                case 'var':
                    $var = $value;
                    break;
                case 'timestampsnd':
                    $timestampsnd = $value;
                    break;
            }
        }

        SSC::send($id, $timestampsnd, $var, $version);


    }

};

$channel->basic_consume('PlanningQueue', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

include_once 'Calendar.php';
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
          'description' => 'testdesc',
          'summary' => 'testsum',
          'start' => '123',
          'end' => 'gast@gmail.test',
          'loc' => 'gastspreker',
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
                case 'uuid':
                    $id = $value;
                case 'version':
                    $version = $value;
                case 'topic':
                    $topic = $value;
                case 'topic_description':
                    $topic_descripton = $value;
                case 'spk_uuid':
                    $spk_uuid = $value;
                case 'start':
                    $start = $value;
                case 'end':
                    $end = $value;
                case 'location':
                    $location = $value;
            }

            if (!isset($id)) {
                echo "geen id";
            }
            if (!isset($version)) {
                $version = null;
            }
            if (!isset($topic)) {
                $topic = null;
            }
            if (!isset($topic_descripton)) {
                $topic_descripton = null;
            }
            if (!isset($spk_uuid)) {
                echo "geen id";
            }
            if (!isset($start)) {
                $start = null;
            }
            if (!isset($end)) {
                $end = null;
            }
            if (!isset($location)) {
                $location = null;
            }
          //  $test = date("Y-m-d H:m:s");

            switch ($method) {
                case 'PUT':
                    switch ($objectType) {
                        case 'EVT':
                            $group = $objectType;
                            Calendar::createEvent($topic_descripton, $topic, $start , $end, $location, $group);
                            echo " [x] Received ";
                            break;
                        default:
                            echo "deze user maken we niet aan";
                            break;
                    }
                    break;
                case 'GET':
                    switch ($objectType) {

                    }
                    break;
                case 'POST':
                    switch ($objectType) {

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
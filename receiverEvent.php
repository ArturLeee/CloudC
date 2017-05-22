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
                case 'UUID':
                    $id = $value;
                case 'description':
                    $description = $value;
                case 'summary':
                    $summary = $value;
                case 'start':
                    $start = $value;
                case 'end':
                    $end = $value;
                case 'loc':
                    $loc = $value;
            }

            if (!isset($id)) {
                echo "geen id";
            }
            if (!isset($description)) {
                $description = null;
            }
            if (!isset($summary)) {
                $summary = null;
            }
            if (!isset($start)) {
                $start = null;
            }
            if (!isset($end)) {
                $end = null;
            }
            if (!isset($loc)) {
                $loc = null;
            }
          //  $test = date("Y-m-d H:m:s");

            switch ($method) {
                case 'PUT':
                    switch ($objectType) {
                        case 'EVT':
                            Calendar::createEvent($description, $summary, $start , $end, $loc  );
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
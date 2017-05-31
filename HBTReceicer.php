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
    //var_dump($msg->body);

    $json = json_decode($msg->body, true);

    $Type = $json['Type'];
    if($Type=='HBT') {

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
        echo "vardump";
       // var_dump($json);

        SSC::send($id, $timestampsnd, $var, $version);

    }else{
        echo "geen HBT";
    }
};

$channel->basic_consume('PlanningQueue', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
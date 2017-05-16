
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$json = array(
    'Type' => 'Request',
    'Method' => 'PUT',
    'Sender' => 'cloud',
    'Receiver' => 'cloud',
    'ObjectType' => 'user',
    'Credentials' => array (    
    'login' => 'admin',
    'password' => 'lalolu4'
    ),
    'Body' => array (
        'id' => '6d9fa04f-2148-c1c4-fb78-590f3af9e935',
        'username' => 'Jefke',
        'fullname' => 'tata',
        'password' => '123',
        'email' => 'gast@gmail.test',
        'group' => 'gastspreker',
        'groupadmin' => 'no',
        'quota' => '5')
);
 $input = json_encode($json);
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('cloud', false, false, false, false);
$msg = new AMQPMessage($input);
$channel->basic_publish($msg, '', 'cloud');
echo " [x] Sent \n";
$channel->close();
$connection->close();

?>
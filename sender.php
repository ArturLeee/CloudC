
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
// COL = medewerker SPK=speaker
$json = array(
    'Type' => 'Request',
    'Method' => 'PUT',
    'Sender' => 'CLP',
    'Receiver' => 'CLP',
    'ObjectType' => 'SPK',
    'Credentials' => array (    
    'login' => 'admin',
    'password' => 'Student1'
    ),
    'Body' => array (
        'UUID' => '6d9fa04f-2148-c1c4-fb78-590f3af9e935',
        'fnaam' => 'olee',
        'email' => 'gast@gmail.test',
       )
);
$input = json_encode($json);
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('PlanningQueue', false, false, false, false);
$msg = new AMQPMessage($input);
$channel->basic_publish($msg, '', 'PlanningQueue');
echo " [x] Sent \n";
$channel->close();
$connection->close();

?>
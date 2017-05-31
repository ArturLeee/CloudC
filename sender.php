
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Deze sender wordt enkel gebruikt om te testen

//update request
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
        'uuid' => 'b887854e-CRM-COL-b6be-5321-9da8-a05b2e1fc658',
        'name' => 'Pieterupdate',
        'email' => 'eepieter@gmail.com',
       )
);



//JJJJDDMMTHHMMSSZ


/*
$end = "20170522T220000Z";
//EVENT
$json = array(
    'Type' => 'Request',
    'Method' => 'PUT',
    'Sender' => 'CLP',
    'Receiver' => 'CLP',//FRE
    'ObjectType' => 'EVT',
    'Credentials' => array (
        'login' => 'admin',
        'password' => 'Student1'
    ),
    'Body' => array (
        'uuid' => '6d9fa04f-2148-c1c4-fb78-590f3af9e935',
        'version' => 5,
        'topic' => 'testdesc',
        'topic_description' => 'testsum',
        'start' => $start,
        'end' => $end,
        'location' => 'testloc',
        'spk_uuid' => 'null',
    )
);
//SHIFT
/*
$json = array(
    'Type' => 'Request',
    'Method' => 'PUT',
    'Sender' => 'CLP',
    'Receiver' => 'FRE',
    'ObjectType' => 'SHT',
    'Credentials' => array (
        'login' => 'admin',
        'password' => 'Student1'
    ),

    'Body' => array (

        'uuid' => '6d9fa04f-2148-c1c4-fb78-590f3af9e935',
        'version' => '',
        'col_uuid' => '6d9fa04f-2148-c1c4-fb78-590f3af9e935',
        'start' => $start,
        'end' => $end,
        'location' => 'testloc',
    )
);*/

$input = json_encode($json);
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('PlanningQueue', false, true, false, false);
$msg = new AMQPMessage($input);
$channel->basic_publish($msg, '', 'PlanningQueue');
echo " [x] Sent \n";
$channel->close();
$connection->close();
?>
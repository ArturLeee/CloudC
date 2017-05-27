<?php
/**
 * Created by PhpStorm.
 * User: geldh
 * Date: 24/05/2017
 * Time: 13:38
 */
$naam = $_POST['naam'];
$beschrijving = $_POST['beschrijving'];
$dag = $_POST['dag'];
$start =$_POST['start'];
$einde = $_POST['einde'];
$locatie = $_POST['locatie'];
$objecttype = "EVT";

$startFRO = strtotime($dag . " " . $start);
$endFRO = strtotime($dag . " " . $einde);
//$startOwncloud = date();

if( $_POST['gastspreker'] == "geen"){
    $spk_uuid= "null";
    $uniqStr = str_replace(' ', '', $beschrijving);
}else{
    $spk_uuid = $_POST['gastspreker'];
    $uniqStr = $spk_uuid;
}

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
// COL = medewerker SPK=speaker
$json = array(
    'Type' => 'Request',
    'Method' => 'POST',
    'Sender' => 'CLP',
    'Receiver' => 'FRE',
    'ObjectType' => 'EVT',
    'Credentials' => array (
        'login' => 'admin',
        'password' => 'Student1'
    ),
    'Body' => array (
        'uuid' => getUuid($uniqStr, $objecttype),
        'version' => 1,
        'topic' => $naam,
        'topic_description' => $beschrijving,
        'start' => $startFRO,
        'end' => $endFRO,
        'location' => $locatie,
        'spk_uuid' => $spk_uuid,
    )
);


$input = json_encode($json);
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('FrontendQueue', false, true, false, false);
$msg = new AMQPMessage($input);
$channel->basic_publish($msg, '', 'FrontendQueue');
echo " [x] Sent \n";
$channel->close();
$connection->close();

header("location:Main.php");


function getUuid($uniqString, $kind){

    $url = '10.3.51.41/api/v1/uuid';

    $params = array(
        'login' => "cloud",
        'password' => md5("R7YWjP"),
        'uniq' => $uniqString,
        'kind' => $kind
    );

    $json =json_encode($params);
    print_r($json);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json)));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
    curl_setopt($ch,CURLOPT_POSTFIELDS,$json);

    $response = curl_exec($ch);
    print_r($response);

    return $response;
}

?>

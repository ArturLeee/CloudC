<?php
/**
 * Created by PhpStorm.
 * User: geldh
 * Date: 26/05/2017
 * Time: 14:57
 */
$dag = $_POST['dag'];
$start =$_POST['start'];
$einde = $_POST['einde'];
$locatie = $_POST['locatie'];
$objecttype = "SHT";
$col_uuid = $_POST['collaborator'];


$startFRO = strtotime($dag . " " . $start);
$endFRO = strtotime($dag . " " . $einde);
//$startOwncloud = date();

require_once __DIR__ . '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
// COL = medewerker SPK=speaker
$json = array(
    'Type' => 'Request',
    'Method' => 'POST',
    'Sender' => 'CLP',
    'Receiver' => 'FRE',
    'ObjectType' => 'SHTT',
    'Credentials' => array (
        'login' => 'admin',
        'password' => 'Student1'
    ),
    'Body' => array (
        'uuid' => getUuid($col_uuid, $objecttype),
        'version' => 1,
        'col_uuid' => $col_uuid,
        'start' => $startFRO,
        'end' => $endFRO,
        'location' => $locatie,
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
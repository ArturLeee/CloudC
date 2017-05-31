<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Created by PhpStorm.
 * User: geldh
 * Date: 24/05/2017
 * Time: 13:38
 */

require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
include_once "Calendar.php";

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

    if ($_POST['gastspreker'] == "geen") {
        $spk_uuid = "null";
        $uniqStr = str_replace(' ', '', $beschrijving);
    } else {
        $spk_uuid = $_POST['gastspreker'];
        $uniqStr = $spk_uuid;
    }

    $response = getUuid($uniqStr, $objecttype);
    $json = json_decode($response, true);

echo "var dump: ";
var_dump($json);

    $statusCode = $json["StatusCode"];
    $uuid = $json['StatusMessage']["UUID"];
    $version = $json['StatusMessage']["Version"];

    Calendar::createEvent($uuid, $naam ,$beschrijving,$start,$einde,$locatie);

    echo "statuscode";
    echo $statusCode;
    echo "uuid";
    echo $uuid;
    echo "version";
    echo  $version;

    if ($statusCode != 200) {
        echo "Fout, statuscode: " . $statusCode;
        ?>  <a href="formEvent.php">Terug naar form</> <br><?php
    }else {


        echo "statuscode: 200";
        $json = array(
            'Type' => 'Request',
            'Method' => 'POST',
            'Sender' => 'CLP',
            'Receiver' => 'FRE',
            'ObjectType' => 'EVT',
            'Credentials' => array(
                'login' => 'admin',
                'password' => md5('Student1')
            ),
            'Body' => array(
                'uuid' => $uuid,
                'version' => $version,
                'topic' => $naam,
                'topic_description' => $beschrijving,
                'start' => $startFRO,
                'end' => $endFRO,
                'location' => $locatie,
                'spk_uuid' => $spk_uuid,
            )
        );

        echo "json gemaakt, klaar om te senden";
        var_dump($json);

         $input = json_encode($json);
         echo "test1";
         $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
         echo "test2";
         $channel = $connection->channel();
         echo "test3";
         $channel->queue_declare('FrontendQueue', false, true, false, false);
        $channel->queue_declare('MonitoringLogQueue', false, true, false, false);
        echo "test4";
         $msg = new AMQPMessage($input);
         echo "test5";
         $channel->basic_publish($msg, '', 'FrontendQueue');
        $channel->basic_publish($msg, '', 'MonitoringLogQueue');
        echo " [x] Sent \n";
         $channel->close();
         $connection->close();
    }

?>

<a href="Main.php" class="btn btn-default">Terug naar main</a>


<?php
        function getUuid($uniqString, $kind)
        {
echo "start func";
            $url = '10.3.51.41/api/v1/uuid';

            $params = array(
                'login' => "cloud",
                'password' => md5("R7YWjP"),
                'uniq' => $uniqString,
                'kind' => $kind
            );

            $json = json_encode($params);
            echo "Meegegeven json:";
            print_r($json);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

            $response = curl_exec($ch);

            echo "Response json:";
            echo $response;
            // print_r($response);

            // var_dump($response)

            // var_dump($response);

            //$json = json_decode($response);
//echo "var dump: ";
            // var_dump($json);

            // $statusCode = $json['StatusCode'];

            return $response;
        }

?>
</body>
</html>
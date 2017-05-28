<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: geldh
 * Date: 24/05/2017
 * Time: 13:38
 */
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$valid = true;

$allValues = [
    "naam",
    "beschrijving",
    "dag",
    "start",
    "einde",
    "locatie"
];

$errors = [
    "naam" => "",
    "beschrijving" => "",
    "dag" => "",
    "start" => "",
    "einde" => "",
    "locatie" => ""
];

 $values = [
    "naam" => "",
    "beschrijving" => "",
    "dag" => "",
    "start" => "",
    "einde" => "",
    "locatie" => ""
];
echo "test1";
if ($_SERVER["REQUEST_METHOD"]  !== "POST") {
    echo "test2";

    include 'formEvent.php';
    echo "test3";

}else{
    echo "test4";

    checkRequired($allValues);
    echo "test9";


    foreach($errors as $error) {
        echo "test10";
        if(!empty($error)) {
            echo "test11";
            $valid = false;
            break;
        }
    }

    if(!$valid) {
        echo "test12";
        include 'formEvent.php';
    } else {
        echo "test130";
        // anders wordt het resultaat getoond
        SendEvent();
    }

    function checkRequired($fields) {
        echo "test5";
        foreach($fields as $name) {
            echo "test6";
            if(required($name)) {
                global $values;
                $values[$name] = $_POST[$name];
                echo "test7";
            } else {
                global $errors;
                echo "test8";
                $errors[$name] = "Dit veld is verplicht in te vullen";
            }
        }
    }

}
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["naam"])) {
        $nameErr = "Naam is niet ingevuld";
        $check = false;
    } else {
        $naam = $_POST['naam'];
    }
    if (empty($_POST["beschrijving"])) {
        $beschrijvingErr = "Beschrijving is niet ingevuld";
        $check = false;
    } else {
        $beschrijving = $_POST['beschrijving'];
    }
    if (empty($_POST["dag"])) {
        $dagErr = "Dag is niet ingevuld";
        $check = false;
    } else {
        $dag = $_POST['dag'];
    }
    if (empty($_POST["start"])) {
        $startErr = "Start is niet ingevuld";
        $check = false;
    } else {
        $start =$_POST['start'];
    }
    if (empty($_POST["einde"])) {
        $eindeErr = "Einde is niet ingevuld";
        $check = false;
    } else {
        $einde = $_POST['einde'];
    }
    if (empty($_POST["locatie"])) {
        $locatieErr = "Locatie is niet ingevuld";
        $check = false;
    } else {
        $locatie = $_POST['locatie'];
    }
}
*/

function SendEvent()
{
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

    require_once __DIR__ . '/vendor/autoload.php';


// COL = medewerker SPK=speaker
    $json = array(
        'Type' => 'Request',
        'Method' => 'POST',
        'Sender' => 'CLP',
        'Receiver' => 'FRE',
        'ObjectType' => 'EVT',
        'Credentials' => array(
            'login' => 'admin',
            'password' => 'Student1'
        ),
        'Body' => array(
           // 'uuid' => getUuid($uniqStr, $objecttype),
            'version' => 1,
            'topic' => $naam,
            'topic_description' => $beschrijving,
            'start' => $startFRO,
            'end' => $endFRO,
            'location' => $locatie,
            'spk_uuid' => $spk_uuid,
        )
    );

/*
    $input = json_encode($json);
    $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
    $channel = $connection->channel();
    $channel->queue_declare('FrontendQueue', false, true, false, false);
    $msg = new AMQPMessage($input);
    $channel->basic_publish($msg, '', 'FrontendQueue');
    echo " [x] Sent \n";
    $channel->close();
    $connection->close();
*/
    header("location:Main.php");


    function getUuid($uniqString, $kind)
    {

        $url = '10.3.51.41/api/v1/uuid';

        $params = array(
            'login' => "cloud",
            'password' => md5("R7YWjP"),
            'uniq' => $uniqString,
            'kind' => $kind
        );

        $json = json_encode($params);
        print_r($json);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $response = curl_exec($ch);
        print_r($response);

        return $response;
    }

}
?>
</body>
</html>
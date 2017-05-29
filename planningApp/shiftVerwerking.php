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

require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$response = getUuid($col_uuid, $objecttype);
$json = json_decode($response, true);


echo "var dump: ";
var_dump($json);

$statusCode = $json["StatusCode"];
$uuid = $json['StatusMessage']["UUID"];
$version = $json['StatusMessage']["Version"];


if ($statusCode != 200) {
    echo "Fout, statuscode: " . $statusCode;
    ?>  <a href="formEvent.php">Terug naar form</> <br><?php
}else {

// COL = medewerker SPK=speaker
$json = array(
    'Type' => 'Request',
    'Method' => 'POST',
    'Sender' => 'CLP',
    'Receiver' => 'FRE',
    'ObjectType' => 'SHT',
    'Credentials' => array (
        'login' => 'admin',
        'password' => 'Student1'
    ),
    'Body' => array (
        'uuid' => $uuid,
        'version' => $version,
        'col_uuid' => $col_uuid,
        'start' => $startFRO,
        'end' => $endFRO,
        'location' => $locatie,
    )
);

echo "json gemaakt, klaar om te senden";

$input = json_encode($json);
echo "test1";
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
echo "test2";
$channel = $connection->channel();
echo "test3";
$channel->queue_declare('FrontendQueue', false, true, false, false);
//$channel->queue_declare('MonitoringLogQueue', false, true, false, false);
echo "test4";
$msg = new AMQPMessage($input);
echo "test5";
$channel->basic_publish($msg, '', 'FrontendQueue');
//$channel->basic_publish($msg, '', 'MonitoringLogQueue');
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
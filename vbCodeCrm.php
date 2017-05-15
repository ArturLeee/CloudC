<?php
include 'Select&Creates&Updates.php';
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('10.3.51.32', 5672, 'crm', 'Student1');
$channel = $connection->channel();
$channel->queue_declare('CRM', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function ($msg) {
    //echo " [x] Received ", $msg->body, "\n";
    $json = json_decode($msg->body, true);
    $type = $json['Type'];
    $sender = $json['Sender'];
    $Receiver = $json['Receiver'];
    $method = $json['Method'];
    $ObjectType = $json['ObjectType'];
    $login = $credentials['login'];
    $password = $credentials['password'];
    $body = $json['Body'];
            foreach ($body as $name => $value) {
            switch ($name) {
                case 'id':
                    $id = $value;
                case 'voornaam':
                    $voornaam = $value;
                case 'achternaam':
                    $achternaam = $value;
                case 'email':
                    $email = $value;
                case 'telefoonnummer':
                    $telefoonnummer = $value;
                case 'straat':
                    $straat = $value;
                case 'gemeente':
                    $gemeente = $value;
                case 'postcode':
                    $postcode = $value;
                case 'provincie':
                    $provincie = $value;
                case 'land':
                    $land = $value;
                case 'bedrijfsnaam':
                    $bedrijfsnaam = $value;
                case 'website':
                    $website = '$value';
                case 'image':
                    $image = $value;
            }
        }
        if (!isset($id)) {
            //ERROR ANSWER
        }
        if (!isset($voornaam)) {
            $voornaam = null;
        }
        if (!isset($achternaam)) {
            $telefoonnummer = null;
        }
        if (!isset($email)) {
            $email = null;
        }
        if (!isset($telefoonnummer)) {
            $telefoonnummer = null;
        }
        if (!isset($straat)) {
            $straat = null;
        }
        if (!isset($gemeente)) {
            $gemeente = null;
        }
        if (!isset($postcode)) {
            $postcode = null;
        }
        if (!isset($provincie)) {
            $provincie = null;
        }
        if (!isset($land)) {
            $land = null;
        }
        if (!isset($bedrijfsnaam)) {
            $bedrijfsnaam = null;
        }
        if (!isset($website)) {
            $website = null;
        }
        if (!isset($image)) {
            $image = null;
        }
    switch ($method) {
        case 'PUT':
            switch ($ObjectType) {
                case 'Bezoeker':
                    updateBezoeker($login, $password, $id, $voornaam, $achternaam, $email, $telefoonnummer, $straat, $gemeente, $postcode, $provincie, $land);
                    break;
                case 'Sponsor':
                    updateSponsor($login, $password, $id, $bedrijfsnaam, $telefoonnummer, $email, $straat, $gemeente , $provincie , $postcode, $land, $website, $image);
                    break;
                case 'Spreker':
                    updateSpreker($login, $password, $id, $voornaam, $achternaam, $email, $telefoonnummer, $straat, $gemeente, $postcode, $provincie, $land);
                    break;
                case 'Medewerker':
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
            break;
        case 'GET':
            switch ($ObjectType) {
                case 'Bezoeker':
                    getBezoekers($login, $password, $id, $voornaam, $achternaam, $email, $telefoonnummer, $straat, $gemeente, $postcode, $provincie, $land);
                    break;
                case 'Sponsor':
                    getSponsors($login, $password, $id, $bedrijfsnaam, $telefoonnummer, $email, $straat, $gemeente , $provincie , $postcode, $land, $website, $image);
                    break;
                case 'Spreker':
                    getSprekers($login, $password, $id, $voornaam, $achternaam, $email, $telefoonnummer, $straat, $gemeente, $postcode, $provincie, $land);
                    break;
                case 'Medewerker':
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
            break;
        case 'POST':
            switch ($ObjectType) {
                case 'Bezoeker':
                    addBezoeker($login, $password, $id, $voornaam, $achternaam, $email, $telefoonnummer, $straat, $gemeente, $postcode, $provincie, $land);
                    break;
                case 'Sponsor':
                    addSponsor($login, $password, $id, $bedrijfsnaam, $telefoonnummer, $email, $straat, $gemeente , $provincie , $postcode, $land, $website, $image);
                    break;
                case 'Spreker':
                    addSpreker($login, $password, $id, $voornaam, $achternaam, $email, $telefoonnummer, $straat, $gemeente, $postcode, $provincie, $land);
                    break;
                case 'Medewerker':
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
            break;     
        default:
            # code...
            break;
    }
$channel->basic_consume('CRM', '', false, true, false, false, $callback);
while (count($channel->callbacks)) {
    $channel->wait();
}
?>
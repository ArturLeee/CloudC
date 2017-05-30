<?php

/**
 * Created by PhpStorm.
 * User: geldh
 * Date: 30/05/2017
 * Time: 10:54
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class SSC
{
    public static function send($UUID, $timestampsnd, $var, $version)
    {
        $timestampres = strtotime("now");
        echo "time now: ".$timestampres;
        $var = $var * $var;

        $jsonHbt= array(
            'Type' => 'HBT',
            'Body' => array (
                'uuid' => $UUID,
                'timestampsnd' => $timestampsnd,
                'timestampres' => intval($timestampres),
                'var' => $var,
                'version' => $version
            ));
        var_dump($jsonHbt);
        echo "json gemaakt, klaar om te senden";

        $input = json_encode($jsonHbt);
        echo "test1";
        $connection = new AMQPStreamConnection('10.3.51.32', 5672, 'cloud', 'Student1');
        echo "test2";
        $channel = $connection->channel();
        echo "test3";
        $channel->queue_declare('HeartBeatQueue', false, true, false, false);
        echo "test4";
        $msg = new AMQPMessage($input);
        echo "test5";
        $channel->basic_publish($msg, '', 'HeartBeatQueue');
        echo " [x] Sent \n";
        $channel->close();
        $connection->close();
    }
}
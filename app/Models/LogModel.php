<?php

namespace App\Models;

use CodeIgniter\Model;

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;

class LogModel extends Model
{

    public function __construct() {
        $url = getenv('influxhost');
        $token = getenv('influxToken');
        $bucket = getenv('influxBucket');
        $precision = WritePrecision::NS;
        $org = getenv('influxOrg');
        $debug = false;
        // $dns = sprintf("influxdb://%s:%s@%s:%s/%s", $username, $password, $host, $port, $dbName);

        $this->client = new Client([
            'url'   => $url,
            'token' => $token,
            'bucket'=> $bucket,
            'precision' => $precision,
            'org'   => $org,
            'debug' => $debug
        ]);
    }
    // public function getTotal($userId, $assetId)
    // {
    //     $data = $this->builder()
    //     ->from()
    // }

}

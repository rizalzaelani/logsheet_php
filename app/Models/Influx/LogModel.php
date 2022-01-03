<?php

namespace App\Models\Influx;

use CodeIgniter\Model;
use DateTime;
use InfluxDB\Client;
use influxDB\Database;
use InfluxDB\Point;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

class LogModel
{
    private Client $client;
    private Database $db;
    public function __construct()
    {
        // date_default_timezone_set('UTC');

        $host = getenv('influxHost');
        $port = getenv('influxPort');
        $username = getenv('influxUsername');
        $password = getenv('influxPassword');
        $dbName = getenv('influxDbname');
        $dns = sprintf("influxdb://%s:%s@%s:%s/%s", $username, $password, $host, $port, $dbName);

        $this->db = Client::fromDSN($dns, 5);
        $this->client = $this->db->getClient();
        // $this->database = $this->client->selectDB('iotbox');
    }

    public function querybuilder()
    {
        return $this->db->getQueryBuilder();
    }

    public function getLogAsset($activity, $activity2, $userId, $assetId, $dateFrom, $dateTo)
    {
        $database = $this->client->selectDB('db_logsheet');
        $where = [
            // "activity = '$activity'",
            "assetId = '$assetId'",
            "userId = '$userId'",
            "time >= '$dateFrom'",
            "time <= '$dateTo'",
            "activity = '$activity' OR activity = '$activity2'",
        ];

        $query = "SELECT * FROM logsheet_logactivity WHERE assetId = '$assetId' AND userId = '$userId' AND time >= '$dateFrom' AND time <= '$dateTo' AND (activity = '$activity' OR activity = '$activity2')";
        $result = $database->query($query);
        return $result->getPoints();
        // return $this->querybuilder()
        //     ->from('logsheet_logactivity')
        //     ->select('*')
        //     ->where($where)
        //     // ->limit(5)
        //     ->orderBy('time', 'desc')
        //     ->getResultSet()
        //     ->getPoints();
    }

    public function getAll($userId)
    {
        $where = [
            "userId = '$userId'"
        ];

        return $this->querybuilder()
            ->from('logsheet_logactivity')
            ->select('*')
            ->where($where)
            // ->limit(5)
            ->orderBy('time', 'desc')
            ->getResultSet()
            ->getPoints();
    }

    public function writeData($activity, $ip, $userId, $username, $assetId, $data)
    {
        $now = new DateTime();
        $date = $now->format("Y-m-d H:i:s");
        // create an array of points
        $points = array(
            new Point(
                'logsheet_logactivity', // name of the measurement
                null, // the measurement value
                [
                    'activity' => $activity,
                    'ip' => $ip,
                    'userId' => $userId,
                    'username' => $username,
                    'assetId' => $assetId,
                ], // optional tags
                [
                    'data' => $data
                ], // optional additional fields
                strtotime($date) // Time precision has to be set to seconds!
            )
        );

        // we are writing unix timestamps, which have a second precision
        $result = $this->db->writePoints($points, Database::PRECISION_SECONDS);
    }
}

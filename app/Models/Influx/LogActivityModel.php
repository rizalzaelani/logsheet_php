<?php

namespace App\Models\Influx;

use CodeIgniter\Model;
use DateTime;
use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Model\DeletePredicateRequest;
use InfluxDB2\Service\DeleteService;
use InfluxDB2\Point;

class LogActivityModel extends Model
{
    public function writeLog($data, $activity, $userId, $username, $assetId)
    {
        $token = env('influx_token');
        $org = env('influx_org');
        $bucket = env('influx_bucket');

        $client = new Client([
            "url" => "http://45.77.45.6:8086",
            "token" => $token,
        ]);

        // $point1 = Point::measurement("log_activity");

        // $point = Point::measurement('log_activity')
        //     ->addTag('location', 'Indonesia')
        //     ->addField('data', 23.43234543)
        //     ->time(microtime(true));

        $writeApi = $client->createWriteApi();
        $dataArray = [
            'name' => 'log_activity',
            'tags' => [
                'activity' => $activity,
                'ip' => '::1',
                'userId' => $userId,
                'username' => $username,
                'assetId'   => $assetId
            ],
            'fields' => ['data' => $data],
            // 'time' => microtime(true)
        ];
        $writeApi->write($dataArray, WritePrecision::S, $bucket, $org);
        return true;
    }

    public function readLog($start, $end, $activity)
    {
        $token = env('influx_token');
        $org = env('influx_org');
        $bucket = env('influx_bucket');

        $client = new Client([
            "url" => "http://45.77.45.6:8086",
            "token" => $token,
            "precision" => WritePrecision::S,
            "org" => $org,
            "debug" => false
        ]);

        $this->queryApi = $client->createQueryApi();

        $result = $this->queryApi->queryStream(
            'from(bucket:"logsheet_logactivity")
            |> range(start: ' . $start . ', stop: ' . $end . ')
            |> filter(fn: (r) => 
                r._measurement == "log_activity" and
                r.activity == "' . $activity . '"
                )
            |> yield()'
        );
        return $result;
    }

    public function deleteLog()
    {
        $token = env('influx_token');
        $org = env('influx_org');
        $bucket = env('influx_bucket');

        $client = new Client([
            "url" => "http://45.77.45.6:8086",
            "token" => $token,
            "precision" => WritePrecision::S,
            "org" => $org,
            "debug" => false
        ]);

        $service = $client->createService(DeleteService::class);
        $predicate = new DeletePredicateRequest();
        $predicate->setStart(DateTime::createFromFormat('Y', '2020'));
        $predicate->setStop(new DateTime());
        $predicate->setPredicate("_measurement=\"log_activity\"");

        $service->postDelete($predicate, null, $org, $bucket);

        $client->close();
    }
}

<?php

namespace App\Models\Influx;

use CodeIgniter\Model;
use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

class LogActivityModel extends Model
{
    public function writeLog()
    {
        $token = env('influx_token');
        $org = env('influx_org');
        $bucket = env('influx_bucket');

        $client = new Client([
            "url" => "http://45.77.45.6:8086",
            "token" => $token,
        ]);

        // $point = Point::measurement('mem')
        //     ->addTag('host', 'host1')
        //     ->addField('used_percent', 23.43234543)
        //     ->time(microtime(true));

        $dataArray = [
            'name' => 'log_activity',
            'tags' => ['activity' => 'View Dashboard', 'ip' => '::1', 'userId' => '04891731-35ec-47b9-b616-db2266f7fbf8', 'username' => 'user.test'],
            'fields' => ['data' => "{\"userId\":\"04891731-35ec-47b9-b616-db2266f7fbf8\",\"name\":\"user.test\",\"email\":\"user.test@gmail.com\",\"parameter\":\"{\"country\":
                \"Indonesia\",\"city\": \"Cilacap\",\"tagLocation\": \"\",\"noTelp\": \"088215766216\",\"company\":
                \"Nocola\",\"postalCode\": \"53223\",\"fullname\": \"user.test\",\"tag\":
                \"\"}\"}"],
            'time' => microtime(true)
        ];

        $writeApi = $client->createWriteApi();
        $writeApi->write($dataArray, WritePrecision::S, $bucket, $org);

        return true;
    }
}

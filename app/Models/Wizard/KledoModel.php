<?php

namespace App\Models\Wizard;

use CodeIgniter\Model;
use HTTP_Request2;
use HTTP_Request2_Exception;

class kledoModel extends Model
{
    // Product
    function getProduct($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('kledoURL') . 'finance/products?' . $data);
        $request->setMethod(HTTP_Request2::METHOD_GET);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MmQ2MzFiMC0wZDFjLTQzNWItOGZkYS0yYWI4YmE2YzVkM2EiLCJqdGkiOiI5M2UyZTk4OGRlYmNjMDYzZjdmNTZlZDdhNzdiMDI4MTQ5MTc4MmYzMWM0NWM4NDNjNjM3OGJlNGNiZDVlYzliOTA0NmE3OTkyMmE3MDk5ZiIsImlhdCI6MTYzNzY0MTMzMy4yNjAwOTUsIm5iZiI6MTYzNzY0MTMzMy4yNjAwOTksImV4cCI6MTY2OTE3NzMzMy4yNTc2NTksInN1YiI6IjE0NjUzIiwic2NvcGVzIjpbXX0.k_rQw-JSn4wOiyD39nwp91VefFNxpApLrOjX_wNYK--jUM6yeZuJCPh7bb4BOTgkekd9RgVL6Cd9XGJE8UfyG-OpB68TGPDDSvnrqV77ss270i7QTjXf2bvhXKgxXdbUZYMh_plzCS_Oqkhyj2un8ac0Ze6_EwIvbdz3uDK9Yh-Vt-Lk2BTsDp86hFlq1H9l-5OeFR1FJYGmNyTnugJUTWDkmfQ0gj-IPo6QsEYi0vh_nVNN36XwZwAmR60HZSZBSpwITHfZJCqqeL1X_Y7UqFyz2i1FXqE9Yv2Kh79wnD-8CVPG7IhvrOIDWF_fL8ewGvKmEo4PjyFBbYRgZESEEDouaCAUmMazlvHil06J-TZ3rR-qAuA0-MJhme7QsIxbnvmWUHf6CFVIv1b5-DsOmM0wPQutY7NxhO99DPVhfyaB7wM-5xxqWRw49702Y96vJH2CiiNzu_u2Apc4zWC4aSQLCy_Na18jgrNPi_EWh9CF4Q3CF4xDeCyo8A0UX_0vB7B6nlk8gloQrE2bF1vmDvSGKkzs-YOPfzZYXjgPbLLU_6njHJSNm9zqXHo8O9QJw7B9bg-oNR-SEpii4uuZX361q7UItl5D8PSbRcgMscmRlgeXY0fr_hkjOsxCxnQjwCdLPu3rJKg0bW6Z7dAZb-egi875BwlyP1x0ugQCr0s',
        ));
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => $response->getBody(),
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }

    // Contact
    public function getContact($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('kledoURL') . 'finance/contacts?' . $data);
        $request->setMethod(HTTP_Request2::METHOD_GET);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MmQ2MzFiMC0wZDFjLTQzNWItOGZkYS0yYWI4YmE2YzVkM2EiLCJqdGkiOiI5M2UyZTk4OGRlYmNjMDYzZjdmNTZlZDdhNzdiMDI4MTQ5MTc4MmYzMWM0NWM4NDNjNjM3OGJlNGNiZDVlYzliOTA0NmE3OTkyMmE3MDk5ZiIsImlhdCI6MTYzNzY0MTMzMy4yNjAwOTUsIm5iZiI6MTYzNzY0MTMzMy4yNjAwOTksImV4cCI6MTY2OTE3NzMzMy4yNTc2NTksInN1YiI6IjE0NjUzIiwic2NvcGVzIjpbXX0.k_rQw-JSn4wOiyD39nwp91VefFNxpApLrOjX_wNYK--jUM6yeZuJCPh7bb4BOTgkekd9RgVL6Cd9XGJE8UfyG-OpB68TGPDDSvnrqV77ss270i7QTjXf2bvhXKgxXdbUZYMh_plzCS_Oqkhyj2un8ac0Ze6_EwIvbdz3uDK9Yh-Vt-Lk2BTsDp86hFlq1H9l-5OeFR1FJYGmNyTnugJUTWDkmfQ0gj-IPo6QsEYi0vh_nVNN36XwZwAmR60HZSZBSpwITHfZJCqqeL1X_Y7UqFyz2i1FXqE9Yv2Kh79wnD-8CVPG7IhvrOIDWF_fL8ewGvKmEo4PjyFBbYRgZESEEDouaCAUmMazlvHil06J-TZ3rR-qAuA0-MJhme7QsIxbnvmWUHf6CFVIv1b5-DsOmM0wPQutY7NxhO99DPVhfyaB7wM-5xxqWRw49702Y96vJH2CiiNzu_u2Apc4zWC4aSQLCy_Na18jgrNPi_EWh9CF4Q3CF4xDeCyo8A0UX_0vB7B6nlk8gloQrE2bF1vmDvSGKkzs-YOPfzZYXjgPbLLU_6njHJSNm9zqXHo8O9QJw7B9bg-oNR-SEpii4uuZX361q7UItl5D8PSbRcgMscmRlgeXY0fr_hkjOsxCxnQjwCdLPu3rJKg0bW6Z7dAZb-egi875BwlyP1x0ugQCr0s',
        ));
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => $response->getBody(),
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }

    public function addContact($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('kledoURL') . 'finance/contacts');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MmQ2MzFiMC0wZDFjLTQzNWItOGZkYS0yYWI4YmE2YzVkM2EiLCJqdGkiOiI5M2UyZTk4OGRlYmNjMDYzZjdmNTZlZDdhNzdiMDI4MTQ5MTc4MmYzMWM0NWM4NDNjNjM3OGJlNGNiZDVlYzliOTA0NmE3OTkyMmE3MDk5ZiIsImlhdCI6MTYzNzY0MTMzMy4yNjAwOTUsIm5iZiI6MTYzNzY0MTMzMy4yNjAwOTksImV4cCI6MTY2OTE3NzMzMy4yNTc2NTksInN1YiI6IjE0NjUzIiwic2NvcGVzIjpbXX0.k_rQw-JSn4wOiyD39nwp91VefFNxpApLrOjX_wNYK--jUM6yeZuJCPh7bb4BOTgkekd9RgVL6Cd9XGJE8UfyG-OpB68TGPDDSvnrqV77ss270i7QTjXf2bvhXKgxXdbUZYMh_plzCS_Oqkhyj2un8ac0Ze6_EwIvbdz3uDK9Yh-Vt-Lk2BTsDp86hFlq1H9l-5OeFR1FJYGmNyTnugJUTWDkmfQ0gj-IPo6QsEYi0vh_nVNN36XwZwAmR60HZSZBSpwITHfZJCqqeL1X_Y7UqFyz2i1FXqE9Yv2Kh79wnD-8CVPG7IhvrOIDWF_fL8ewGvKmEo4PjyFBbYRgZESEEDouaCAUmMazlvHil06J-TZ3rR-qAuA0-MJhme7QsIxbnvmWUHf6CFVIv1b5-DsOmM0wPQutY7NxhO99DPVhfyaB7wM-5xxqWRw49702Y96vJH2CiiNzu_u2Apc4zWC4aSQLCy_Na18jgrNPi_EWh9CF4Q3CF4xDeCyo8A0UX_0vB7B6nlk8gloQrE2bF1vmDvSGKkzs-YOPfzZYXjgPbLLU_6njHJSNm9zqXHo8O9QJw7B9bg-oNR-SEpii4uuZX361q7UItl5D8PSbRcgMscmRlgeXY0fr_hkjOsxCxnQjwCdLPu3rJKg0bW6Z7dAZb-egi875BwlyP1x0ugQCr0s',
            'Content-Type' => 'application/json-data',
        ));
        $request->setBody($data);
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => $response->getBody()
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }

    // Get Invoice
    public function getInvoice($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('kledoURL') . 'finance/invoices?' . $data);
        $request->setMethod(HTTP_Request2::METHOD_GET);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MmQ2MzFiMC0wZDFjLTQzNWItOGZkYS0yYWI4YmE2YzVkM2EiLCJqdGkiOiI5M2UyZTk4OGRlYmNjMDYzZjdmNTZlZDdhNzdiMDI4MTQ5MTc4MmYzMWM0NWM4NDNjNjM3OGJlNGNiZDVlYzliOTA0NmE3OTkyMmE3MDk5ZiIsImlhdCI6MTYzNzY0MTMzMy4yNjAwOTUsIm5iZiI6MTYzNzY0MTMzMy4yNjAwOTksImV4cCI6MTY2OTE3NzMzMy4yNTc2NTksInN1YiI6IjE0NjUzIiwic2NvcGVzIjpbXX0.k_rQw-JSn4wOiyD39nwp91VefFNxpApLrOjX_wNYK--jUM6yeZuJCPh7bb4BOTgkekd9RgVL6Cd9XGJE8UfyG-OpB68TGPDDSvnrqV77ss270i7QTjXf2bvhXKgxXdbUZYMh_plzCS_Oqkhyj2un8ac0Ze6_EwIvbdz3uDK9Yh-Vt-Lk2BTsDp86hFlq1H9l-5OeFR1FJYGmNyTnugJUTWDkmfQ0gj-IPo6QsEYi0vh_nVNN36XwZwAmR60HZSZBSpwITHfZJCqqeL1X_Y7UqFyz2i1FXqE9Yv2Kh79wnD-8CVPG7IhvrOIDWF_fL8ewGvKmEo4PjyFBbYRgZESEEDouaCAUmMazlvHil06J-TZ3rR-qAuA0-MJhme7QsIxbnvmWUHf6CFVIv1b5-DsOmM0wPQutY7NxhO99DPVhfyaB7wM-5xxqWRw49702Y96vJH2CiiNzu_u2Apc4zWC4aSQLCy_Na18jgrNPi_EWh9CF4Q3CF4xDeCyo8A0UX_0vB7B6nlk8gloQrE2bF1vmDvSGKkzs-YOPfzZYXjgPbLLU_6njHJSNm9zqXHo8O9QJw7B9bg-oNR-SEpii4uuZX361q7UItl5D8PSbRcgMscmRlgeXY0fr_hkjOsxCxnQjwCdLPu3rJKg0bW6Z7dAZb-egi875BwlyP1x0ugQCr0s',
        ));
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => $response->getBody(),
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }

    // Invoice
    public function addInvoice($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('kledoURL') . 'finance/invoices');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MmQ2MzFiMC0wZDFjLTQzNWItOGZkYS0yYWI4YmE2YzVkM2EiLCJqdGkiOiI5M2UyZTk4OGRlYmNjMDYzZjdmNTZlZDdhNzdiMDI4MTQ5MTc4MmYzMWM0NWM4NDNjNjM3OGJlNGNiZDVlYzliOTA0NmE3OTkyMmE3MDk5ZiIsImlhdCI6MTYzNzY0MTMzMy4yNjAwOTUsIm5iZiI6MTYzNzY0MTMzMy4yNjAwOTksImV4cCI6MTY2OTE3NzMzMy4yNTc2NTksInN1YiI6IjE0NjUzIiwic2NvcGVzIjpbXX0.k_rQw-JSn4wOiyD39nwp91VefFNxpApLrOjX_wNYK--jUM6yeZuJCPh7bb4BOTgkekd9RgVL6Cd9XGJE8UfyG-OpB68TGPDDSvnrqV77ss270i7QTjXf2bvhXKgxXdbUZYMh_plzCS_Oqkhyj2un8ac0Ze6_EwIvbdz3uDK9Yh-Vt-Lk2BTsDp86hFlq1H9l-5OeFR1FJYGmNyTnugJUTWDkmfQ0gj-IPo6QsEYi0vh_nVNN36XwZwAmR60HZSZBSpwITHfZJCqqeL1X_Y7UqFyz2i1FXqE9Yv2Kh79wnD-8CVPG7IhvrOIDWF_fL8ewGvKmEo4PjyFBbYRgZESEEDouaCAUmMazlvHil06J-TZ3rR-qAuA0-MJhme7QsIxbnvmWUHf6CFVIv1b5-DsOmM0wPQutY7NxhO99DPVhfyaB7wM-5xxqWRw49702Y96vJH2CiiNzu_u2Apc4zWC4aSQLCy_Na18jgrNPi_EWh9CF4Q3CF4xDeCyo8A0UX_0vB7B6nlk8gloQrE2bF1vmDvSGKkzs-YOPfzZYXjgPbLLU_6njHJSNm9zqXHo8O9QJw7B9bg-oNR-SEpii4uuZX361q7UItl5D8PSbRcgMscmRlgeXY0fr_hkjOsxCxnQjwCdLPu3rJKg0bW6Z7dAZb-egi875BwlyP1x0ugQCr0s',
            'Content-Type' => 'application/json-data',
        ));
        $request->setBody($data);
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => $response->getBody()
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }

    public function generateInvoice($data)
    {
        $request = new HTTP_Request2();
        $request->setUrl(env('kledoURL') . 'invoice/generator/1');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MmQ2MzFiMC0wZDFjLTQzNWItOGZkYS0yYWI4YmE2YzVkM2EiLCJqdGkiOiI5M2UyZTk4OGRlYmNjMDYzZjdmNTZlZDdhNzdiMDI4MTQ5MTc4MmYzMWM0NWM4NDNjNjM3OGJlNGNiZDVlYzliOTA0NmE3OTkyMmE3MDk5ZiIsImlhdCI6MTYzNzY0MTMzMy4yNjAwOTUsIm5iZiI6MTYzNzY0MTMzMy4yNjAwOTksImV4cCI6MTY2OTE3NzMzMy4yNTc2NTksInN1YiI6IjE0NjUzIiwic2NvcGVzIjpbXX0.k_rQw-JSn4wOiyD39nwp91VefFNxpApLrOjX_wNYK--jUM6yeZuJCPh7bb4BOTgkekd9RgVL6Cd9XGJE8UfyG-OpB68TGPDDSvnrqV77ss270i7QTjXf2bvhXKgxXdbUZYMh_plzCS_Oqkhyj2un8ac0Ze6_EwIvbdz3uDK9Yh-Vt-Lk2BTsDp86hFlq1H9l-5OeFR1FJYGmNyTnugJUTWDkmfQ0gj-IPo6QsEYi0vh_nVNN36XwZwAmR60HZSZBSpwITHfZJCqqeL1X_Y7UqFyz2i1FXqE9Yv2Kh79wnD-8CVPG7IhvrOIDWF_fL8ewGvKmEo4PjyFBbYRgZESEEDouaCAUmMazlvHil06J-TZ3rR-qAuA0-MJhme7QsIxbnvmWUHf6CFVIv1b5-DsOmM0wPQutY7NxhO99DPVhfyaB7wM-5xxqWRw49702Y96vJH2CiiNzu_u2Apc4zWC4aSQLCy_Na18jgrNPi_EWh9CF4Q3CF4xDeCyo8A0UX_0vB7B6nlk8gloQrE2bF1vmDvSGKkzs-YOPfzZYXjgPbLLU_6njHJSNm9zqXHo8O9QJw7B9bg-oNR-SEpii4uuZX361q7UItl5D8PSbRcgMscmRlgeXY0fr_hkjOsxCxnQjwCdLPu3rJKg0bW6Z7dAZb-egi875BwlyP1x0ugQCr0s',
            'Content-Type' => 'application/json-data',
        ));
        $request->setBody($data);
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return array(
                    'error' => false,
                    'message' => 'Success Send Data',
                    'data' => $response->getBody(),
                );
            } else {
                return array(
                    'error' => true,
                    'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase(),
                    'data' => $response->getBody()
                );
            }
        } catch (HTTP_Request2_Exception $e) {
            return array(
                'error' => true,
                'message' => $e->getMessage(),
                'data' => '[]'
            );
        }
    }
}

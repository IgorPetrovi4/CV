<?php


namespace App\Service;


class ApiNewPost
{
    private $apiKey = '';


    public function getCity($city, $limit)
    {
        $data = [
            'apiKey' => $this->apiKey,
            'modelName' => 'Address',
            'calledMethod' => 'searchSettlements',
            'methodProperties' => [
                'CityName' => $city,
                'Limit' => $limit,

            ],
        ];
        return $this->apiConnect($data);
    }

    public function getAddress($city, $address)
    {
        $data = [
            'apiKey' => $this->apiKey,
            'modelName' => 'AddressGeneral',
            'calledMethod' => 'getWarehouses',

            'methodProperties' => [
                'CityName' => $city,
                'FindByString' => $address,

            ],
        ];

        return $this->apiConnect($data);

    }

    public function apiConnect($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.novaposhta.ua/v2.0/json/",
            CURLOPT_RETURNTRANSFER => True,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array("content-type: application/json",),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }

    }
}
<?php


namespace App\Service;


class GetRestApiService
{
    private $apiKey;
    private $url;

    public function __construct($apiKey, $url)
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function getSettings()
    {
        $data = [
            'field1' => 'string',
            'field2' => null,
            'field3' => 3
        ];
        return $this->apiConnect($data);
    }

    public function apiConnect($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url(),
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
        return $response;
    }

    public function apiKey()
    {
        return $this->apiKey;
    }

    public function url()
    {
        return $this->url;
    }

}



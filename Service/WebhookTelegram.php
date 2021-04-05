<?php


namespace App\Service;


class WebhookTelegram
{
    private string $url;
    private string $api;
    private string $resource;

    public function __construct($api, $url, $resource)
    {
        $this->url = $url;
        $this->api = $api;
        $this->resource = $resource;
    }

    public function setWebhook()
    {
        return file_get_contents($this->resource . $this->api . '/setWebhook?url=' . $this->url);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function deleteWebhook()
    {
        return file_get_contents($this->resource . $this->api . '/deleteWebhook');
    }

    public function WebhookInfo()
    {
        return file_get_contents($this->resource . $this->api . '/WebhookInfo');
    }

    public function getWebhookInfo()
    {
        return json_decode(file_get_contents($this->resource . $this->api . '/getWebhookInfo'), JSON_OBJECT_AS_ARRAY);
    }

    public function getUpdates()
    {
        return json_decode(file_get_contents($this->resource . $this->api . '/getUpdates'), JSON_OBJECT_AS_ARRAY);
    }

    public function sendMessage($params)
    {

        file_get_contents($this->resource . $this->api . '/sendMessage?' . http_build_query($params));
        /*$url = $this->resource . $this->api . '/sendMessage?' . http_build_query($params);


        if (!$curld = curl_init()) {
            exit;
        }
        curl_setopt($curld, CURLOPT_POST, true);
       // curl_setopt($curld, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        return $output;*/
    }

    public function deleteMessage($params)
    {
        return file_get_contents($this->resource . $this->api . '/deleteMessage?'. http_build_query($params));
    }
    public function stopPoll()
    {
        return file_get_contents($this->resource . $this->api . '/stopPoll');
    }


}
<?php


namespace App\Service;


class GetHttpService
{

    private $username;
    private $password;
    private $url;

    public function __construct(
        $username,
        $password,
        $url
    )
    {
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
    }

    public function getSettings()
    {
        $data = [
            'field1' => null,
            'field2' => 111,
            'field3' => [
                'string',
                234
            ]
        ];
        return $this->httpConnect($data);
    }

    public function httpConnect($data)
    {
        $context = stream_context_create([
                'http' => [
                    'header' => "Authorization: Basic " . base64_encode("$this->username:$this->password"), 'content' => $data
                ]
            ]
        );
        return file_get_contents($this->url, false, $context);
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }

    public function url()
    {
        return $this->url;
    }


}




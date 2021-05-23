<?php


namespace App\Service;

use Spiral\GRPC;

class GetGrpcService
{
    private $serverKey;
    private $url;
    private ContextInterface $ctx;

    public function __construct(
        $serverKey,
        $url,
        ContextInterface $ctx
    )
    {
        $this->serverKey = $serverKey;
        $this->url = $url;
        $this->ctx = $ctx;
    }

    public function getSettings()
    {
        $data = [
            'field1' => 'string',
            'field2' => null,
            'field3' => 3
        ];
        return $this->grpcConnect($data);
    }

    public function grpcConnect($data)
    {
        $result = $this->ctx->getValue(GRPC\ResponseHeaders::class)->set($this->url(), $this->serverKey(), $data);
        return $result;
    }

    public function serverKey()
    {
        return $this->serverKey;
    }

    public function url()
    {
        return $this->url;
    }

}
<?php

namespace App\Controller;

use App\Entity\SettingService;
use App\Service\GetGrpcService;
use App\Service\GetHttpService;
use App\Service\GetRestApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataServicesController extends AbstractController
{
    /**
     * @Route("/data/services", name="data_services")
     */
    public function index(
        GetRestApiService $getRestApiService,
        GetHttpService $getHttpService,
        GetGrpcService $getGrpcService
    ): Response
    {

        $setting  = new SettingService();

        $api = $getRestApiService->getSettings();
        $http = $getHttpService->getSettings();
        $gprc = $getGrpcService->getSettings();

        $setting->setField1($api->getString());
        $setting->getField2($api->getBoolean());
        $setting->getField3($http->getArray());
        $setting->getField4($gprc->getInteger());


        $data = [
            'int1' => $api->getInteger(),
            'int2' => $http->getInteger(),
            'int3' => $gprc->getInteger(),
        ];


        return new JsonResponse($data);
    }
}

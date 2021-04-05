<?php

namespace App\Controller;


use App\Service\WebhookTelegram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BotController extends AbstractController
{
    /**
     * @Route("/bot", name="bot")
     * @param Request $request
     * @param WebhookTelegram $webhookTelegram
     * @return Response
     */

    public function index(Request $request, WebhookTelegram $webhookTelegram)
    {
        $info = $webhookTelegram->getWebhookInfo();
        $data = json_decode($request->getContent(), true);
        file_put_contents('file.txt', 'data: ' . print_r($data, 1) . "\n", FILE_APPEND);
  /* $params_del = [
         "chat_id"=>  '302804041',
        "message_id"=> '45',
        ];
       $webhookTelegram->deleteMessage($params_del);*/
        //$webhookTelegram->stopPoll();
       if (!empty($data)) {

            //$message = 'Вариант';
           $message = $data['message']['text'];
           $keyboard = [
               'inline_keyboard' =>[
               [
                   ['text'=>'1','callback_data'=>'Text222'],
                   ['text'=>'2','callback_data'=>'Text333'],
                   ['text'=>'3','callback_data'=>'Text444'],
                   ['text'=>'4','callback_data'=>'Text555'],
                   ['text'=>'5','callback_data'=>'Text666'],
                   ['text'=>'6','callback_data'=>'Text777']
               ]
           ]
           ];
         /*  $keyboard = [
               'keyboard' => [['Button1'],['Button2'],['Button3']],
               'resize_keyboard' => true,
               'one_time_keyboard' => true,
               'selective' => true
           ];*/


            $params = [
                'chat_id' => $data['message']['chat']['id'],
                'text' => $message,
                'reply_markup'=> json_encode($keyboard, true),



            ];


            $webhookTelegram->sendMessage($params);
        }

        return $this->render('bot/index.html.twig', [
            'info' => $info,

        ]);
    }
}

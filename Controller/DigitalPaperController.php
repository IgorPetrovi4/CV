<?php

namespace App\Controller\Calcs;

use App\Entity\OrderDigitalPaperKalc;
use App\Entity\OrdersAll;
use App\Form\OrderDigitalPaperKalcType;
use App\Repository\DigitalFormatListRepository;
use App\Repository\DigitalPaperListRepository;
use App\Repository\DigitalPrintColorRepository;
use App\Repository\DigitalProductRepository;
use App\Repository\DigitalServiceCreaseRepository;
use App\Repository\DigitalServiceFoldingRepository;
use App\Repository\DigitalServiceHoleRepository;
use App\Service\Calcs\ArticleGeneratorService;
use App\Service\Calcs\BalanceUserService;
use App\Service\Calcs\DeleteUnregisteredOrderService;
use App\Service\Calcs\SelectUserService;
use App\Service\Calcs\DigitalPaperCalckService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DigitalPaperController extends AbstractController
{

    /**
     * @Route("/digital/paper/{format}", name="digital_paper")
     * @param $format
     * @param Request $request
     * @param DigitalPaperCalckService $digitalPaperCalckService
     * @param DigitalProductRepository $digitalProductRepository
     * @return Response
     */
    public function index(
        $format,
        Request $request,
        DigitalProductRepository $digitalProductRepository,
        DigitalPaperCalckService $digitalPaperCalckService,
        DeleteUnregisteredOrderService $deleteUnregisteredOrder,
        SelectUserService $selectUser,
        BalanceUserService $balanceUser,
        ArticleGeneratorService $articleGeneratorService

    ): Response
    {

        // удаляем не дооформленный заказ для партнера
        if ($this->isGranted('ROLE_PARTNER')) {
            $deleteUnregisteredOrder->delete($this->getUser());
        }

        //форма пленки
        $orderDigitalPaperKalc = new OrderDigitalPaperKalc();
        // связь с таблицей всех заказов
        $ordersAll = new OrdersAll();
        $orderDigitalPaperKalc->setOrdersAll($ordersAll);

        $form = $this->createForm(OrderDigitalPaperKalcType::class, $orderDigitalPaperKalc, [
            'format' => $format,
            'id_choice' => $digitalProductRepository->findOneBy(['product_name' => $format])->getFormat()->getValues()[0]->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //выбираем юзера
            $user = $selectUser->getSelectUser($form);
            if (empty($user)) {
                $ordersAll->setNotUser($request->getSession()->getId());
            } else {
                $ordersAll->setUser($user);
            }

            //считаем сумму заказа
            $digitalPaperCalckService->setSelectUser($user);
            $digitalPaperCalckService->setForm($form);
            $priceSum = $digitalPaperCalckService->getSum();

            //просчет баланса
            if ($user->getRoles()[0] === "ROLE_PARTNER") {

                if ($balanceUser->getLastBalance($user) > $priceSum) {
                    $ordersAll->setPaid(true);
                }
                $ordersAll->setBalance($balanceUser->getBalance($priceSum, $user));
                $ordersAll->setPriceMarkup(($priceSum * $digitalPaperCalckService->getMarkup() / 100) + $priceSum);
            }


           if (!empty($form->getData()->getWidth())){
               $ordersAll->setWidth($form->getData()->getWidth());
               $ordersAll->setHeight($form->getData()->getHeight());
           } else {
               $ordersAll->setWidth($form->getData()->getDigitalFormat()->getWidth());
               $ordersAll->setHeight($form->getData()->getDigitalFormat()->getHeight());
           }

            $ordersAll->setPrice($priceSum);
            $ordersAll->setSum($form->getData()->getSum());
            $ordersAll->setSumKit($form->getData()->getSumKit());


            // генерация номера артикля BR - баннер, рандом 4, текущая дата
            $ordersAll->setArticleNumber($articleGeneratorService->getArticleNumber('DP'));


            $ordersAll->setDatetime(new DateTimeImmutable());
            if ($this->isGranted("ROLE_MANAGER")) {
                $ordersAll->setSales(1);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderDigitalPaperKalc);
            $entityManager->flush();
           // $this->addFlash('success', 'Ваш заказ добавлен в корзину!');
            // возвращаем после заказа на роут ..
            if ($this->isGranted("ROLE_PARTNER")) {
                return $this->redirectToRoute('partner_order_shop');
            } elseif ($this->isGranted("ROLE_MANAGER")) {
                return $this->redirectToRoute('manager_page');
            } else {
                return $this->redirectToRoute('order_shop');
            }

        }

        if ($this->isGranted("ROLE_PARTNER")) {
            return $this->render('new_pages/calcs/digital_paper_page/partner_form_calculator_digital_paper.html.twig', [
                'form' => $form->createView(),
                'format' => $format,
                 'product_size' => $digitalProductRepository->findAll(),

            ]);
        } elseif ($this->isGranted("ROLE_MANAGER")) {
            return $this->render('new_pages/calcs/digital_paper_page/manager_form_calculator_digital_paper.html.twig', [
                'form' => $form->createView(),
                'format' => $format,
                'product_size' => $digitalProductRepository->findAll(),

            ]);
        } else {
            return $this->render('new_pages/calcs/digital_paper_page/form_calculator_digital_paper.html.twig', [
                'form' => $form->createView(),
                'format' => $format,
                'product_size' => $digitalProductRepository->findAll(),


            ]);
        }

    }


    /**
     * @Route("/digital_paper_kalc/ajax", name="digitalPaperKalc", methods={"GET","POST"})
     * @param Request $request
     * @param DigitalPaperCalckService $digitalPaperCalckService
     * @return JsonResponse
     */
    public function kalc(Request $request,  DigitalProductRepository $digitalProductRepository, DigitalPaperCalckService $digitalPaperCalckService, SelectUserService $selectUser)
    {
        $format  = $request->query->get('format_');
        $orderDigitalPaperKalc = new OrderDigitalPaperKalc();
        $form = $this->createForm(OrderDigitalPaperKalcType::class, $orderDigitalPaperKalc, [
            'format' => $format,
            'id_choice' => $digitalProductRepository->findBy(['product_name'=>$format])[0]->getFormat()->getValues()[0]->getId()
        ]);
        $form->handleRequest($request);
        $user = $selectUser->getSelectUser($form);
        $digitalPaperCalckService->setSelectUser($user);
        $digitalPaperCalckService->setForm($form);
        $priceSumUser = $digitalPaperCalckService->getSum();
        if ($this->isGranted("ROLE_PARTNER")){
            $price = round(($priceSumUser * $digitalPaperCalckService->getMarkup() / 100) + $priceSumUser, 2);
            return new JsonResponse($price);
        } else{
            return new JsonResponse($priceSumUser);
        }
    }


    /**
     * @Route("/digital_paper/ajax", name="digitalPaper", methods={"GET"})
     */

    public function paper(
        Request $request,
        DigitalPaperListRepository $digitalPaperListRepository,
        DigitalFormatListRepository $digitalFormatListRepository,
        DigitalPrintColorRepository $digitalPrintColorRepository): Response
    {
        $idFormat = !empty($request->query->get('idFormat')) ? $request->query->get('idFormat') :  $digitalFormatListRepository->findOneBy(['name'=>$request->query->get('format')])->getId();
        $idProduct = $request->query->get('idProduct');
        $papers = $digitalPaperListRepository->getPapersForFormat($idFormat, $idProduct);
        $id_first_paper =  $papers[0]->getId();
        $paperAsArray = [];
        foreach ($papers as $value) {
            $paperAsArray[] = [
                'id_paper' => $value->getId(),
                'name_paper' => $value->getDigitalPaperName(),
                'id_first_paper'=>$id_first_paper,
                ];

        }

        $colors = $digitalPrintColorRepository->getColorForPaperProduct( $idProduct);
        $id_first_color =  $colors[0]->getId();
        foreach ($colors as $color) {
            $paperAsArray[] = [
                'id_color' => $color->getId(),
                'name_color' => $color->getColorName(),
                'id_first_color'=>$id_first_color,
            ];

        }

        return new JsonResponse($paperAsArray );


    }


    /**
     * @Route("/digital_paper_lamination/ajax", name="digitalPaperLamination", methods={"GET"})
     */

    public function lamination(Request $request, DigitalPaperListRepository $digitalPaperListRepository): Response
    {

        $idPaper = $request->query->get('idPaper');
        $papers = $digitalPaperListRepository->findBy(['id' => $idPaper], ['id' => 'ASC']);
        foreach ($papers as $paper) {
            $laminations = $paper->getLamination()->getValues();
            $idLamination = $laminations[0]->getId();
            foreach ($laminations as $lamination) {
                $laminationAsArray[] = [
                    'id_lamination' => $lamination->getId(),
                    'name_lamination' => $lamination->getLaminationName(),
                    'id_paper_lamination' => $idLamination
                ];
            }
        }

        return new JsonResponse($laminationAsArray);
    }


    /**
     * @Route("/geometry_preview/ajax", name="geometryPreview", methods={"GET"})
     */

    public function geometryPreview(
        Request $request,
        DigitalFormatListRepository $digitalFormatListRepository,
        DigitalServiceFoldingRepository $digitalServiceFoldingRepository,
        DigitalServiceCreaseRepository $digitalServiceCreaseRepository,
        DigitalServiceHoleRepository $digitalServiceHoleRepository

    ): Response
    {

        $idFormat = $request->query->get('idFormat');
        $idRounding = $request->query->get('idRounding');
        $idFolding = !empty($request->query->get('idFolding')) ? $digitalServiceFoldingRepository->find($request->query->get('idFolding'))->getAmount() : 0;
        $idHole = !empty($request->query->get('idHole')) ? $digitalServiceHoleRepository->find($request->query->get('idHole'))->getAmount() : 0;
        $idCrease = !empty($request->query->get('idCrease')) ? $digitalServiceCreaseRepository->find($request->query->get('idCrease'))->getAmount(): 0;
        $width_dp = $request->query->get('width_dp');
        $height_dp = $request->query->get('height_dp');
        if (!empty($idFormat)) {
            $width = $digitalFormatListRepository->findBy(['id' => $idFormat])[0]->getWidth();
            $height = $digitalFormatListRepository->findBy(['id' => $idFormat])[0]->getHeight();
        }

        if (empty($idFormat) && empty($width_dp)) {
            $width = 320;
            $height = 450;
        }


        if (!empty($width_dp) && !empty($height_dp)) {
            $width = $width_dp;
            $height = $height_dp;
        }

        // текст под превью геометрии бумаги
        if ($width === 450 ||  $height === 450){
            $text = 'Розмір аркуша: 320мм x 450мм<br>Область друку: 310мм х 440мм';
        }
        elseif ($idFormat == 13){
            $text = '2 фальца';
        }
        elseif ($idFormat == 14 || $idFormat == 16 || $idFormat == 17){
            $text = '1 фальц';
        }else{
            $text = '';
        }



        $pixel =  3.793627;

        // деление листа фальцовка
        if ($idFormat == 13 || $idFolding == 2 || $idCrease == 2 ){
            $width_b = 40;
            $width_b2 = 0;
            $height_b2 = 0;
            $height_b = 100;
        } elseif ( $idFormat == 14 || $idFormat == 16 || $idFormat == 17 || $idFolding == 1 || $idCrease == 1){
            $width_b = 0;
            $height_b = 100;
            $width_b2 = 0;
            $height_b2 = 0;
        } elseif ($idCrease == 4 || $idFolding == 4){
            $width_b = 73;
            $width_b2 = 33;
            $height_b = 100;
            $height_b2 = 100;
        } elseif ($idFolding == 3 || $idCrease == 3){
            $width_b = 65;
            $width_b2 = 0;
            $height_b = 100;
            $height_b2 = 100;
        }

// скругление углов
        if ($idRounding == 2){
            $tr = 20;
        } elseif ($idRounding == 3){
            $tr = 20;
            $tl = 20;
        } elseif ($idRounding == 4){
            $tr = 20;
            $tl = 20;
            $br = 20;
        } elseif ($idRounding == 5){
            $tr = 20;
            $tl = 20;
            $br = 20;
            $bl = 20;
        }






        $sizeFormat[] = [
            'w' => $width,
            'h' => $height,
            'width' =>  $width > $height ? 100 : $width/$height*100,
            'height' => $height > $width ? 100 : $height/$width*100,
            'h_centre' => $height*$pixel/(($height*$pixel)/ ($height/$width*100 >= 100  ? 250/2 : (250*($height/$width*100)/100) /2 ) ),
            'text'=>$text,
            'width_b' =>  !empty($width_b) ? $width_b : 0,
            'width_b2'=> !empty($width_b2) ? $width_b2 : 0,
            'height_b' => !empty($height_b) ? $height_b :0,
            'height_b2' => !empty($height_b2) ? $height_b2 :0,
            'width_r' =>  20,
            'height_r' => 20,
            'tr'=> !empty($tr) ? $tr : 0,
            'tl'=> !empty($tl) ? $tl : 0,
            'br'=> !empty($br) ? $br : 0,
            'bl'=> !empty($bl) ? $bl : 0,
            'sum_hole'=>!empty($idHole ) && $idHole >= 1 ? 'x' . $idHole  : '',
            'hole'=>!empty($idHole )  && $idHole >= 1 ? 1 : 0,

        ];


        return new JsonResponse($sizeFormat);


    }


}

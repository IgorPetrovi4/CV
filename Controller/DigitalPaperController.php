<?php

namespace App\Controller\Calcs;

use App\Entity\Calcs\DigitalPaperCalc;
use App\Entity\OrderDigitalPaperKalc;
use App\Entity\OrdersAll;
use App\Form\OrderDigitalPaperKalcType;
use App\Repository\DigitalFormatListRepository;
use App\Repository\DigitalLaminationRepository;
use App\Repository\DigitalPaperListRepository;
use App\Repository\DigitalPaperProductRepository;
use App\Repository\DigitalPrintColorRepository;
use App\Repository\DigitalProductRepository;
use App\Repository\DigitalServiceCreaseRepository;
use App\Repository\DigitalServiceFoldingRepository;
use App\Repository\DigitalServiceHoleRepository;
use App\Repository\OrdersAllRepository;
use App\Repository\UserMarkupRepository;
use App\Service\DigitalPaperCalckService;
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
     * @param OrdersAllRepository $ordersAllRepository
     * @param UserMarkupRepository $markupRepository
     * @return Response
     */
    public function index(
        $format,
        Request $request,
        DigitalPaperCalckService $digitalPaperCalckService,
        DigitalProductRepository $digitalProductRepository,
        OrdersAllRepository $ordersAllRepository,
        UserMarkupRepository $markupRepository

    ): Response
    {

        //  удаление неоформленного заказа перед заказом нового
        $orderSales = $ordersAllRepository->findBy(['user' => $this->getUser(), 'sales' => null]);

        if (!empty($orderSales)) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($orderSales as $order_sale) {
                $entityManager->remove($order_sale);
                $entityManager->flush();
            }
        }

        //форма пленки
        $orderDigitalPaperKalc = new OrderDigitalPaperKalc();

        // связь с таблицей всех заказов
        $ordersAll = new OrdersAll();
        $ordersAll->setPaper($orderDigitalPaperKalc->getId());
        $orderDigitalPaperKalc->setOrdersAll($ordersAll);

        $form_digital_paper = $this->createForm(OrderDigitalPaperKalcType::class, $orderDigitalPaperKalc, [
            'format' => $format,
            'id_choice' => $digitalProductRepository->findOneBy(['product_name' => $format])->getFormat()->getValues()[0]->getId()
        ]);

        $form_digital_paper->handleRequest($request);

        if ($form_digital_paper->isSubmitted() && $form_digital_paper->isValid()) {
            // данные для сервиса


            // подготовлено к переносу в ООП  $arr_data[].
            $digitalPaperCalc = new DigitalPaperCalc();
            $digitalPaperCalc->setDigitalFormat($form_digital_paper->getData()->getDigitalFormat()->getId());
            $digitalPaperCalc->setYourSizeWidth($form_digital_paper->getData()->getWidth());
            $digitalPaperCalc->setYourSizeHeight($form_digital_paper->getData()->getHeight());
            $digitalPaperCalc->setFormat($format);
            $digitalPaperCalc->setDigitalPaper($form_digital_paper->getData()->getDigitalPaper()->getId());
            $digitalPaperCalc->setPrintColor($form_digital_paper->getData()->getPrintColor()->getId());
            $digitalPaperCalc->setLamination($form_digital_paper->getData()->getLamination()->getId());
            $digitalPaperCalc->setServicesRounding($form_digital_paper->getData()->getServices()->getId());
            $digitalPaperCalc->setServicesHole($form_digital_paper->getData()->getServiceHole()->getId());
            $digitalPaperCalc->setServicesFolding($form_digital_paper->getData()->getServiceFolding()->getId());
            $digitalPaperCalc->setServicesCrease($form_digital_paper->getData()->getServiceCrease()->getId());
            $digitalPaperCalc->setSum($form_digital_paper->getData()->getSum());
            $digitalPaperCalc->setSumKit($form_digital_paper->getData()->getSumKit());

            $priceSum = $digitalPaperCalckService->DigitalPaperCalk($digitalPaperCalc);

            // Партнеру
            if ($this->isGranted("ROLE_PARTNER")) {
                //просчет баланса
                $last_balance = $ordersAllRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC'], 1);
                $ordersAll->setBalance(round(!empty($last_balance) ? $last_balance[0]->getBalance() - $priceSum : '-' . $priceSum, 2));
                $ordersAll->setPriceMarkup(round(($priceSum * ($markupRepository->findBy(['user' => $this->getUser()])[0]->getDigitalPaperKalc()) / 100) + $priceSum, 2));
            }

            $ordersAll->setWidth($form_digital_paper->getData()->getDigitalFormat()->getWidth());
            $ordersAll->setHeight($form_digital_paper->getData()->getDigitalFormat()->getHeight());
            $ordersAll->setPrice($priceSum);
            $ordersAll->setSum($form_digital_paper->getData()->getSum());
            $ordersAll->setSumKit($form_digital_paper->getData()->getSumKit());

            // генерация номера артикля PL - пленка, рандом 4, текущая дата
            $articleNumber = 'DP-' . mt_rand(1111, 9999) . '-' . date("ymd");
            $ordersAll->setArticleNumber($articleNumber);

            // усли нет юзера то ставим сесию
            if (!empty($this->getUser())) {
                $ordersAll->setUser($this->getUser());
            } else {
                $session = $request->getSession()->getId();
                $ordersAll->setNotUser($session);
            }

            $ordersAll->setDatetime(new DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderDigitalPaperKalc);
            $entityManager->flush();


            $this->addFlash('success', 'Ваш заказ добавлен в корзину!');

            if ($this->isGranted('ROLE_PARTNER')) {
                return $this->redirectToRoute('partner_order_shop');
            }
            return $this->redirectToRoute('order_shop');
        }

        return $this->render('new_pages/calcs/digital_paper_page/form_calculator_digital_paper.html.twig', [
            'form' => $form_digital_paper->createView(),
            'format' => $format,
            'product_size' => $digitalProductRepository->findAll(),

        ]);
    }


    /**
     * @Route("/digital_paper_kalc/ajax", name="digitalPaperKalc", methods={"GET"})
     * @param Request $request
     * @param DigitalPaperCalckService $digitalPaperCalckService
     * @return JsonResponse
     */
    public function kalc(Request $request, DigitalPaperCalckService $digitalPaperCalckService, UserMarkupRepository $markupRepository)
    {
        $arr_data = [
            'digitalFormat' => $request->query->get('digitalFormat'),
            'yourSizeWidth' => $request->query->get('yourSizeWidth'),
            'yourSizeHeight' => $request->query->get('yourSizeHeight'),
            'format' => $request->query->get('format'),
            'digitalPaper' => $request->query->get('digitalPaper'),
            'printColor' => $request->query->get('printColor'),
            'lamination' => $request->query->get('lamination'),
            'servicesRounding' => $request->query->get('servicesRounding'),
            'servicesHole' => $request->query->get('servicesHole'),
            'servicesFolding' => $request->query->get('servicesFolding'),
            'servicesCrease' => $request->query->get('servicesCrease'),
            'sum' => $request->query->get('sum'),
            'sumKit' => $request->query->get('sumKit'),
            'markup' => $this->isGranted('ROLE_PARTNER') ? $markupRepository->findBy(['user' => $this->getUser()])[0]->getDigitalPaperKalc() : 0,
        ];


        $priceSumUser = $digitalPaperCalckService->DigitalPaperCalk($arr_data);

        return new JsonResponse($priceSumUser);
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

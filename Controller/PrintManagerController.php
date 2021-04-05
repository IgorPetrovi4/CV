<?php

namespace App\Controller\ManagerCrud;

use App\Entity\OrdersAll;
use App\Form\PrintManagerType;
use App\Repository\OrdersAllRepository;
use App\Service\ImagickCheckService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;

/**
 * @Route("/print/manager/orders")
 */

class PrintManagerController extends AbstractController
{


    /**
     * @Route("/", name="print_manager_page", methods={"GET","POST"})
     * @param Request $request
     * @param OrdersAllRepository $ordersAllRepository
     * @return Response
     */

    public function index(Request $request, OrdersAllRepository $ordersAllRepository): Response
    {

        if ($this->isGranted('ROLE_PRINT_MANAGER_DP')){
            $ordersAll = $ordersAllRepository->getAllOrdersManagerDP();
        }
        if ($this->isGranted('ROLE_PRINT_MANAGER')){
            $ordersAll = $ordersAllRepository->getAllOrdersManagerAll();
        }

        $forms = [];
        foreach ($ordersAll as $order){
            $form = $this->createForm(PrintManagerType::class, $order);
            $form->handleRequest($request);
            $forms[$order->getId()] = $form->createView();
       }

        return $this->render('manager/print_manager/view_out_data_form_vars_value.html.twig', [
            'orders_alls'=> $ordersAll,
             'forms' => $forms,
        ]);
    }


    /**
     * @Route("/check", name="print_manager_orders_check", methods={"GET","POST"})
     */
    public function edit(Request $request, OrdersAllRepository $ordersAllRepository, ImagickCheckService $checkService): Response
    {



        $order_id = $request->request->all('print_manager')['submit'];
        $order = $ordersAllRepository->findBy(['id'=>$order_id]);

        // печать наклейки чека
        if ($request->request->get('print_manager')['status'] == 4){
       // данные для сервиса наклейки чека
        $size = $order[0]->getWidth().'mm'.' x '.$order[0]->getHeight().'mm';
        $sum = [
            'sum'=>$order[0]->getSum(),
            'kit'=>!empty($order[0]->getSumKit()) ? $order[0]->getSumKit(): 1,
            'edition'=>!empty($order[0]->getSumKit()) ? $order[0]->getSum()*$order[0]->getSumKit() : 1,
        ];

            if (!empty( $order[0]->getFilm())){
                $film =[
                    'name'=>'Самоклеящеся пленка',
                    'product'=>$order[0]->getFilm()->getFilm()->getFilmName(),
                    'resolution' => !empty( $order[0]->getFilm()->getResolution() ) ? $order[0]->getFilm()->getResolution()->getResolutionName(): null,
                    'lamination' => !empty( $order[0]->getFilm()->getLamination()) && $order[0]->getFilm()->getLamination()->getId() != 1? $order[0]->getFilm()->getLamination()->getLaminationName(): null,
                ];
               $checkService->getCheck($order[0]->getArticleNumber(),  $film, $size, $sum );

            }
            if (!empty( $order[0]->getPaper())){
                $paper =[
                    'name'=>'Бумага',
                    'product'=>$order[0]->getPaper()->getPaper()->getPaperName(),
                    'resolution' => !empty( $order[0]->getPaper()->getResolution()) ? $order[0]->getPaper()->getResolution()->getResolutionName() : null,
                    'lamination' => !empty( $order[0]->getPaper()->getLamination()) && $order[0]->getPaper()->getLamination()->getId() != 1? $order[0]->getPaper()->getLamination()->getLaminationName(): null,
                ];
              $checkService->getCheck($order[0]->getArticleNumber(), $paper, $size,  $sum );

            }
            if (!empty( $order[0]->getDigitalPaper())){

                $digitalPaper = [
                    'name'=>'SRA3',
                    'product'=>$order[0]->getDigitalPaper()->getDigitalPaperProduct()->getName(),
                    'resolution' => $order[0]->getDigitalPaper()->getDigitalPaper()->getDigitalPaperName(),
                    'lamination' => !empty( $order[0]->getDigitalPaper()->getLamination()) && $order[0]->getDigitalPaper()->getLamination()->getId() != 1 ? $order[0]->getDigitalPaper()->getLamination()->getLaminationName(): null,
                    'color'=> !empty( $order[0]->getDigitalPaper()->getPrintColor()) ? $order[0]->getDigitalPaper()->getPrintColor()->getColorName(): null,
                ];
                $checkService->getCheck($order[0]->getArticleNumber(), $digitalPaper, $size,  $sum );

            }
            if (!empty( $order[0]->getBanner())){

                $banner =[
                    'name'=>'Баннер',
                    'product'=>$order[0]->getBanner()->getBanner()->getBannerName(),
                    'resolution' => !empty( $order[0]->getBanner()->getResolution()) ? $order[0]->getBanner()->getResolution()->getResolutionName(): null,
                    'pocket'=> !empty( $order[0]->getBanner()->getPocket()) ? $order[0]->getBanner()->getPocket()->getPocketName(): null,
                    'cringle'=> !empty( $order[0]->getBanner()->getCringle()) ? $order[0]->getBanner()->getCringle()->getCringleName(): null,
                    'upturn'=> !empty( $order[0]->getBanner()->getUpturn()) ? $order[0]->getBanner()->getUpturn()->getUpturnName(): null,
                ];
                $checkService->getCheck($order[0]->getArticleNumber(),  $banner, $size, $sum);

            }

            if (!empty( $order[0]->getPloter())){

                $ploter =[
                    'name'=>'Плотер',
                    'product'=>$order[0]->getPloter()->getOrdersAll()->getPloter()->getFilm()->getFilmName().$order[0]->getPloter()->getOrdersAll()->getPloter()->getFilm()->getProduct(),
                    'width_line' => (int)$order[0]->getPloter()->getWidth()/1000,
                    'cutting'=> !empty( $order[0]->getPloter()->getCutting()) ? $order[0]->getPloter()->getCutting()->getCuttingName(): null,
                    'selection'=> !empty( $order[0]->getPloter()->getSelection()) ? $order[0]->getPloter()->getSelection()->getSelectionName(): null,
                ];
                $size_ploter =  '1000 mm'.' x '.$order[0]->getHeight().'mm';
                $checkService->getCheck($order[0]->getArticleNumber(),  $ploter, $size_ploter, $sum);

            }

        }


        $form = $this->createForm(PrintManagerType::class, $order[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('print_manager_page');
        }


    }




}

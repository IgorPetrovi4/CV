<?php

namespace App\Controller\Pagination;

use App\Repository\OrdersAllRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pagination")
 */
class PaginationController extends AbstractController
{
    /**
     * @Route("/{page<\d+>?1}", name="pagination", methods={"GET"})
     * @param OrdersAllRepository $ordersAllRepository
     * @param $page
     * @return Response
     */
    public function index(OrdersAllRepository $ordersAllRepository, $page): Response
    {

        $limit = 30;
        $query = $ordersAllRepository->getAllOrders($page, $limit, $this->getUser());
        $paginator = new Paginator($query);
        $count_pages = ceil(count($paginator) / $limit);

        return $this->render('pagination/index.html.twig', [
            'orders'=>$paginator,
            'page' => $page,
            'count_pages' => $count_pages,
        ]);
    }

}

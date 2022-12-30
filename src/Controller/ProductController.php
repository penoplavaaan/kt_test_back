<?php

namespace App\Controller;

use App\Handler\ProductList;
use App\Handler\ProductsStatistics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'list', methods: 'GET')]
    public function list(Request $request, ProductList $handler): Response
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('size');
        $query = $request->query->get('q') ?? '';
        $filterBy = $request->query->get('filterBy');
        $filterValue = $request->query->get('filterValue');


        $products = $handler->handle($query, $filterBy, $filterValue, $page, $limit);

        return new JsonResponse($products);
    }


    #[Route('/api/products/statistics', name: 'statistics', methods: 'GET')]
    public function getStatistics(ProductsStatistics $handler): Response
    {
        $products = $handler->handle();

        return new JsonResponse($products);
    }

    #[Route('/api/products/statistics/download', name: 'statistics-download', methods: 'GET')]
    public function downloadStatistics(ProductsStatistics $handler): Response
    {
        $statistics = $handler->handle()->toCsvArray();
        $content = implode("\n", $statistics);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }
}
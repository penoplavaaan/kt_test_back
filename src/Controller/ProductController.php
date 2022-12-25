<?php

namespace App\Controller;

use App\Form\CsvFileType;
use App\Form\EmployeeType;
use App\Handler\EmployeeCreate;
use App\Handler\EmployeeList;
use App\Handler\ProductList;
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
}
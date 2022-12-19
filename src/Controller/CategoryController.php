<?php

namespace App\Controller;

use App\Handler\CategoryList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_category_list', methods: 'GET')]
    public function list(Request $request, CategoryList $handler): Response
    {
        $products = $handler->handle();

        return new JsonResponse($products);
    }
}
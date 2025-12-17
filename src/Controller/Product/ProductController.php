<?php

namespace App\Controller\Product;

use App\Repository\ProductRepository;
use App\Service\ProductExporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/list', name: 'product_list')]
    public function list(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['price' => 'DESC']);

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/export', name: 'product_export')]
    public function export(ProductExporter $productExporter): StreamedResponse
    {
        return $productExporter->export();
    }
}

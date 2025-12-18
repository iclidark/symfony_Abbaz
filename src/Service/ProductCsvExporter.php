<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class ProductCsvExporter
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function export(): Response
    {
        $products = $this->productRepository->findAll();

        $csv = "name,description,price\n";
        foreach ($products as $product) {
            $csv .= sprintf(
                '"%s","%s","%.2f"\n',
                str_replace('"', '""', $product->getName()),
                str_replace('"', '""', $product->getDescription()),
                $product->getPrice()
            );
        }

        $response = new Response($csv);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');

        return $response;
    }
}

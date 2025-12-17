<?php

namespace App\Controller;

use App\Service\ProductCsvExporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductExportController extends AbstractController
{
    #[Route('/product/export/csv', name: 'product_export_csv')]
    public function exportCsv(ProductCsvExporter $exporter): Response
    {
        return $exporter->export();
    }
}

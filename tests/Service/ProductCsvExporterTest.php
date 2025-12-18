<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductCsvExporter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductCsvExporterTest extends TestCase
{
    public function testExport()
    {
        $product = new Product();
        $product->setName('Test Product');
        $product->setDescription('Test, with a comma');
        $product->setPrice(10.99);

        $product2 = new Product();
        $product2->setName('Product with "quotes"');
        $product2->setDescription('Another description');
        $product2->setPrice(20.50);

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([$product, $product2]);

        $exporter = new ProductCsvExporter($productRepository);
        $response = $exporter->export();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="products.csv"', $response->headers->get('Content-Disposition'));

        $expectedCsv = "name,description,price\n";
        $expectedCsv .= '"Test Product","Test, with a comma","10.99"\n';
        $expectedCsv .= '"Product with ""quotes""","Another description","20.50"\n';

        $this->assertEquals($expectedCsv, $response->getContent());
    }
}

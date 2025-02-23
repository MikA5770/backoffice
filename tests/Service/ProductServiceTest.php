<?php

namespace App\Tests\Service;

use App\Service\ProductService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductServiceTest extends TestCase
{
    public function testExportCSV()
    {
        $productService = new ProductService();

        $response = $productService->exportCSV();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertStringContainsString('name,description,price', $response->getContent());

        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="products.csv"', $response->headers->get('Content-Disposition'));
    }
}
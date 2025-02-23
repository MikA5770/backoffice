<?php
namespace App\Service;

use League\Csv\Reader;
use League\Csv\Writer;
use PDO;
use SplTempFileObject;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    public function exportCSV(): Response
    {
        $dbh = new PDO("mysql:host=127.0.0.1;port=3306;dbname=backoffice;charset=utf8mb4", "root", "");

        $sth = $dbh->prepare("SELECT name, description, price FROM product");
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $sth->execute();

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setEscape('');

        $csv->insertOne(['name', 'description', 'price']);
        $csv->insertAll($sth);

        $csvContent = $csv->toString();

        return new Response(
            $csvContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="products.csv"',
            ]
        );
    }
    
    public function importCSV(string $csvFile): array
    {
        $csv = Reader::createFromPath('public/' . $csvFile, 'r');
        $csv->setDelimiter('|');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $products = [];

        foreach ($records as $record) {
            $products[] = [
                'name' => $record['name'],
                'description' => $record['description'],
                'price' => $record['price'],
            ];
        }

        return $products;
    }
}
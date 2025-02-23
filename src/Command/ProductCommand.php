<?php

namespace App\Command;

use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import:products',
    description: 'Importe des produits à l\'aide d\'un fichier CSV',
)]
class ProductCommand extends Command
{
    private ProductService $productService;
    private EntityManagerInterface $entityManager;


    public function __construct(ProductService $productService, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->entityManager = $entityManager;

    }

    protected function configure(): void
    {
        $this
            ->addArgument('fileName', InputArgument::REQUIRED, 'Le nom du fichier CSV');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $csvFile = $input->getArgument('fileName');

        if (!file_exists('public/' . $csvFile)) {
            $io->error("Le fichier n'existe pas.");
            return Command::FAILURE;
        }

        try {
            $products = $this->productService->importCSV($csvFile);

            foreach ($products as $productData) {
                $product = new Product();
                $product->setName($productData['name']);
                $product->setDescription($productData['description']);
                $product->setPrice($productData['price']);

                $this->entityManager->persist($product);
            }

            $this->entityManager->flush();
            
            $io->success(count($products) . ' produits ont été importés avec succès.');

        } catch (\Exception $e) {
            $output->writeln('Erreur : ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}   
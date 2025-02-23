<?php

namespace App\DataFixtures;
 
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
 
class ProductFixtures extends Fixture 
{    
    public function __construct()
    {}
    
    public const PRODUCT_REFERENCE = 'Product';

    public function load(ObjectManager $manager)
    {
        $dataProducts = [
            ["name" => "iPhone 16 Pro Max", "description" => "Le dernier modèle d'Apple avec un écran plus grand, une puce A18 Bionic ultra-performante et des améliorations significatives en photographie, notamment en basse lumière.", "price" => 1200],
            ["name" => "iPhone 15", "description" => "Équipé de la puce A16 Bionic, il introduit la Dynamic Island et un appareil photo principal de 48 MP pour des photos plus détaillées.", "price" => 700],
            ["name" => "iPhone 14 Pro", "description" => "Doté de la puce A16 Bionic, il adopte l'USB-C et améliore l'autonomie ainsi que les performances photo par rapport à son prédécesseur.", "price" => 600],
        ];

        foreach ($dataProducts as $key => $dataProduct) {
            $product = new Product();
            $product->setName($dataProduct["name"]);
            $product->setDescription($dataProduct["description"]);
            $product->setPrice($dataProduct["price"]);
                               
            $manager->persist($product);
            $this->addReference(self::PRODUCT_REFERENCE . '_' . $key, $product);

        }
 
        $manager->flush();
    }
}
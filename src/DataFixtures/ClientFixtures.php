<?php

namespace App\DataFixtures;
 
use App\Entity\Client;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture 
{    
    public function __construct()
    {}
    
    public const CLIENT_REFERENCE = 'Client';

    public function load(ObjectManager $manager)
    {
        $dataClients = [
            ["email" => "em@gmail.com", "firstname" => "Mikail", "lastname" => "ER", "phoneNumber" => "0646981209", "address" => "2 allée du lac, Lille", "createdAt" => new DateTimeImmutable()],
            ["email" => "if@gmail.com", "firstname" => "Iliass", "lastname" => "FERCHACH", "phoneNumber" => "0726863123", "address" => "38 rue des cygnes, Forbach", "createdAt" => new DateTimeImmutable()],
            ["email" => "bn@gmail.com", "firstname" => "Noa", "lastname" => "BOUGUERREA", "phoneNumber" => "0608735816", "address" => "23 chemin de la fôret, Metz", "createdAt" => new DateTimeImmutable()],
        ];

        foreach ($dataClients as $key => $dataClient) {
            $client = new Client();
            $client->setEmail($dataClient["email"]);
            $client->setFirstName($dataClient["firstname"]);
            $client->setLastname($dataClient["lastname"]);
            $client->setPhoneNumber($dataClient["phoneNumber"]);
            $client->setCreatedAt($dataClient["createdAt"]);
            $client->setAddress($dataClient["address"]);
                               
            $manager->persist($client);
            $this->addReference(self::CLIENT_REFERENCE . '_' . $key, $client);

        }
 
        $manager->flush();
    }
}
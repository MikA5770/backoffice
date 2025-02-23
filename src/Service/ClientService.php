<?php

namespace App\Service;

use App\Entity\Client;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ClientService {
    
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createClient(string $firstname, string $lastname, string $email, string $phoneNumber, string $address): bool
    {        
        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhoneNumber($phoneNumber);
        $client->setAddress($address);
        $client->setCreatedAt(new DateTimeImmutable());

        $this->em->persist($client);
        $this->em->flush();
        
        return true;
    }
}
<?php
namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase
{
    public function testCreationClient()
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        assert($entityManagerMock instanceof EntityManagerInterface);

        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($client) {
                return $client instanceof Client
                    && $client->getFirstname() === 'Test'
                    && $client->getLastname() === 'Service'
                    && $client->getEmail() === 'test.service@gmail.com'
                    && $client->getPhoneNumber() === '0123456789'
                    && $client->getAddress() === 'Ile du Saulcy'
                    && $client->getCreatedAt() instanceof \DateTimeImmutable;
            }));

        $entityManagerMock->expects($this->once())->method('flush');

        $clientService = new ClientService($entityManagerMock);

        $result = $clientService->createClient(
            'Test',
            'Service',
            'test.service@gmail.com',
            '0123456789',
            'Ile du Saulcy'
        );

        $this->assertTrue($result);
    }
}
<?php

namespace App\Command;

use App\Repository\ClientRepository;
use App\Service\ClientService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'add:client',
    description: 'Créé un nouveau client',
)]
class ClientCommand extends Command
{
    private ClientService $clientService;
    private ClientRepository $clientRepository;
    private ValidatorInterface $validator;

    public function __construct(ClientService $clientService, ClientRepository $clientRepository, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->clientService = $clientService;
        $this->clientRepository = $clientRepository;
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::REQUIRED, 'Le prénom du client')
            ->addArgument('lastname', InputArgument::REQUIRED, 'Le nom du client')
            ->addArgument('email', InputArgument::REQUIRED, 'L\'email du client')
            ->addArgument('phoneNumber', InputArgument::REQUIRED, 'Le numéro de téléphone du client')
            ->addArgument('address', InputArgument::REQUIRED, 'L\'adresse du client');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $email = $input->getArgument('email');
        $phoneNumber = $input->getArgument('phoneNumber');
        $address = $input->getArgument('address');

        $constraints = new Assert\Collection([
            'firstname' => [
                new Assert\NotBlank(['message' => 'Le prénom ne peut pas être vide.']),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z\s-]+$/',
                    'message' => 'Le prénom ne doit contenir que des lettres et espaces.'
                ]),
            ],
            'lastname' => [
                new Assert\NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z\s-]+$/',
                    'message' => 'Le nom ne doit contenir que des lettres et espaces.'
                ]),
            ],
            'email' => [
                new Assert\NotBlank(['message' => "L'email ne peut pas être vide."]),
                new Assert\Email(['message' => "L'email '{{ value }}' n'est pas valide."]),
            ],
            'phoneNumber' => new Assert\NotBlank(['message' => 'Le numéro de téléphone est requis.']),
            'address' => new Assert\NotBlank(['message' => "L'adresse est requise."])
        ]);

        $inputData = compact('firstname', 'lastname', 'email', 'phoneNumber', 'address');
        $violations = $this->validator->validate($inputData, $constraints);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $io->error($violation->getMessage());
            }
            return Command::FAILURE;
        }

        if ($this->clientRepository->findOneBy(['email' => $email])) {
            $io->error("L'email '$email' est déjà utilisé par un autre client.");
            return Command::FAILURE;
        }

        $this->clientService->createClient($firstname, $lastname, $email, $phoneNumber, $address);
        $io->success("Le client '$firstname $lastname' a été créé avec succès !");

        return Command::SUCCESS;
    }
}
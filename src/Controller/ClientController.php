<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Security\Voter\ClientVoter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/client-list', name: 'client-list')]
    public function index(ClientRepository $clientRepository): Response
    {
        if(!$this->isGranted(ClientVoter::VIEW)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 
        
        $clients = $clientRepository->findAll();

        return $this->render('client/client.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/client/add', name: 'client-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        if(!$this->isGranted(ClientVoter::ADD)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $client->setCreatedAt(new DateTimeImmutable());
            $em->persist($client);
            $em->flush();
            $this->addFlash('success', "Le client a été ajouté");
            return $this->redirectToRoute('client-list');
        }

        return $this->render('client/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/client/edit/{id}', name: 'client-edit')]
    public function edit(Request $request, EntityManagerInterface $em, Client $client): Response
    { 
        if(!$this->isGranted(ClientVoter::EDIT)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();
            $this->addFlash('success', "Le client a été modifié !");
            return $this->redirectToRoute('client-list');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form,
            'client' => $client
        ]);
    }

    #[Route('/client/delete/{id}', name: 'client-delete')]
    public function delete(EntityManagerInterface $em, Client $client): Response
    {           
        if(!$this->isGranted(ClientVoter::DELETE)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 
        
        $em->remove($client);
        $em->flush();

        $this->addFlash("success", "Le client a été supprimé !");
        return $this->redirectToRoute('client-list'); 
    }

}
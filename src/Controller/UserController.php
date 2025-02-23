<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user-list', name: 'user-list')]
    public function index(UserRepository $userRepository): Response
    {
        if(!$this->isGranted(UserVoter::VIEW)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 
        
        $users = $userRepository->findAll();

        return $this->render('user/user.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/add', name: 'user-add')]
    public function add(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if(!$this->isGranted(UserVoter::ADD)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, "userpassword");
            $user->setPassword($hashedPassword);
            
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a été ajouté");
            return $this->redirectToRoute('user-list');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'user-edit')]
    public function edit(Request $request, EntityManagerInterface $em, User $user): Response
    { 
        if(!$this->isGranted(UserVoter::EDIT)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a été modifié !");
            return $this->redirectToRoute('user-list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user-delete')]
    public function delete(EntityManagerInterface $em, User $user): Response
    {           
        if(!$this->isGranted(UserVoter::DELETE)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('home'); 
        }; 
        
        $em->remove($user);
        $em->flush();

        $this->addFlash("success", "L'utilisateur a été supprimé !");
        return $this->redirectToRoute('user-list'); 
    }

}
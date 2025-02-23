<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Security\Voter\ProductVoter;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/export-csv', name: 'export_csv')]
    public function exportCSV(ProductService $productService): Response
    {
        if(!$this->isGranted('ROLE_USER')){
            $this->addFlash('success', "Vous n'êtes pas connecté !");
            return $this->redirectToRoute('home'); 
        };
        
        return $productService->exportCSV();
    }
    
    #[Route('/product-list', name: 'product-list')]
    public function index(ProductRepository $productRepository): Response
    {        
        if(!$this->isGranted('ROLE_USER')){
            $this->addFlash('success', "Vous n'êtes pas connecté !");
            return $this->redirectToRoute('home'); 
        };
        
        $products = $productRepository->sortByPrice();

        return $this->render('product/product.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/add', name: 'product-add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        if(!$this->isGranted(ProductVoter::ADD)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('product-list'); 
        }; 

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', "Le produit a été ajouté");
            return $this->redirectToRoute('product-list');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/edit/{id}', name: 'product-edit')]
    public function edit(Request $request, EntityManagerInterface $em, Product $product): Response
    { 
        if(!$this->isGranted(ProductVoter::EDIT)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('product-list'); 
        }; 

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', "Le produit a été modifié !");
            return $this->redirectToRoute('product-list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
            'product' => $product
        ]);
    }

    #[Route('/product/delete/{id}', name: 'product-delete')]
    public function delete(EntityManagerInterface $em, Product $product): Response
    {           
        if(!$this->isGranted(ProductVoter::DELETE)){
            $this->addFlash('success', "Vous n'êtes pas administrateur !");
            return $this->redirectToRoute('product-list'); 
        }; 
        
        $em->remove($product);
        $em->flush();

        $this->addFlash("success", "Le produit a été supprimé !");
        return $this->redirectToRoute('product-list'); 
    }

}
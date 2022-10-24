<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Announces;
use App\Form\AnnounceFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnounceController extends AbstractController
{
    
    #[Route('/announce', name: 'app_announce')]
    public function index(): Response
    {
        return $this->render('announce/index.html.twig', [
            'controller_name' => 'AnnounceController',
        ]);
    }

    #[Route('/addannounce', name: 'app_addannounce')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    { 
        $form = $this->createForm(AnnounceFormType::class);
        

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $myAnnounce = $form->getData();
            $myAnnounce->setCreatedAt();
            $myAnnounce->setUserId(1);

            $entityManager->persist($myAnnounce);
            $entityManager->flush();

            $this->addFlash('success', 'Tu as ajouté une annonce !');

            dd($myAnnounce);
        }

        return $this->render('announce/new.html.twig', [
            'announceForm' => $form->createView()
        ]);
    }
}

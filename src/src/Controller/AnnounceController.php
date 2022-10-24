<?php

namespace App\Controller;

use App\Entity\Announces;
use App\Form\AnnounceFormType;
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
    public function new(Request $request): Response
    { 
        $form = $this->createForm(AnnounceFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('announce/new.html.twig', [
            'announceForm' => $form->createView()
        ]);
    }
}

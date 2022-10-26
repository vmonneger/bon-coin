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

            return $this->redirectToRoute('app_announce');
        }

        return $this->render('announce/new.html.twig', [
            'announceForm' => $form->createView()
        ]);
    }

    #[Route('/allannounce', name: 'app_announce')]
    public function allAnnounces(EntityManagerInterface $entityManager): Response
    {
        // $loggedUser = $this->getUser()->getId();
        $repository = $entityManager->getRepository(Announces::class);
        $announces = $repository->findBy([], ['created_at' =>'ASC']);
        return $this->render('announce/all.html.twig', [
            'announces' => $announces,
            // 'userId' => $loggedUser
        ]);
    }

    #[Route('/removeannounce{id}', name: 'app_removeannounce')]
    public function remove(int $id, EntityManagerInterface $entityManager){
        $announce = $entityManager->getReference(Announces::class, $id);
        $entityManager->remove($announce);
        $entityManager->flush();

        return $this->redirectToRoute('app_announce');
    }

    // #[Route('/userannounces', name: 'app_userannounces')]
    
    #[Route('/announce/{id}', name: 'app_announcebyid')]
    public function getAnnounceById(int $id, EntityManagerInterface $entityManager): Response
    {
        // $loggedUser = $this->getUser()->getId();
        // $announce = $entityManager->getReference(Announces::class, $id);
        $repository = $entityManager->getRepository(Announces::class);
        $announce = $repository->find($id);
        return $this->render('announce/single.html.twig', [
            'announce' => $announce,
            // 'userId' => $loggedUser
        ]);
    }


}

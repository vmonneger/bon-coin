<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Announces;
use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AnnouncesRepository;
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
            $idUser = $this->getUser();
            $myAnnounce = $form->getData();
            $myAnnounce->setCreated_At();
            $myAnnounce->setUserId($idUser);

            $file = $form['Image']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads/';
            $originalFileName = $file->getClientOriginalName();

            $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);

            $fileName = $baseFileName . '-' . uniqId() . '-' . $file->getClientOriginalName();

            $file->move($destination, $fileName);

            $myAnnounce->setImage($fileName);
            
            $entityManager->persist($myAnnounce);
            $entityManager->flush();

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
        $announces = $repository->findBy([], ['created_at' => 'ASC']);
        return $this->render('announce/all.html.twig', [
            'announces' => $announces,
            // 'userId' => $loggedUser
        ]);
    }

    #[Route('/removeannounce/{id}', name: 'app_removeannounce')]
    public function remove(int $id, EntityManagerInterface $entityManager){
        $announce = $entityManager->getReference(Announces::class, $id);
        $entityManager->remove($announce);
        $entityManager->flush();

        return $this->redirectToRoute('app_announce');
    }

    #[Route('/announce/{id}', name: 'app_announcebyid')]
    public function getAnnounceById(int $id, EntityManagerInterface $entityManager): Response
    {
        // $loggedUser = $this->getUser()->getId();
        // $announce = $entityManager->getReference(Announces::class, $id);
        $userID = $this->getUser()->getId();
        
        $repository = $entityManager->getRepository(Announces::class);
        $announce = $repository->find($id);
        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $announce->getUserId()));

        // $vote->getAlreadyDownvote($this->getUser()->getId())
        return $this->render('announce/single.html.twig', [
            'announce' => $announce,
            'sellerId' => $announce->getUserId()->getId(),
            'user' => $announce->getUserId(), 
            'announces' => $announce->getUserId()->getAnnounces(), 
            'downvote' => $vote ? $vote->getDownvote() : 0,
            'upvote' => $vote ? $vote->getUpvote() : 0,
            'vote' => $vote
        ]);
    }

    #[Route('/getannounce/{id}', name: 'app_getannounce')]
    public function get(ManagerRegistry $doctrine, int $id, EntityManagerInterface $entityManager, AnnouncesRepository $repo, Request $request){

        $announce = $doctrine->getRepository(Announces::class)->find($id);
        $form = $this->createForm(AnnounceFormType::class, $announce);
        $form->handleRequest($request);
        // $tags = [];
        // foreach ($announce->getTags() as $tag) {
        //     $tags[] = $tag->getId();
        // }

        if ($form->isSubmitted() && $form->isValid()) {
            $myAnnounce = $form->getData();
            $announce->setTitle($myAnnounce->getTitle());
            $announce->setDescription($myAnnounce->getDescription());
            $announce->setPrice($myAnnounce->getPrice());
    
            $file = $form['Image']->getData();
    
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads/';
            $originalFileName = $file->getClientOriginalName();
    
            $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
    
            $fileName = $baseFileName . '-' . uniqId() . '-' . $file->getClientOriginalName();
    
            $file->move($destination, $fileName);

            $announce->setImage($fileName);

            $entityManager->persist($announce);
            $entityManager->flush();

            return $this->redirectToRoute('app_announce');
        }
        return $this->render('announce/new.html.twig', [
            'announceForm' => $form->createView()
        ]);
    }

    #[Route('/updateannounce/{id}', name: 'app_updateannounce')]
    public function update(int $id, EntityManagerInterface $entityManager, Request $request){

     

        $tags = $request->request->all('tags');

        foreach ($tags as $tag) {
            $myTag = new Tags();
            if ($tag == 1) {
                $myTag->setName("Immobilier");
            }
            if ($tag == 2) {
                $myTag->setName("Vacance");
            }
            if ($tag == 3) {
                $myTag->setName("Vehicule");
            }
            $entityManager->persist($myTag);
            $announce->addTag($myTag);
        }
        $entityManager->persist($announce);
        $entityManager->flush();

        return $this->redirectToRoute('app_announce');
    }

    #[Route('/myannounces/{id}', name:"app_getmyannounces")]

    public function getmyannounces(int $id, EntityManagerInterface $entityManager, Request $request) {
        $userID = $this->getUser()->getId();

        $repository = $entityManager->getRepository(Announces::class);
        $announce = $repository->find($id);
        $user = $entityManager->getRepository(User::class)->find($userID);

        return $this->render('announce/myannounces.html.twig', [
            'user' => $announce->getUserId(), 
            'announces' => $user->getAnnounces(), 
        ]);
    }
}

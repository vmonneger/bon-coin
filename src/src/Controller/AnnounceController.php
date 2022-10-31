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

            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';

            //FILE1
            $file = $form['Image']->getData();
            $originalFileName = $file->getClientOriginalName();
            $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
            $fileName = $baseFileName . '-' . uniqId() . '-' . $file->getClientOriginalName();
            $file->move($destination, $fileName);
            $myAnnounce->setImage($fileName);

            //FILE2
            $file2 = $form['Image_2']->getData();
            $originalFileName2 = $file2->getClientOriginalName();
            $baseFileName2 = pathinfo($originalFileName2, PATHINFO_FILENAME);
            $fileName2 = $baseFileName2 . '-' . uniqId() . '-' . $file2->getClientOriginalName();
            $file2->move($destination, $fileName2);
            $myAnnounce->setImage2($fileName2);

            //FILE3
            $file3 = $form['Image_3']->getData();
            $originalFileName3 = $file3->getClientOriginalName();
            $baseFileName3 = pathinfo($originalFileName3, PATHINFO_FILENAME);
            $fileName3 = $baseFileName3 . '-' . uniqId() . '-' . $file3->getClientOriginalName();
            $file3->move($destination, $fileName3);
            $myAnnounce->setImage3($fileName3);

            //FILE4
            $file4 = $form['Image_4']->getData();
            $originalFileName4 = $file4->getClientOriginalName();
            $baseFileName4 = pathinfo($originalFileName4, PATHINFO_FILENAME);
            $fileName4 = $baseFileName4 . '-' . uniqId() . '-' . $file4->getClientOriginalName();
            $file4->move($destination, $fileName4);
            $myAnnounce->setImage4($fileName4);

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
        $repository = $entityManager->getRepository(Announces::class);
        $announces = $repository->findBy([], ['created_at' => 'ASC']);
        return $this->render('announce/all.html.twig', [
            'announces' => $announces,
        ]);
    }

    #[Route('/removeannounce/{id}', name: 'app_removeannounce')]
    public function remove(int $id, EntityManagerInterface $entityManager)
    {
        $announce = $entityManager->getReference(Announces::class, $id);
        $entityManager->remove($announce);
        $entityManager->flush();

        return $this->redirectToRoute('app_announce');
    }

    #[Route('/announce/{id}', name: 'app_announcebyid')]
    public function getAnnounceById(int $id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Announces::class);
        $announce = $repository->find($id);
        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $announce->getUserId()));

        return $this->render('announce/single.html.twig', [
            'announce' => $announce,
            'sellerId' => $announce->getUserId()->getId(),
            'user' => $announce->getUserId(),
            'announces' => $announce->getUserId()->getAnnounces(),
            'downvote' => $vote ? $vote->getDownvote() : 0,
            'upvote' => $vote ? $vote->getUpvote() : 0,
            'vote' => $vote,
            'questions' => $announce->getQuestions()->getValues(),
        ]);
    }

    #[Route('/getannounce/{id}', name: 'app_getannounce')]
    public function get(ManagerRegistry $doctrine, int $id, EntityManagerInterface $entityManager, AnnouncesRepository $repo, Request $request)
    {

        $announce = $doctrine->getRepository(Announces::class)->find($id);
        $form = $this->createForm(AnnounceFormType::class, $announce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myAnnounce = $form->getData();
            $announce->setTitle($myAnnounce->getTitle());
            $announce->setDescription($myAnnounce->getDescription());
            $announce->setPrice($myAnnounce->getPrice());

            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';

            //FILE1
            $file = $form['Image']->getData();
            if ($file !== null) {
                $originalFileName = $file->getClientOriginalName();
                $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $fileName = $baseFileName . '-' . uniqId() . '-' . $file->getClientOriginalName();
                $file->move($destination, $fileName);
                $announce->setImage($fileName);
            } else {
                $announce->setImage($announce->getImage());
            }

            //FILE2
            $file2 = $form['Image_2']->getData();
            if ($file2 !== null) {
                $originalFileName2 = $file2->getClientOriginalName();
                $baseFileName2 = pathinfo($originalFileName2, PATHINFO_FILENAME);
                $fileName2 = $baseFileName2 . '-' . uniqId() . '-' . $file2->getClientOriginalName();
                $file2->move($destination, $fileName2);
                $announce->setImage2($fileName2);
            } else {
                $announce->setImage2($announce->getImage2());
            }

            //FILE3
            $file3 = $form['Image_3']->getData();
            if ($file3 !== null) {
                $originalFileName3 = $file3->getClientOriginalName();
                $baseFileName3 = pathinfo($originalFileName3, PATHINFO_FILENAME);
                $fileName3 = $baseFileName3 . '-' . uniqId() . '-' . $file3->getClientOriginalName();
                $file3->move($destination, $fileName3);
                $announce->setImage3($fileName3);
            } else {
                $announce->setImage3($announce->getImage3());
            }

            //FILE4
            $file4 = $form['Image_4']->getData();
            if ($file4 !== null) {
                $originalFileName4 = $file4->getClientOriginalName();
                $baseFileName4 = pathinfo($originalFileName4, PATHINFO_FILENAME);
                $fileName4 = $baseFileName4 . '-' . uniqId() . '-' . $file4->getClientOriginalName();
                $file4->move($destination, $fileName4);
                $announce->setImage4($fileName4);
            } else {
                $announce->setImage4($announce->getImage4()); 
            }

            $entityManager->persist($announce);
            $entityManager->flush();

            return $this->redirectToRoute('app_announce');
        }
        return $this->render('announce/edit.html.twig', [
            'announceForm' => $form->createView(),
            'announce' => $announce,
        ]);
    }

    #[Route('/myannounces', name: "app_getmyannounces")]

    public function getmyannounces(EntityManagerInterface $entityManager, Request $request)
    {
        $userID = $this->getUser()->getId();

        $user = $entityManager->getRepository(User::class)->find($userID);

        return $this->render('announce/myannounces.html.twig', [
            'user' => $user,
            'announces' => $user->getAnnounces(),
        ]);
    }
}

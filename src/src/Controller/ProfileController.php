<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Vote;
use App\Entity\Announces;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;

class ProfileController extends AbstractController
{
    #[Route('/profile/update', name: 'app_profile_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $userID = $this->getUser()->getId();
        $user = $entityManager->getRepository(User::class)->find($userID);

        $user->setName($request->request->get('name'))
            ->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $request->request->get('password')
                )
            );

        $entityManager->persist($user);
        $entityManager->flush();

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }

    #[Route('/profile/{userID}', name: 'app_profile_id')]
    public function profileId($userID, EntityManagerInterface $entityManager): Response
    {
        $currentUserID = $this->getUser()->getId();

        // Redirect if is the current user.
        if ($userID === strval($currentUserID)) {
            return $this->redirectToRoute('app_profile');
        }

        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $userID));
        $user = $entityManager->getRepository(User::class)->find($userID);

        $upvoteDisabled = false;
        $downvoteDisabled = false;

        $upvoteArray = $vote->getUpvote();
        $downvoteArray = $vote->getDownvote();

        if (in_array($currentUserID, $upvoteArray)) {
            global $upvoteDisabled;
            $upvoteDisabled = true;
        }

        if (in_array($currentUserID, $downvoteArray)) {
            global $upvoteDisabled;
            $downvoteDisabled = true;
        }


        return $this->render('profile/getOne.html.twig', [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'created_at' => $user->getCreated_At(),
            'downvote' => count($vote->getDownvote()),
            'upvote' => count($vote->getUpvote()),
            'upvoteDisabled' => $upvoteDisabled ? 'disabled' : '',
            'downvoteDisabled' => $downvoteDisabled ? 'disabled' : '',
            'announces' => $user->getAnnounces(),
            'vote' => $vote
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, Request $request): Response
    {

        $userID = $this->getUser()->getId();
        $user = $doctrine->getRepository(User::class)->find($userID);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';

        if ($form->isSubmitted() && $form->isValid()) {
            $myProfile = $form->getData();
            $user->setName($myProfile->getName());
            $user->setEmail($myProfile->getEmail());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $myProfile->getPassword()
                )
            );

            //PP
            $file = $form['profilepicture']->getData();
            if($file !== null) {
                $originalFileName = $file->getClientOriginalName();
                $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
                $fileName = $baseFileName . '-' . uniqId() . '-' . $file->getClientOriginalName();
                $file->move($destination, $fileName);
                $user->setProfilepicture($fileName);
            }
            else {
                $user->setProfilepicture($user->getProfilepicture()); 
            }

            //BGPicture
            $file2 = $form['backgroundpicture']->getData();
            if($file2 !== null) {
                $originalFileName2 = $file2->getClientOriginalName();
                $baseFileName2 = pathinfo($originalFileName2, PATHINFO_FILENAME);
                $fileName2 = $baseFileName2 . '-' . uniqId() . '-' . $file2->getClientOriginalName();
                $file2->move($destination, $fileName2);
                $user->setBackgroundpicture($fileName2);
            }
            else {
                $user->setBackgroundpicture($user->getBackgroundpicture()); 
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }
        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $userID));
        $user = $entityManager->getRepository(User::class)->find($userID);

        return $this->render('profile/index.html.twig', [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'pp' => $user->getProfilepicture(),
            'bgp' => $user->getBackgroundpicture(), 
            'created_at' => $user->getCreated_At(),
            'downvote' => $vote ? $vote->getDownvote() : 0,
            'upvote' => $vote ? $vote->getUpvote() : 0,
            'announces' => $user->getAnnounces(),
            'profileform' => $form->createView()
        ]);
    }
}

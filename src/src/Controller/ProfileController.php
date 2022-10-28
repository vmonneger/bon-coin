<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Vote;
use App\Entity\Announces;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $userID = $this->getUser()->getId();

        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $userID));
        $user = $entityManager->getRepository(User::class)->find($userID);

        return $this->render('profile/index.html.twig', [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'created_at' => $user->getCreated_At(), 
            'downvote' => $vote ? $vote->getDownvote() : 0,
            'upvote' => $vote ? $vote->getUpvote() : 0,
            'announces' => $user->getAnnounces()
        ]);
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

        $upvoteArray = $vote->getUpvote();
        $downvoteArray = $vote->getDownvote();

        $upvoteDisabled = false;
        $downvoteDisabled = false;

        if (in_array($currentUserID, $upvoteArray)) {
            global $upvoteDisabled;
            $upvoteDisabled = true;
        }

        if (in_array($currentUserID, $downvoteArray)) {
            global $upvoteDisabled;
            $downvoteDisabled = true;
        }
        
        return $this->render('profile/getOne.html.twig', [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'downvote' => $vote ? count($vote->getDownvote()) : 0,
            'upvote' => $vote ? count($vote->getUpvote()) : 0,
            'upvoteDisabled' => $upvoteDisabled ? 'disabled' : '',
            'downvoteDisabled' => $downvoteDisabled ? 'disabled' : '',
        ]);
    }
}

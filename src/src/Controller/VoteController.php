<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends AbstractController
{
    #[Route('/vote/{sellerId}', name: 'app_vote', methods: "POST")]
    public function postVote($sellerId, EntityManagerInterface $entityManager, Request $request): Response
    {   

        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $sellerId));

        $upvoteArray = $vote->getUpvote();
        $downvoteArray = $vote->getDownvote();

        $voteRequest = $request->request->get('vote');
        $loggedUserID = $this->getUser()->getId();

        $alreadyUpvote = false;
        $alreadyDownvote = false;

        if (in_array($loggedUserID, $upvoteArray)) {
            global $alreadyUpvote;
            $alreadyUpvote = true;
        }

        if (in_array($loggedUserID, $downvoteArray)) {
            global $alreadyDownvote;
            $alreadyDownvote = true;
        }

        if ($voteRequest === 'up') {
            if ($alreadyUpvote) {
                return $this->json([
                    'error' => 'Tu as déjà upvote'
                ]);
            }

            if ($alreadyDownvote) {
                $downvoteArray = array_filter($downvoteArray, function ($element) use ($loggedUserID) {
                    return $element !== $loggedUserID;
                });
                $vote->setDownvote($downvoteArray);
            }
            $upvoteArray[] = $loggedUserID;
            $vote->setUpvote($upvoteArray);
            $entityManager->persist($vote);
            $entityManager->flush();
        }
        
        if ($voteRequest === 'down') {
            if ($alreadyDownvote) {
                return $this->json([
                    'error' => 'Tu as déjà downvote'
                ]);
            }

            if ($alreadyUpvote) {
                $upvoteArray = array_filter($upvoteArray, function ($element) use ($loggedUserID) {
                    return $element !== $loggedUserID;
                });

                $vote->setUpvote($upvoteArray);
            }

            $downvoteArray[] = $loggedUserID;
            $vote->setDownvote($downvoteArray);
            $entityManager->persist($vote);
            $entityManager->flush();
        }

        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        return $this->json([
            'downvote' => $downvoteArray,
            'upvote' => $upvoteArray,
        ]);
    //     return $this->render('vote/index.html.twig', [
    //         'controller_name' => 'VoteController',
    //     ]);
    }

    #[Route('/vote', name: 'app_votez')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $voteCount = 10;
        $repository = $entityManager->getRepository(User::class);

        $user = $repository->findAll();

            return $this->render('vote/index.html.twig', [
            'controller_name' => 'VoteController',
        ]);
    }
}

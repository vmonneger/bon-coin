<?php

namespace App\Controller;

use App\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class VoteController extends AbstractController
{
    #[Route('/vote/{sellerId}/{direction}', name: 'app_vote', methods: "POST")]
    public function postVote($sellerId, $direction, EntityManagerInterface $entityManager): Response
    {   
        if ($sellerId === strval($this->getUser()->getId())) {
            return $this->json([
                'error' => 'Tu ne peux pas voter pour toi'
            ]);
        }

        $vote = $entityManager->getRepository(Vote::class)->FindOneBy(array('seller_id' => $sellerId));

        $upvoteArray = $vote->getUpvote();
        $downvoteArray = $vote->getDownvote();

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

        if ($direction === 'up') {
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
        
        if ($direction === 'down') {
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

        return $this->json([
            'voteDown' => count($downvoteArray),
            'voteUp' => count($upvoteArray)
        ]);
    }
}

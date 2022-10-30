<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Question;
use App\Entity\Announces;
use App\Entity\Response;

class ResponseController extends AbstractController
{
    #[Route('/response/{announceId}/{questionId}', name: 'app_response')]
    public function index($announceId, $questionId, Request $request, EntityManagerInterface $entityManager)
    {
        $responseRequest = $request->request->get('response');

        $repositoryAnnounce = $entityManager->getRepository(Announces::class);
        $repositoryQuestion = $entityManager->getRepository(Question::class);

        $question = $repositoryQuestion->find($questionId);
        $announce = $repositoryAnnounce->find($announceId);

        if ($responseRequest) {
            $response = new Response();
            $response->setMessage($responseRequest)
                ->setQuestion($question)
                ->setAnnounce($announce);
                // ->setAskedAt(new \DateTimeImmutable('now'));
                
            $announce->addResponse($response);


            $entityManager->persist($response);
            $entityManager->persist($announce);
            $entityManager->flush();
        }

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}

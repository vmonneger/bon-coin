<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Question;
use App\Entity\Announces;

class QuestionController extends AbstractController {
    #[Route("/question/{announceId}", name: "app_question", methods: "POST")]
    public function index($announceId, Request $request, EntityManagerInterface $entityManager)
    {
        $questionRequest = $request->request->get('question');

        $repository = $entityManager->getRepository(Announces::class);
        $announce = $repository->find($announceId);

        if ($questionRequest) {
            $question = new Question();
            $question->setQuestion($questionRequest)
                ->setUser($this->getUser())
                ->setAnnounce($announce)
                ->setAskedAt(new \DateTimeImmutable('now'));
                
            $announce->addQuestion($question);

            $entityManager->persist($question);
            $entityManager->persist($announce);
            $entityManager->flush();
        }

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}

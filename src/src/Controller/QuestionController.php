<?php

namespace App\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController {
    #[Route("/", "app_index")]
    public function index(LoggerInterface $logger): Response
    {
        $logger->notice("coucou je suis le log");
        return $this->render("show.html.twig", [
            "question" => "nfkqnfnvf",
            "answers" => [
                "coucou",
                "les",
                "copains"
            ]
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends AbstractController
{
    #[Route("/{quelqueChose}", "app_index")]

    public function index($quelqueChose, bool $isDebug): Response
    {
        return $this->render('show.html.twig', [
            "questions" => [
                "pourquoi...1",
                "pourquoi....2",
                "pourquoi....3"
            ],
            "answers" => [
                "&1...",
                "&2...",
                "&3..."
            ]
        ]);
    }
}
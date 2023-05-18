<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WordController extends AbstractController
{
    #[Route('/word', name: 'app_word')]
    public function index(): Response
    {
        return $this->render('word/index.html.twig', [
            'controller_name' => 'WordController',
        ]);
    }
}

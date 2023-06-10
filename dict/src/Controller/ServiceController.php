<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServiceController extends AbstractController
{

    private $apiClient;

    public function __construct(HttpClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    private function downloadDictionary(): ?string
    {
        $response = $this->apiClient->request('GET', 'http://127.0.0.1:8000/api/words');

        if ($response->getStatusCode() === 200) {
            return $response->getContent();
        }

        return null;
    }

    #[Route('/randomWordService', name:'randomWord', methods:['GET'])]
    public function getRandomWord(): Response
    {
        $dictionary = $this->downloadDictionary();
        $wordsArray = json_decode($dictionary, true);

        if (is_array($wordsArray) && count($wordsArray) > 0) {
            $randomKey = array_rand($wordsArray['hydra:member']);
            $randomWord = $wordsArray['hydra:member'][$randomKey];
        } else {
            $randomWord = 'No word found';
        }


        return new Response(json_encode($randomWord), 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}
